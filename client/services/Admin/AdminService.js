const AdminService = {
  updateAllowedDomain (form = {}) {
    return axios.post(route('company.update.allowed.domain'), form)
  }
}

export { AdminService }