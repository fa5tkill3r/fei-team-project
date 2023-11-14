package main

import (
	"fmt"
	"os"
	"os/exec"

	"github.com/gin-gonic/gin"
)

func main() {
	r := gin.Default()
	r.GET("/build", func(c *gin.Context) {
		cmd := exec.Command("pnpm", "build")
		cmd.Dir = "../"
		cmd.Stderr = os.Stderr
		stdout, err := cmd.Output()

		if err != nil {
			panic(err)
		}

		fmt.Print(string(stdout))
		c.Status(204)
	})
	r.Run() // listen and serve on 0.0.0.0:8080
}
