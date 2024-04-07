package main

import (
	"fmt"
	"log"
	"net/http"
	"net/http/httputil"
	"net/url"
	"os"
	"os/exec"
	"strings"
	"time"

	"github.com/gin-gonic/gin"
	"golang.org/x/sync/errgroup"
)

var (
	g errgroup.Group
)

// add allowed api paths here (without leading slash!)
var allowedApiPaths = map[string]bool{
	"/api/incidents": true,
}

var apiUrl = os.Getenv("PROXY_API_URL")

func build() {
	cmd := exec.Command("pnpm", "build")
	cmd.Dir = "./"
	cmd.Stderr = os.Stderr
	stdout, err := cmd.Output()

	if err != nil {
		fmt.Print(string(stdout))
	}
}

func builderRouter() http.Handler {
	r := gin.New()
	r.POST("/build", func(c *gin.Context) {
		build()
		c.Status(204)
	})

	return r
}

func apiRouter() http.Handler {
	r := gin.New()
	r.Any("/*proxyPath", func(c *gin.Context) {
		path := c.Param("proxyPath")
		path = strings.TrimRight(path, "/")

		if _, ok := allowedApiPaths[path]; !ok {
			c.Status(404)
			return
		}

		remote, err := url.Parse(apiUrl)
		if err != nil {
			panic(err)
		}

		proxy := httputil.NewSingleHostReverseProxy(remote)
		proxy.Director = func(req *http.Request) {
			req.Header = c.Request.Header
			req.Host = remote.Host
			req.URL.Scheme = remote.Scheme
			req.URL.Host = remote.Host
			req.URL.Path = path
		}

		proxy.ModifyResponse = func(resp *http.Response) error {
			if resp.Header.Get("Access-Control-Allow-Origin") == "" {
				resp.Header.Set("Access-Control-Allow-Origin", "*")
			}

			return nil
		}

		proxy.ServeHTTP(c.Writer, c.Request)
	})

	return r
}

func main() {
	apiServer := &http.Server{
		Addr:         ":8080",
		Handler:      apiRouter(),
		ReadTimeout:  5 * time.Second,
		WriteTimeout: 10 * time.Second,
	}

	builderServer := &http.Server{
		Addr:         ":8081",
		Handler:      builderRouter(),
		ReadTimeout:  5 * time.Second,
		WriteTimeout: 10 * time.Second,
	}

	g.Go(func() error {
		return apiServer.ListenAndServe()
	})

	g.Go(func() error {
		return builderServer.ListenAndServe()
	})

	go build()

	if err := g.Wait(); err != nil {
		log.Fatal(err)
	}
}
