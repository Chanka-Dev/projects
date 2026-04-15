import { useState } from 'react'
import { useApi } from '@/hooks/useApi'
import { savingsApi } from '@/api/savings'
import { formatCurrency, formatDate, calcPercent, formatAmount, equivalentLabel } from '@/utils/formatters'
import { useExchangeRate } from '@/hooks/useExchangeRate'
import PageHeader from '@/components/common/PageHeader'
import Modal from '@/components/common/Modal'
import ConfirmDialog from '@/components/common/ConfirmDialog'
import EmptyState from '@/components/common/EmptyState'
import FormField, { Input, Select, Textarea } from '@/components/common/FormField'

const emptyGoal = { name: '', target_amount: '', currency: 'BOB', notes: '' }
const emptyMovement = { amount: '', description: '', date: new Date().toISOString().slice(0, 10) }

export default function Savings() {
  const { data: savings, loading, refetch } = useApi(() => savingsApi.getAll())
  const { rate } = useExchangeRate()
  const [goalModal, setGoalModal] = useState(false)
  const [movModal, setMovModal] = useState(null) // = savings item
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyGoal)
  const [movForm, setMovForm] = useState(emptyMovement)
  const [movType, setMovType] = useState('deposit')
  const [deleting, setDeleting] = useState(null)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState(null)

  const totalSaved = (savings || []).reduce((s, i) => s + parseFloat(i.currentAmount || i.current_amount || 0), 0)

  const openCreate = () => { setEditing(null); setForm(emptyGoal); setError(null); setGoalModal(true) }
  const openEdit = (s) => {
    setEditing(s)
    setForm({ name: s.name, target_amount: s.targetAmount || s.target_amount || '', currency: s.currency || 'BOB', notes: s.notes || '' })
    setError(null); setGoalModal(true)
  }
  const openMovement = (s) => { setMovModal(s); setMovForm(emptyMovement); setMovType('deposit'); setError(null) }

  const handleSaveGoal = async (e) => {
    e.preventDefault(); setSaving(true); setError(null)
    try {
      if (editing) await savingsApi.update(editing.id, form)
      else await savingsApi.create(form)
      setGoalModal(false); refetch()
    } catch (err) { setError(err.response?.data?.message || 'Error al guardar') }
    finally { setSaving(false) }
  }

  const handleAddMovement = async (e) => {
    e.preventDefault(); setSaving(true); setError(null)
    try {
      const amount = movType === 'withdrawal' ? -Math.abs(parseFloat(movForm.amount)) : Math.abs(parseFloat(movForm.amount))
      await savingsApi.addMovement(movModal.id, { ...movForm, amount })
      setMovModal(null); refetch()
    } catch (err) { setError(err.response?.data?.message || 'Error al guardar') }
    finally { setSaving(false) }
  }

  const handleDelete = async () => {
    setSaving(true)
    try { await savingsApi.remove(deleting.id); setDeleting(null); refetch() }
    finally { setSaving(false) }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        title="Ahorros"
        subtitle={`${(savings || []).length} metas · Total ahorrado: ${formatCurrency(totalSaved)}`}
        action={
          <button onClick={openCreate} className="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg transition-colors">
            + Nueva meta
          </button>
        }
      />

      {loading && <p className="text-gray-400 text-sm">Cargando...</p>}
      {!loading && !savings?.length && (
        <div className="bg-white rounded-xl border border-gray-200">
          <EmptyState icon="🏦" message="No hay metas de ahorro" />
        </div>
      )}

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {(savings || []).map(s => {
          const current = parseFloat(s.currentAmount || s.current_amount || 0)
          const target = parseFloat(s.targetAmount || s.target_amount || 0)
          const currency = s.currency || 'BOB'
          const pct = target > 0 ? calcPercent(current, target) : null
          const equiv = equivalentLabel(current, currency, rate)
          return (
            <div key={s.id} className="bg-white rounded-xl border border-gray-200 p-5">
              <div className="flex items-start justify-between mb-3">
                <div>
                  <h3 className="font-semibold text-gray-900">{s.name}</h3>
                  {s.notes && <p className="text-xs text-gray-400 mt-0.5">{s.notes}</p>}
                </div>
                <div className="flex gap-2">
                  <button onClick={() => openEdit(s)} className="text-gray-400 hover:text-blue-600 transition-colors text-sm">✏️</button>
                  <button onClick={() => setDeleting(s)} className="text-gray-400 hover:text-red-600 transition-colors text-sm">🗑️</button>
                </div>
              </div>

              <div className="mb-3">
                <div className="flex justify-between text-sm mb-1.5">
                  <div>
                    <span className="font-bold text-violet-700">{formatAmount(current, currency)}</span>
                    {equiv && <span className="text-xs text-gray-400 ml-2">{equiv}</span>}
                  </div>
                  {target > 0 && <span className="text-gray-400">meta: {formatAmount(target, currency)}</span>}
                </div>
                {pct !== null && (
                  <div className="w-full bg-gray-100 rounded-full h-2">
                    <div className="h-2 rounded-full bg-violet-500 transition-all" style={{ width: `${pct}%` }} />
                  </div>
                )}
                {pct !== null && (
                  <p className="text-xs text-gray-400 mt-1">{pct}% alcanzado</p>
                )}
              </div>

              {currency !== 'BOB' && (
                <span className="inline-block text-xs px-2 py-0.5 rounded-full bg-green-50 text-green-700 font-semibold mb-3">{currency}</span>
              )}

              <button onClick={() => openMovement(s)}
                className="w-full py-2 text-sm font-medium text-violet-700 bg-violet-50 hover:bg-violet-100 rounded-lg transition-colors">
              + Movimiento
            </button>
            </div>
      )
        })}
    </div>

      {/* Modal meta */ }
  <Modal open={goalModal} onClose={() => setGoalModal(false)} title={editing ? 'Editar meta' : 'Nueva meta de ahorro'} size="sm">
    <form onSubmit={handleSaveGoal} className="space-y-4">
      <FormField label="Nombre" required>
        <Input required value={form.name} onChange={e => setForm({ ...form, name: e.target.value })} placeholder="Ej: Fondo de emergencia" />
      </FormField>
      <FormField label="Meta — opcional">
        <Input type="number" step="0.01" min="0" value={form.target_amount} onChange={e => setForm({ ...form, target_amount: e.target.value })} placeholder="0.00" />
      </FormField>
      <FormField label="Moneda">
        <Select value={form.currency} onChange={e => setForm({ ...form, currency: e.target.value })}>
          <option value="BOB">🇧🇴 BOB — Bolivianos</option>
          <option value="USD">🇺🇸 USD — Dólares</option>
        </Select>
      </FormField>
      <FormField label="Notas">
        <Textarea value={form.notes} onChange={e => setForm({ ...form, notes: e.target.value })} placeholder="Descripción o propósito" />
      </FormField>
      {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
      <div className="flex justify-end gap-3 pt-2">
        <button type="button" onClick={() => setGoalModal(false)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
        <button type="submit" disabled={saving} className="px-4 py-2 text-sm rounded-lg bg-violet-600 hover:bg-violet-700 disabled:opacity-60 text-white">{saving ? 'Guardando...' : 'Guardar'}</button>
      </div>
    </form>
  </Modal>

  {/* Modal movimiento */ }
      <Modal open={!!movModal} onClose={() => setMovModal(null)} title={`Movimiento — ${movModal?.name}`} size="sm">
        <form onSubmit={handleAddMovement} className="space-y-4">
          <div className="flex gap-2">
            <button type="button" onClick={() => setMovType('deposit')}
              className={`flex-1 py-2 rounded-lg text-sm font-medium border transition-colors ${movType === 'deposit' ? 'bg-emerald-600 text-white border-emerald-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50'}`}>
              Depósito
            </button>
            <button type="button" onClick={() => setMovType('withdrawal')}
              className={`flex-1 py-2 rounded-lg text-sm font-medium border transition-colors ${movType === 'withdrawal' ? 'bg-red-600 text-white border-red-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50'}`}>
              Retiro
            </button>
          </div>
          <div className="grid grid-cols-2 gap-4">
            <FormField label="Monto (Bs.)" required>
              <Input type="number" step="0.01" min="0.01" required value={movForm.amount} onChange={e => setMovForm({ ...movForm, amount: e.target.value })} placeholder="0.00" />
            </FormField>
            <FormField label="Fecha" required>
              <Input type="date" required value={movForm.date} onChange={e => setMovForm({ ...movForm, date: e.target.value })} />
            </FormField>
          </div>
          <FormField label="Descripción">
            <Textarea value={movForm.description} onChange={e => setMovForm({ ...movForm, description: e.target.value })} placeholder="Motivo del movimiento" />
          </FormField>
          {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
          <div className="flex justify-end gap-3 pt-2">
            <button type="button" onClick={() => setMovModal(null)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
            <button type="submit" disabled={saving} className={`px-4 py-2 text-sm rounded-lg disabled:opacity-60 text-white ${movType === 'deposit' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'}`}>{saving ? 'Guardando...' : 'Registrar'}</button>
          </div>
        </form>
      </Modal>

      <ConfirmDialog open={!!deleting} onClose={() => setDeleting(null)} onConfirm={handleDelete} loading={saving}
        title="Eliminar meta de ahorro"
        message={`¿Eliminar "${deleting?.name}"? Se perderán todos sus movimientos.`} />
    </div >
  )
}
