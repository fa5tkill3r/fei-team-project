import kyclient from 'ky'

const defaultOptions = {
  prefixUrl: import.meta.env.VITE_API_URL,
  headers: {
    accept: 'application/json',
  },
  hooks: {
    beforeRequest: [
      request => {
        // const token = sessionStorage.getItem('token')
        const token = import.meta.env.VITE_TOKEN
        if (token) {
          request.headers.set('authorization', `Bearer ${token}`)
        }
      },
    ],

  },
}

export let ky = kyclient.create({ ...defaultOptions })

export function setDefaults(options) {
  ky = ky.extend(options)
}
