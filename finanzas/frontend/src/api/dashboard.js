import api from './index'
export const dashboardApi = {
  getSummary: () => api.get('/dashboard/summary'),
  getMonthlyBalance: (year, month) => api.get(`/dashboard/balance/${year}/${month}`),
}
