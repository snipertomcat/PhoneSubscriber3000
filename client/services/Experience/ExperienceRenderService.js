const RenderService = {
  toUnix (date) {
    return Math.round(date.getTime() / 1000);
  },
  finishSession (form) {
    let url = '/student/progress' + '?time=' + RenderService.toUnix(new Date())
    return axios.post(url, form)
  },
  sendAccessData (params) {
    return axios({
      method: 'POST',
      url: route('user.set_progress') + '?time=' + RenderService.toUnix(new Date()),
      params: params
    })
  },
  updateUserTime (params) {
    return axios({
      method: 'POST',
      url: route('user.set_time') + '?time=' + RenderService.toUnix(new Date()),
      params: params
    })
  }
}

export {RenderService}
