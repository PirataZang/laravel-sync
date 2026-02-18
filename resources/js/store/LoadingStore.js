import { reactive } from 'vue'

export const loadingStore = reactive({
  activeRequests: 0,

  start() {
    this.activeRequests++
  },

  stop() {
    if (this.activeRequests > 0) {
      this.activeRequests--
    }
  },

  get isLoading() {
    return this.activeRequests > 0
  }
})