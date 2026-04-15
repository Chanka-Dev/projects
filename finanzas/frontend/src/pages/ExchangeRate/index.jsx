import { useState } from 'react'
import { useApi } from '@/hooks/useApi'
import { exchangeRatesApi } from '@/api/exchangeRates'
import { formatDate } from '@/utils/formatters'
import PageHeader from '@/components/common/PageHeader'
import Modal from '@/components/common/Modal'
import ConfirmDialog from '@/components/common/ConfirmDialog'
import EmptyState from '@/components/common/EmptyState'
import FormField, { Input, Textarea } from '@/components/common/FormField'

const emptyForm = {
    officialRate: '',
    parallelRate: '',
    date: new Date().toISOString().slice(0, 10),
    notes: '',
}

export default function ExchangeRate() {
    const { data: rates, loading, refetch } = useApi(() => exchangeRatesApi.getAll())
    const [modal, setModal] = useState(false)
    const [editing, setEditing] = useState(null)
    const [form, setForm] = useState(emptyForm)
    const [deleting, setDeleting] = useState(null)
    const [saving, setSaving] = useState(false)
    const [error, setError] = useState(null)

    const latest = rates?.[0] ?? null

    const openCreate = () => {
        setEditing(null)
        setForm(emptyForm)
        setError(null)
        setModal(true)
    }
    const openEdit = (r) => {
        setEditing(r)
        setForm({
            officialRate: r.officialRate,
            parallelRate: r.parallelRate,
            date: r.date,
            notes: r.notes || '',
        })
        setError(null)
        setModal(true)
    }

    const handleSave = async (e) => {
        e.preventDefault()
        setSaving(true)
        setError(null)
        try {
            if (editing) await exchangeRatesApi.update(editing.id, form)
            else await exchangeRatesApi.create(form)
            setModal(false)
            refetch()
        } catch (err) {
            setError(err.response?.data?.message || 'Error al guardar')
        } finally { setSaving(false) }
    }

    const handleDelete = async () => {
        setSaving(true)
        try { await exchangeRatesApi.remove(deleting.id); setDeleting(null); refetch() }
        finally { setSaving(false) }
    }

    return (
        <div className="space-y-6">
            <PageHeader
                title="Tipo de Cambio"
                subtitle="USD / BOB — con historial de tasas"
                action={
                    <button onClick={openCreate} className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        + Registrar tasa
                    </button>
                }
            />

            {/* Panel tasa actual */}
            {latest && (
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div className="bg-white rounded-xl border border-gray-200 p-5 sm:col-span-3 lg:col-span-1">
                        <p className="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Última tasa registrada — {formatDate(latest.date)}</p>
                        <div className="grid grid-cols-2 gap-6">
                            <div>
                                <p className="text-xs text-gray-400 mb-1">Tasa oficial</p>
                                <p className="text-3xl font-bold text-gray-700">{Number(latest.officialRate).toFixed(2)}</p>
                                <p className="text-xs text-gray-400 mt-1">BOB por USD</p>
                            </div>
                            <div>
                                <p className="text-xs text-gray-400 mb-1">Tasa paralela</p>
                                <p className="text-3xl font-bold text-blue-600">{Number(latest.parallelRate).toFixed(2)}</p>
                                <p className="text-xs text-gray-400 mt-1">BOB por USD</p>
                            </div>
                        </div>
                        {latest.notes && (
                            <p className="text-xs text-gray-400 mt-3 border-t border-gray-100 pt-3">{latest.notes}</p>
                        )}
                    </div>

                    <div className="bg-blue-50 rounded-xl border border-blue-100 p-5 flex flex-col justify-center">
                        <p className="text-xs text-blue-500 font-medium mb-2">Equivalencias rápidas (tasa paralela)</p>
                        {[1, 5, 10, 50, 100].map(usd => (
                            <div key={usd} className="flex justify-between items-center py-1 border-b border-blue-100 last:border-0">
                                <span className="text-sm font-medium text-blue-700">$ {usd} USD</span>
                                <span className="text-sm text-gray-600">Bs. {(usd * Number(latest.parallelRate)).toFixed(2)}</span>
                            </div>
                        ))}
                    </div>

                    <div className="bg-gray-50 rounded-xl border border-gray-200 p-5 flex flex-col justify-center">
                        <p className="text-xs text-gray-500 font-medium mb-2">Diferencia oficial vs paralela</p>
                        <div className="space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-gray-500">Spread</span>
                                <span className="font-semibold text-orange-600">
                                    +{(Number(latest.parallelRate) - Number(latest.officialRate)).toFixed(2)} BOB
                                </span>
                            </div>
                            <div className="flex justify-between text-sm">
                                <span className="text-gray-500">Diferencia %</span>
                                <span className="font-semibold text-orange-600">
                                    +{(((Number(latest.parallelRate) - Number(latest.officialRate)) / Number(latest.officialRate)) * 100).toFixed(1)}%
                                </span>
                            </div>
                            <p className="text-xs text-gray-400 mt-2 pt-2 border-t border-gray-200">
                                Los valores en USD se convierten usando la tasa <strong>paralela</strong> en todo el sistema.
                            </p>
                        </div>
                    </div>
                </div>
            )}

            {/* Historial */}
            <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div className="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h3 className="text-sm font-semibold text-gray-600">Historial (últimas 30 tasas)</h3>
                </div>

                {loading && <p className="text-gray-400 text-sm p-6">Cargando...</p>}
                {!loading && !rates?.length && (
                    <EmptyState icon="💱" message="No hay tasas registradas" />
                )}

                {!loading && !!rates?.length && (
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead className="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th className="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                                    <th className="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Oficial</th>
                                    <th className="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Paralela</th>
                                    <th className="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Spread</th>
                                    <th className="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Notas</th>
                                    <th className="px-4 py-3" />
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {rates.map((r, idx) => {
                                    const spread = Number(r.parallelRate) - Number(r.officialRate)
                                    return (
                                        <tr key={r.id} className={`hover:bg-gray-50 ${idx === 0 ? 'bg-blue-50/40' : ''}`}>
                                            <td className="px-4 py-3 text-gray-700 whitespace-nowrap font-medium">
                                                {formatDate(r.date)}
                                                {idx === 0 && <span className="ml-2 text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full">actual</span>}
                                            </td>
                                            <td className="px-4 py-3 text-right text-gray-600">{Number(r.officialRate).toFixed(4)}</td>
                                            <td className="px-4 py-3 text-right font-semibold text-blue-600">{Number(r.parallelRate).toFixed(4)}</td>
                                            <td className="px-4 py-3 text-right text-orange-600">+{spread.toFixed(4)}</td>
                                            <td className="px-4 py-3 text-gray-400 text-xs max-w-xs truncate">{r.notes || '—'}</td>
                                            <td className="px-4 py-3">
                                                <div className="flex gap-2 justify-end">
                                                    <button onClick={() => openEdit(r)} className="text-gray-400 hover:text-blue-600 transition-colors">✏️</button>
                                                    <button onClick={() => setDeleting(r)} className="text-gray-400 hover:text-red-600 transition-colors">🗑️</button>
                                                </div>
                                            </td>
                                        </tr>
                                    )
                                })}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>

            {/* Modal crear/editar */}
            <Modal open={modal} onClose={() => setModal(false)} title={editing ? 'Editar tasa' : 'Registrar tipo de cambio'} size="sm">
                <form onSubmit={handleSave} className="space-y-4">
                    <FormField label="Fecha" required>
                        <Input type="date" required value={form.date} onChange={e => setForm({ ...form, date: e.target.value })} />
                    </FormField>
                    <div className="grid grid-cols-2 gap-4">
                        <FormField label="Tasa oficial (BOB/USD)" required>
                            <Input type="number" step="0.0001" min="0.01" required
                                value={form.officialRate}
                                onChange={e => setForm({ ...form, officialRate: e.target.value })}
                                placeholder="6.9600" />
                        </FormField>
                        <FormField label="Tasa paralela (BOB/USD)" required>
                            <Input type="number" step="0.0001" min="0.01" required
                                value={form.parallelRate}
                                onChange={e => setForm({ ...form, parallelRate: e.target.value })}
                                placeholder="9.4000" />
                        </FormField>
                    </div>
                    <FormField label="Notas">
                        <Textarea value={form.notes} onChange={e => setForm({ ...form, notes: e.target.value })}
                            placeholder="Ej: Valor de referencia blue chip, mercado informal..." />
                    </FormField>
                    {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
                    <div className="flex justify-end gap-3 pt-2">
                        <button type="button" onClick={() => setModal(false)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
                        <button type="submit" disabled={saving} className="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white">
                            {saving ? 'Guardando...' : 'Guardar'}
                        </button>
                    </div>
                </form>
            </Modal>

            <ConfirmDialog open={!!deleting} onClose={() => setDeleting(null)} onConfirm={handleDelete} loading={saving}
                title="Eliminar tasa"
                message={`¿Eliminar la tasa del ${formatDate(deleting?.date)}?`} />
        </div>
    )
}
