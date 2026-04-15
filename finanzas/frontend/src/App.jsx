import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom'
import { AuthProvider } from '@/context/AuthContext'
import { useAuth } from '@/hooks/useAuth'
import PageWrapper from '@/components/layout/PageWrapper'

import Login from '@/pages/Auth/Login'
import Dashboard from '@/pages/Dashboard'
import Incomes from '@/pages/Incomes'
import Expenses from '@/pages/Expenses'
import Savings from '@/pages/Savings'
import Debts from '@/pages/Debts'
import Assets from '@/pages/Assets'
import Tags from '@/pages/Tags'
import ExchangeRate from '@/pages/ExchangeRate'

function ProtectedRoute({ children }) {
  const { user, loading } = useAuth()
  if (loading) return <div className="flex h-screen items-center justify-center">Cargando...</div>
  if (!user) return <Navigate to="/login" replace />
  return children
}

function AppRoutes() {
  const { user } = useAuth()
  return (
    <Routes>
      <Route path="/login" element={user ? <Navigate to="/" replace /> : <Login />} />
      <Route path="/" element={<ProtectedRoute><PageWrapper /></ProtectedRoute>}>
        <Route index element={<Dashboard />} />
        <Route path="ingresos" element={<Incomes />} />
        <Route path="gastos" element={<Expenses />} />
        <Route path="ahorros" element={<Savings />} />
        <Route path="deudas" element={<Debts />} />
        <Route path="patrimonio" element={<Assets />} />
        <Route path="tags" element={<Tags />} />
        <Route path="tipo-cambio" element={<ExchangeRate />} />
      </Route>
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  )
}

function App() {
  return (
    <BrowserRouter basename="/finanzas">
      <AuthProvider>
        <AppRoutes />
      </AuthProvider>
    </BrowserRouter>
  )
}

export default App
