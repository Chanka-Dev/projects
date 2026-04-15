import api from './index'

export const exchangeRatesApi = {
    getAll: () => api.get('/exchange-rates'),
    getLatest: () => api.get('/exchange-rates/latest'),
    create: (data) => api.post('/exchange-rates', data),
    update: (id, data) => api.put(`/exchange-rates/${id}`, data),
    remove: (id) => api.delete(`/exchange-rates/${id}`),
}
