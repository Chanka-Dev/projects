export default function EmptyState({ icon = '📭', message = 'No hay registros', action }) {
    return (
        <div className="flex flex-col items-center justify-center py-16 text-center">
            <span className="text-4xl mb-3">{icon}</span>
            <p className="text-gray-500 text-sm mb-4">{message}</p>
            {action}
        </div>
    )
}
