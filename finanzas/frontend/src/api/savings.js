import api from './index'
export const savingsApi = {
  getAll: () => api.get('/savings'),
  getOne: (id) => api.get(`/savings/${id}`),
  create: (data) => api.post('/savings', data),
  update: (id, data) => api.put(`/savings/${id}`, data),
  remove: (id) => api.delete(`/savings/${id}`),
  addMovement: (id, data) => api.post(`/savings/${id}/movements`, data),
  getMovements: (id) => api.get(`/savings/${id}/movements`),
}
