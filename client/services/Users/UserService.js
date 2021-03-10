const UserService = {
  getFilteredUsers (form = {}) {
    return axios.get(route('users.filter'), {
      params: form
    })
  }
}

export { UserService }
