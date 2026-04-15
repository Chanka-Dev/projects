export default function Navbar({ onMenuClick }) {
  return (
    <header className="h-14 bg-white border-b border-gray-200 flex items-center px-4 gap-4 lg:px-6">
      <button
        onClick={onMenuClick}
        className="p-2 rounded-md text-gray-500 hover:bg-gray-100 lg:hidden"
      >
        ☰
      </button>
      <div className="flex-1" />
      <span className="text-sm text-gray-400">
        {new Date().toLocaleDateString('es-BO', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}
      </span>
    </header>
  )
}
