import api from './index'
export const incomesApi = {
  getAll: (params) => api.get('/incomes', { params }),
  getOne: (id) => api.get(`/incomes/${id}`),
  create: (data) => api.post('/incomes', data),
  update: (id, data) => api.put(`/incomes/${id}`, data),
  remove: (id) => api.delete(`/incomes/${id}`),
}
