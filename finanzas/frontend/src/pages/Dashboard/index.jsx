import { useState, useEffect } from 'react'
import { dashboardApi } from '@/api/dashboard'
import { formatCurrency, formatUSD } from '@/utils/formatters'

// Muestra el total en BOB con desglose "Bs. X + $ Y" cuando hay mezcla de monedas
function StatCard({ label, icon, color, data }) {
    const value = typeof data === 'object' ? (data?.totalBOB ?? 0) : (data ?? 0)
    const hasBOB = typeof data === 'object' && data?.bob > 0
    const hasUSD = typeof data === 'object' && data?.usd > 0
    const mixed = hasBOB && hasUSD

    return (
        <div className="bg-white rounded-xl border border-gray-200 p-5">
            <div className="flex items-center justify-between mb-3">
                <span className="text-sm text-gray-500">{label}</span>
                <span className="text-2xl">{icon}</span>
            </div>
            <p className={`text-2xl font-bold ${color}`}>{formatCurrency(value)}</p>
            {hasUSD && (
                <p className="text-xs text-gray-400 mt-1.5 leading-relaxed">
                    {mixed
                        ? <>{formatCurrency(data.bob)} <span className="text-gray-300">+</span> <span className="text-green-600 font-medium">{formatUSD(data.usd)}</span></>
                        : <span className="text-green-600 font-medium">{formatUSD(data.usd)} USD</span>
                    }
                    <span className="text-gray-300 ml-1.5">· t.c. paralelo</span>
                </p>
            )}
        </div>
    )
}

function RowItem({ label, bob, usd, total, colorClass }) {
    const hasUSD = usd > 0
    return (
        <div className="flex justify-between items-start">
            <span className="text-sm text-gray-500">{label}</span>
            <div className="text-right">
                <span className={`text-sm font-semibold ${colorClass}`}>{formatCurrency(total)}</span>
                {hasUSD && (
                    <p className="text-xs text-gray-400 mt-0.5">
                        {bob > 0 && <span>{formatCurrency(bob)} + </span>}
                        <span className="text-green-600">{formatUSD(usd)}</span>
                    </p>
                )}
            </div>
        </div>
    )
}

export default function Dashboard() {
    const [summary, setSummary] = useState(null)
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState(null)

    useEffect(() => {
        dashboardApi.getSummary()
            .then(({ data }) => setSummary(data))
            .catch(() => setError('No se pudo cargar el resumen'))
            .finally(() => setLoading(false))
    }, [])

    if (loading) return <p className="text-gray-400 text-sm">Cargando...</p>
    if (error) return <p className="text-red-500 text-sm">{error}</p>

    const balance = summary?.monthlyBalance ?? 0
    const rate = summary?.rate

    return (
        <div className="space-y-6">
            <div className="flex items-start justify-between">
                <div>
                    <h2 className="text-xl font-bold text-gray-900">Dashboard</h2>
                    <p className="text-sm text-gray-500 mt-1">Resumen financiero familiar</p>
                </div>
                {rate && (
                    <div className="text-right text-xs text-gray-400 leading-relaxed">
                        <p>Tipo de cambio paralelo</p>
                        <p className="font-semibold text-gray-600">1 USD = Bs. {parseFloat(rate.parallelRate).toFixed(2)}</p>
                    </div>
                )}
            </div>

            {/* Tarjetas principales */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <StatCard
                    label="Balance del mes" icon="📈"
                    color={balance >= 0 ? 'text-emerald-600' : 'text-red-600'}
                    data={balance}
                />
                <StatCard
                    label="Patrimonio neto" icon="🏠"
                    color="text-blue-600"
                    data={summary?.netWorth}
                />
                <StatCard
                    label="Ahorro total" icon="🏦"
                    color="text-violet-600"
                    data={summary?.savings}
                />
                <StatCard
                    label="Deuda pendiente" icon="📋"
                    color="text-red-600"
                    data={summary?.debtsPayable}
                />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                {/* Ingresos vs gastos */}
                <div className="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 className="font-semibold text-gray-700 mb-4">Ingresos vs. Gastos del mes</h3>
                    <div className="space-y-3">
                        <RowItem
                            label="💰 Ingresos"
                            bob={summary?.monthlyIncome ?? 0} usd={0}
                            total={summary?.monthlyIncome ?? 0}
                            colorClass="text-emerald-600"
                        />
                        <RowItem
                            label="💸 Gastos"
                            bob={summary?.monthlyExpenses ?? 0} usd={0}
                            total={summary?.monthlyExpenses ?? 0}
                            colorClass="text-red-600"
                        />
                        <div className="flex justify-between items-center border-t pt-3">
                            <span className="text-sm font-medium text-gray-700">Balance</span>
                            <span className={`text-sm font-bold ${balance >= 0 ? 'text-emerald-600' : 'text-red-600'}`}>
                                {formatCurrency(balance)}
                            </span>
                        </div>
                    </div>
                </div>

                {/* Deudas */}
                <div className="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 className="font-semibold text-gray-700 mb-4">Por cobrar vs. Por pagar</h3>
                    <div className="space-y-3">
                        <RowItem
                            label="✅ Por cobrar"
                            bob={summary?.debtsReceivable?.bob ?? 0}
                            usd={summary?.debtsReceivable?.usd ?? 0}
                            total={summary?.debtsReceivable?.totalBOB ?? 0}
                            colorClass="text-emerald-600"
                        />
                        <RowItem
                            label="❌ Por pagar"
                            bob={summary?.debtsPayable?.bob ?? 0}
                            usd={summary?.debtsPayable?.usd ?? 0}
                            total={summary?.debtsPayable?.totalBOB ?? 0}
                            colorClass="text-red-600"
                        />
                    </div>
                    {(summary?.debtsReceivable?.usd > 0 || summary?.debtsPayable?.usd > 0) && (
                        <p className="text-xs text-gray-400 mt-3 pt-3 border-t">
                            Montos en USD convertidos al tipo de cambio paralelo (Bs. {parseFloat(rate?.parallelRate || 9.4).toFixed(2)})
                        </p>
                    )}
                </div>
            </div>
        </div>
    )
}
