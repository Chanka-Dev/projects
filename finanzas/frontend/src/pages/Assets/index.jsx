import { useState } from 'react'
import { assetsApi } from '@/api/assets'
import { useApi } from '@/hooks/useApi'
import { formatDate, formatCurrency, formatAmount, equivalentLabel, toBOB } from '@/utils/formatters'
import { useExchangeRate } from '@/hooks/useExchangeRate'
import PageHeader from '@/components/common/PageHeader'
import Modal from '@/components/common/Modal'
import ConfirmDialog from '@/components/common/ConfirmDialog'
import EmptyState from '@/components/common/EmptyState'
import FormField, { Input, Select, Textarea } from '@/components/common/FormField'

const USERS = [{ value: '', label: '— Sin asignar' }, { value: '1', label: 'Pedro' }, { value: '2', label: 'Marce' }]
const ASSET_TYPES = [
  { value: 'real_estate', label: 'Bien raíz' },
  { value: 'vehicle', label: 'Vehículo' },
  { value: 'investment', label: 'Inversión' },
  { value: 'other', label: 'Otro' },
]
const TYPE_ICONS = { real_estate: '🏠', vehicle: '🚗', investment: '📈', other: '📦' }

const emptyAsset = { name: '', type: 'other', purchaseValue: '', estimatedValue: '', purchaseDate: '', notes: '', ownerUserId: '', debtId: '', currency: 'BOB' }

export default function Assets() {
  const { data: assets, loading, refetch } = useApi(() => assetsApi.getAll())
  const { rate } = useExchangeRate()

  const [modal, setModal] = useState(false)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyAsset)
  const [deleting, setDeleting] = useState(null)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState(null)

  const totalEstimated = (assets || []).reduce((s, a) => s + parseFloat(a.estimatedValue || a.estimated_value || 0), 0)
  const totalPurchase = (assets || []).reduce((s, a) => s + parseFloat(a.purchaseValue || a.purchase_value || 0), 0)
  const gain = totalEstimated - totalPurchase

  const openCreate = () => { setEditing(null); setForm(emptyAsset); setError(null); setModal(true) }
  const openEdit = (a) => {
    setEditing(a)
    setForm({
      name: a.name, type: a.type || 'other',
      purchaseValue: a.purchaseValue || '',
      estimatedValue: a.estimatedValue || '',
      purchaseDate: a.purchaseDate || '',
      notes: a.notes || '', ownerUserId: a.ownerUserId || '',
      debtId: a.debtId || '',
      currency: a.currency || 'BOB'
    })
    setError(null); setModal(true)
  }

  const handleSave = async (e) => {
    e.preventDefault(); setSaving(true); setError(null)
    try {
      if (editing) await assetsApi.update(editing.id, form)
      else await assetsApi.create(form)
      setModal(false); refetch()
    } catch (err) { setError(err.response?.data?.message || 'Error al guardar') }
    finally { setSaving(false) }
  }

  const handleDelete = async () => {
    setSaving(true)
    try { await assetsApi.remove(deleting.id); setDeleting(null); refetch() }
    finally { setSaving(false) }
  }

  const ownerName = (id) => { const u = USERS.find(u => String(u.value) === String(id)); return u?.value ? u.label : null }

  return (
    <div className="space-y-6">
      <PageHeader
        title="Patrimonio"
        subtitle={`${(assets || []).length} bienes · Valor estimado: ${formatCurrency(totalEstimated)}`}
        action={
          <button onClick={openCreate} className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            + Nuevo bien
          </button>
        }
      />

      {/* Summary */}
      {(assets || []).length > 0 && (
        <div className="grid grid-cols-3 gap-4">
          <div className="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p className="text-xs text-gray-400 mb-1">Valor compra</p>
            <p className="font-bold text-gray-700">{formatCurrency(totalPurchase)}</p>
          </div>
          <div className="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p className="text-xs text-gray-400 mb-1">Valor estimado</p>
            <p className="font-bold text-blue-700">{formatCurrency(totalEstimated)}</p>
          </div>
          <div className="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p className="text-xs text-gray-400 mb-1">Plusvalía</p>
            <p className={`font-bold ${gain >= 0 ? 'text-emerald-600' : 'text-red-600'}`}>{gain >= 0 ? '+' : ''}{formatCurrency(gain)}</p>
          </div>
        </div>
      )}

      {loading && <p className="text-gray-400 text-sm">Cargando...</p>}
      {!loading && !assets?.length && (
        <div className="bg-white rounded-xl border border-gray-200">
          <EmptyState icon="🏛️" message="No hay bienes registrados" />
        </div>
      )}

      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        {(assets || []).map(a => {
          const purchase = parseFloat(a.purchaseValue || 0)
          const estimated = parseFloat(a.estimatedValue || 0)
          const currency = a.currency || 'BOB'
          const plusvalia = estimated - purchase
          const equivEstimated = equivalentLabel(estimated, currency, rate)
          const icon = TYPE_ICONS[a.type] || '📦'
          const typeLabel = ASSET_TYPES.find(t => t.value === a.type)?.label || a.type
          const owner = ownerName(a.ownerUserId)
          return (
            <div key={a.id} className="bg-white rounded-xl border border-gray-200 p-5">
              <div className="flex items-start justify-between mb-3">
                <div className="flex items-center gap-2">
                  <span className="text-2xl">{icon}</span>
                  <div>
                    <h3 className="font-semibold text-gray-900 leading-tight">{a.name}</h3>
                    <span className="text-xs text-gray-400">{typeLabel}</span>
                  </div>
                </div>
                <div className="flex gap-2">
                  <button onClick={() => openEdit(a)} className="text-gray-400 hover:text-blue-600 transition-colors text-sm">✏️</button>
                  <button onClick={() => setDeleting(a)} className="text-gray-400 hover:text-red-600 transition-colors text-sm">🗑️</button>
                </div>
              </div>

              {owner && (
                <span className="inline-block text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-medium mb-3 mr-1">
                  {owner}
                </span>
              )}
              {currency === 'USD' && (
                <span className="inline-block text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-700 font-semibold mb-3">USD</span>
              )}

              <div className="space-y-1 text-sm">
                {purchase > 0 && (
                  <div className="flex justify-between">
                    <span className="text-gray-400">Compra:</span>
                    <span className="text-gray-700">{formatAmount(purchase, currency)}</span>
                  </div>
                )}
                {estimated > 0 && (
                  <div className="flex justify-between">
                    <span className="text-gray-400">Estimado:</span>
                    <div className="text-right">
                      <span className="font-medium text-blue-700">{formatAmount(estimated, currency)}</span>
                      {equivEstimated && <p className="text-xs text-gray-400">{equivEstimated}</p>}
                    </div>
                  </div>
                )}
                {purchase > 0 && estimated > 0 && (
                  <div className="flex justify-between pt-1 border-t border-gray-100">
                    <span className="text-gray-400">Plusvalía:</span>
                    <span className={`font-semibold ${plusvalia >= 0 ? 'text-emerald-600' : 'text-red-600'}`}>
                      {plusvalia >= 0 ? '+' : ''}{formatAmount(plusvalia, currency)}
                    </span>
                  </div>
                )}
              </div>

              {a.purchaseDate && (
                <p className="text-xs text-gray-400 mt-3">Adquirido: {formatDate(a.purchaseDate)}</p>
              )}
              {a.notes && <p className="text-xs text-gray-400 mt-1 line-clamp-2">{a.notes}</p>}
            </div>
          )
        })}
      </div>

      {/* Modal bien */}
      <Modal open={modal} onClose={() => setModal(false)} title={editing ? 'Editar bien' : 'Nuevo bien patrimonial'} size="md">
        <form onSubmit={handleSave} className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            <FormField label="Nombre" required className="col-span-2">
              <Input required value={form.name} onChange={e => setForm({ ...form, name: e.target.value })} placeholder="Ej: Casa Cochabamba" />
            </FormField>
            <FormField label="Tipo">
              <Select value={form.type} onChange={e => setForm({ ...form, type: e.target.value })}>
                {ASSET_TYPES.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
              </Select>
            </FormField>
            <FormField label="Propietario">
              <Select value={form.ownerUserId} onChange={e => setForm({ ...form, ownerUserId: e.target.value })}>
                {USERS.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
              </Select>
            </FormField>
            <FormField label="Valor compra">
              <Input type="number" step="0.01" min="0" value={form.purchaseValue} onChange={e => setForm({ ...form, purchaseValue: e.target.value })} placeholder="0.00" />
            </FormField>
            <FormField label="Valor estimado" required>
              <Input type="number" step="0.01" min="0" required value={form.estimatedValue} onChange={e => setForm({ ...form, estimatedValue: e.target.value })} placeholder="0.00" />
            </FormField>
            <FormField label="Moneda" className="col-span-2">
              <Select value={form.currency} onChange={e => setForm({ ...form, currency: e.target.value })}>
                <option value="BOB">🇧🇴 BOB — Bolivianos</option>
                <option value="USD">🇺🇸 USD — Dólares</option>
              </Select>
            </FormField>
            <FormField label="Fecha adquisición">
              <Input type="date" value={form.purchaseDate} onChange={e => setForm({ ...form, purchaseDate: e.target.value })} />
            </FormField>
          </div>
          <FormField label="Notas">
            <Textarea value={form.notes} onChange={e => setForm({ ...form, notes: e.target.value })} placeholder="Descripción, ubicación, características..." />
          </FormField>
          {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
          <div className="flex justify-end gap-3 pt-2">
            <button type="button" onClick={() => setModal(false)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
            <button type="submit" disabled={saving} className="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white">{saving ? 'Guardando...' : 'Guardar'}</button>
          </div>
        </form>
      </Modal>

      <ConfirmDialog open={!!deleting} onClose={() => setDeleting(null)} onConfirm={handleDelete} loading={saving}
        title="Eliminar bien"
        message={`¿Eliminar "${deleting?.name}"?`} />
    </div>
  )
}
