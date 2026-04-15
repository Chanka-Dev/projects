import api from './index'
export const debtsApi = {
  getAll: (params) => api.get('/debts', { params }),
  getOne: (id) => api.get(`/debts/${id}`),
  create: (data) => api.post('/debts', data),
  update: (id, data) => api.put(`/debts/${id}`, data),
  remove: (id) => api.delete(`/debts/${id}`),
  addPayment: (id, data) => api.post(`/debts/${id}/payments`, data),
  getPayments: (id) => api.get(`/debts/${id}/payments`),
}
