import { build } from 'astro'
import { createServer } from 'node:http'

const port = process.env.PORT || 3000
let building = false

function tryParse(str) {
  try {
    console.log('str', str)

    return JSON.parse(str)
  } catch (err) {
    console.log('err', err)
    return null
  }
}

const server = createServer((req, res) => {
  if (!req.url || req.method !== 'POST') {
    res.writeHead(404)
    res.end()
    return
  }

  const buf = Buffer.alloc(512, 0)
  let offset = 0

  req.on('data', (chunk) => {
    buf.set(chunk, offset)
    offset += chunk.length
  })

  req.on('end', () => {
    const body = tryParse(buf.slice(0, offset).toString('utf8'))

    if (!body?.event) {
      res.writeHead(400)
      res.end()
      return
    }

    if (building) {
      res.writeHead(204)
      res.end()
      return
    }

    building = true

    build({})
      .then(() => {
        building = false

        res.writeHead(204)
        res.end()
      })
  })

  // const url = new URL(req.url)
})

await build({ logLevel: 'warn' })

server.listen(port, () => {
  console.log('http://localhost:3000')
})

