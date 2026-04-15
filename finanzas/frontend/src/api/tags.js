import api from './index'
export const tagsApi = {
  getAll: (params) => api.get('/tags', { params }),
  create: (data) => api.post('/tags', data),
  update: (id, data) => api.put(`/tags/${id}`, data),
  remove: (id) => api.delete(`/tags/${id}`),
}
