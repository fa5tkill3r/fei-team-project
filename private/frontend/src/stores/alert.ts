import { ref } from 'vue'
import { defineStore } from 'pinia'


export const useAlertStore = defineStore('alert', () => {
  const alerts = ref<Alert[]>([])

  enum AlertType {
    SUCCESS = 'success',
    ERROR = 'error',
    INFO = 'info',
    WARNING = 'warning',
  }
  interface Alert {
    type: AlertType
    message: string
    fade: boolean
  }


  function addAlert(message: string, type: AlertType = AlertType.INFO) {
    alerts.value.push({
      type,
      message,
      fade: false,
    })

    setTimeout(() => {
      alerts.value[0].fade = true

      setTimeout(() => {
        alerts.value.shift()
      }, 1000)
    }, 3000)
  }

  return { alerts, addAlert, AlertType }
})
