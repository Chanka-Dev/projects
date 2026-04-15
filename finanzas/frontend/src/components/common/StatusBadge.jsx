const variants = {
    active: 'bg-yellow-50 text-yellow-700 border-yellow-200',
    paid: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    cancelled: 'bg-gray-100 text-gray-500 border-gray-200',
    income: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    expense: 'bg-red-50 text-red-700 border-red-200',
    payable: 'bg-red-50 text-red-700 border-red-200',
    receivable: 'bg-blue-50 text-blue-700 border-blue-200',
}

const labels = {
    active: 'Activo',
    paid: 'Pagado',
    cancelled: 'Cancelado',
    income: 'Ingreso',
    expense: 'Gasto',
    payable: 'Por pagar',
    receivable: 'Por cobrar',
}

export default function StatusBadge({ status }) {
    const cls = variants[status] || 'bg-gray-100 text-gray-500 border-gray-200'
    return (
        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border ${cls}`}>
            {labels[status] || status}
        </span>
    )
}
