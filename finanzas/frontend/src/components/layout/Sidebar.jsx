import { NavLink } from 'react-router-dom'
import { useAuth } from '@/hooks/useAuth'

const navItems = [
  { to: '/', label: 'Dashboard', icon: '📊' },
  { to: '/ingresos', label: 'Ingresos', icon: '💰' },
  { to: '/gastos', label: 'Gastos', icon: '💸' },
  { to: '/ahorros', label: 'Ahorros', icon: '🏦' },
  { to: '/deudas', label: 'Deudas', icon: '📋' },
  { to: '/patrimonio', label: 'Patrimonio', icon: '🏠' },
  { to: '/tags', label: 'Tags', icon: '🏷️' },
  { to: '/tipo-cambio', label: 'Tipo de Cambio', icon: '💱' },
]

export default function Sidebar({ open, onClose }) {
  const { user, logout } = useAuth()
  return (
    <>
      {open && (
        <div className="fixed inset-0 bg-black/40 z-20 lg:hidden" onClick={onClose} />
      )}
      <aside className={`
        fixed top-0 left-0 h-full w-64 bg-white border-r border-gray-200 z-30
        flex flex-col transition-transform duration-200
        ${open ? 'translate-x-0' : '-translate-x-full'}
        lg:translate-x-0 lg:static lg:z-auto
      `}>
        {/* Logo */}
        <div className="p-6 border-b border-gray-100">
          <h1 className="text-xl font-bold text-emerald-700">💼 Finanzas</h1>
          <p className="text-xs text-gray-400 mt-1">Familia</p>
        </div>

        {/* Nav */}
        <nav className="flex-1 p-4 space-y-1 overflow-y-auto">
          {navItems.map(({ to, label, icon }) => (
            <NavLink
              key={to}
              to={to}
              end={to === '/'}
              onClick={onClose}
              className={({ isActive }) => `
                flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                ${isActive
                  ? 'bg-emerald-50 text-emerald-700'
                  : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'}
              `}
            >
              <span>{icon}</span>
              {label}
            </NavLink>
          ))}
        </nav>

        {/* User */}
        <div className="p-4 border-t border-gray-100">
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">
              {user?.name?.[0]?.toUpperCase()}
            </div>
            <div className="flex-1 min-w-0">
              <p className="text-sm font-medium text-gray-700 truncate">{user?.name}</p>
              <p className="text-xs text-gray-400 truncate">{user?.email}</p>
            </div>
            <button onClick={logout} className="text-gray-400 hover:text-red-500 transition-colors" title="Cerrar sesión">
              ←
            </button>
          </div>
        </div>
      </aside>
    </>
  )
}
