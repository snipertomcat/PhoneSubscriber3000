const TaxonomyService = {
  getByTaxonomy (form = {}) {
    return axios.get(`${route('taxonomy.view', form.type)}`, {
      params: form
    })
  },
  getUserTaxonomy (form = {}) {
    return axios.get(route('taxonomy.users'), {
      params: form
    })
  },
  updateTaxonomy (form) {
    return axios.patch( `${route('taxonomy.update', {id: form.id})}`, form)
  },
  deleteTaxonomy(id) {
    return axios.delete(`${route('taxonomy.delete', {id: id})}`)
  },
  storeTaxonomy(form) {
    return axios.post(route('taxonomy.store'), form)
  },
  syncUsers (form) {
    return axios.post(route('taxonomy.users.store'), form)
  }
}

export { TaxonomyService }