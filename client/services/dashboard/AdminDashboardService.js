const AdminDashboardService = {
  getEnrollmentUserDetails (form) {
    return axios.get(route('dashboard.experience.enrollment.user.details'), {
      params: form
    })
  },
  getExperienceEnrollmentUserRecurrence (form) {
    return axios.get(route('dashboard.experience.enrollment.user.recurrence'), {
      params: form
    })
  },
  getUserExperienceSessionActivities (form) {
    return axios.get(route('dashboard.experience.enrollment.user.activities'), {
      params: form
    })
  }
}

export { AdminDashboardService }
