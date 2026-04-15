import api from './index'
export const auditLogsApi = {
  getAll: (params) => api.get('/audit-logs', { params }),
  getOne: (id) => api.get(`/audit-logs/${id}`),
  revert: (id) => api.post(`/audit-logs/${id}/revert`),
}
