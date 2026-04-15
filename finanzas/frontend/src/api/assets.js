import api from './index'
export const assetsApi = {
  getAll: () => api.get('/assets'),
  getOne: (id) => api.get(`/assets/${id}`),
  create: (data) => api.post('/assets', data),
  update: (id, data) => api.put(`/assets/${id}`, data),
  remove: (id) => api.delete(`/assets/${id}`),
}
