import { useState, useCallback } from 'react'
import { debtsApi } from '@/api/debts'
import { tagsApi } from '@/api/tags'
import { useApi } from '@/hooks/useApi'
import { formatDate, calcPercent, formatCurrency, formatAmount, equivalentLabel } from '@/utils/formatters'
import { useExchangeRate } from '@/hooks/useExchangeRate'
import PageHeader from '@/components/common/PageHeader'
import Modal from '@/components/common/Modal'
import ConfirmDialog from '@/components/common/ConfirmDialog'
import EmptyState from '@/components/common/EmptyState'
import StatusBadge from '@/components/common/StatusBadge'
import TagBadge from '@/components/common/TagBadge'
import FormField, { Input, Select, Textarea } from '@/components/common/FormField'

const USERS = [{ value: '', label: '— Cualquiera' }, { value: '1', label: 'Pedro' }, { value: '2', label: 'Marce' }]
const TYPES = [{ value: 'payable', label: 'Por pagar (debemos)' }, { value: 'receivable', label: 'Por cobrar (nos deben)' }]
const STATUSES = [{ value: 'active', label: 'Activa' }, { value: 'paid', label: 'Pagada' }, { value: 'cancelled', label: 'Cancelada' }]

const emptyDebt = { description: '', type: 'payable', counterpart: '', totalAmount: '', dueDate: '', notes: '', userId: '', tagIds: [], currency: 'BOB' }
const emptyPayment = { amount: '', payment_date: new Date().toISOString().slice(0, 10), notes: '' }

export default function Debts() {
  const [activeTab, setActiveTab] = useState('payable')
  const fetchDebts = useCallback(() => debtsApi.getAll({ type: activeTab }), [activeTab])
  const { data: debts, loading, refetch } = useApi(fetchDebts, [activeTab])
  const { data: allTags } = useApi(() => tagsApi.getAll())
  const { rate } = useExchangeRate()

  const [debtModal, setDebtModal] = useState(false)
  const [paymentModal, setPaymentModal] = useState(null)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyDebt)
  const [payForm, setPayForm] = useState(emptyPayment)
  const [deleting, setDeleting] = useState(null)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState(null)

  const debtTags = (allTags || []).filter(t => t.type === 'debt')
  const totalActive = (debts || []).filter(d => d.status === 'active').reduce((s, d) => s + parseFloat(d.totalAmount || d.total_amount || 0), 0)

  const openCreate = () => { setEditing(null); setForm({ ...emptyDebt, type: activeTab }); setError(null); setDebtModal(true) }
  const openEdit = (d) => {
    setEditing(d)
    setForm({
      description: d.description, type: d.type, counterpart: d.counterpart || '',
      totalAmount: d.totalAmount || '', dueDate: d.dueDate || '',
      notes: d.notes || '', userId: d.userId || '', tagIds: (d.Tags || []).map(t => t.id),
      currency: d.currency || 'BOB'
    })
    setError(null); setDebtModal(true)
  }
  const openPayment = (d) => { setPaymentModal(d); setPayForm(emptyPayment); setError(null) }

  const handleSaveDebt = async (e) => {
    e.preventDefault(); setSaving(true); setError(null)
    try {
      if (editing) await debtsApi.update(editing.id, form)
      else await debtsApi.create(form)
      setDebtModal(false); refetch()
    } catch (err) { setError(err.response?.data?.message || 'Error al guardar') }
    finally { setSaving(false) }
  }

  const handleAddPayment = async (e) => {
    e.preventDefault(); setSaving(true); setError(null)
    try {
      await debtsApi.addPayment(paymentModal.id, payForm)
      setPaymentModal(null); refetch()
    } catch (err) { setError(err.response?.data?.message || 'Error al guardar') }
    finally { setSaving(false) }
  }

  const handleDelete = async () => {
    setSaving(true)
    try { await debtsApi.remove(deleting.id); setDeleting(null); refetch() }
    finally { setSaving(false) }
  }

  const toggleTag = (id) => setForm(f => ({ ...f, tagIds: f.tagIds.includes(id) ? f.tagIds.filter(x => x !== id) : [...f.tagIds, id] }))

  const tabLabel = activeTab === 'payable' ? 'Por Pagar' : 'Por Cobrar'
  const tabColor = activeTab === 'payable' ? 'text-red-700' : 'text-emerald-700'
  const btnColor = activeTab === 'payable' ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700'

  return (
    <div className="space-y-6">
      <PageHeader
        title="Deudas"
        subtitle={`${tabLabel}: ${formatCurrency(totalActive)} activos`}
        action={
          <button onClick={openCreate} className={`px-4 py-2 ${btnColor} text-white text-sm font-medium rounded-lg transition-colors`}>
            + Nueva deuda
          </button>
        }
      />

      {/* Tabs */}
      <div className="flex gap-1 border-b border-gray-200">
        {[{ key: 'payable', label: '⬆️ Por Pagar' }, { key: 'receivable', label: '⬇️ Por Cobrar' }].map(tab => (
          <button key={tab.key} onClick={() => setActiveTab(tab.key)}
            className={`px-4 py-2.5 text-sm font-medium border-b-2 transition-colors ${activeTab === tab.key ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'}`}>
            {tab.label}
          </button>
        ))}
      </div>

      {loading && <p className="text-gray-400 text-sm">Cargando...</p>}
      {!loading && !debts?.length && (
        <div className="bg-white rounded-xl border border-gray-200">
          <EmptyState icon="💳" message={`No hay deudas ${activeTab === 'payable' ? 'por pagar' : 'por cobrar'}`} />
        </div>
      )}

      <div className="space-y-3">
        {(debts || []).map(d => {
          const total = parseFloat(d.totalAmount || 0)
          const paid = parseFloat(d.paidAmount || 0)
          const currency = d.currency || 'BOB'
          const equiv = equivalentLabel(total, currency, rate)
          const pct = total > 0 ? calcPercent(paid, total) : null
          const isPayable = d.type === 'payable'
          return (
            <div key={d.id} className="bg-white rounded-xl border border-gray-200 p-4">
              <div className="flex items-start justify-between gap-4">
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2 flex-wrap mb-1">
                    <span className="font-semibold text-gray-900">{d.description}</span>
                    <StatusBadge status={d.status} />
                    {(d.Tags || []).map(t => <TagBadge key={t.id} tag={t} />)}
                  </div>
                  {d.counterpart && <p className="text-xs text-gray-400">{isPayable ? 'Acreedor' : 'Deudor'}: <span className="font-medium text-gray-600">{d.counterpart}</span></p>}
                  {d.dueDate && <p className="text-xs text-gray-400 mt-0.5">Vencimiento: {formatDate(d.dueDate)}</p>}
                  {pct !== null && (
                    <div className="mt-2">
                      <div className="flex justify-between text-xs text-gray-400 mb-1">
                        <span>Pagado: {formatCurrency(paid)}</span>
                        <span>{pct}%</span>
                      </div>
                      <div className="w-full bg-gray-100 rounded-full h-1.5">
                        <div className={`h-1.5 rounded-full transition-all ${isPayable ? 'bg-red-400' : 'bg-emerald-500'}`} style={{ width: `${pct}%` }} />
                      </div>
                    </div>
                  )}
                </div>
                <div className="flex flex-col items-end gap-2">
                  <span className={`font-bold text-lg ${isPayable ? 'text-red-600' : 'text-emerald-600'}`}>{formatAmount(total, currency)}</span>
                  {equiv && <span className="text-xs text-gray-400">{equiv}</span>}
                  <div className="flex gap-2">
                    {d.status === 'active' && (
                      <button onClick={() => openPayment(d)} className="text-xs px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">+ Pago</button>
                    )}
                    <button onClick={() => openEdit(d)} className="text-gray-400 hover:text-blue-600 transition-colors text-sm">✏️</button>
                    <button onClick={() => setDeleting(d)} className="text-gray-400 hover:text-red-600 transition-colors text-sm">🗑️</button>
                  </div>
                </div>
              </div>
            </div>
          )
        })}
      </div>

      {/* Modal deuda */}
      <Modal open={debtModal} onClose={() => setDebtModal(false)} title={editing ? 'Editar deuda' : 'Nueva deuda'} size="md">
        <form onSubmit={handleSaveDebt} className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            <FormField label="Descripción" required className="col-span-2">
              <Input required value={form.description} onChange={e => setForm({ ...form, description: e.target.value })} placeholder="Ej: Préstamo banco" />
            </FormField>
            <FormField label="Tipo" required>
              <Select value={form.type} onChange={e => setForm({ ...form, type: e.target.value })}>
                {TYPES.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
              </Select>
            </FormField>
            <FormField label="Contraparte">
              <Input value={form.counterpart} onChange={e => setForm({ ...form, counterpart: e.target.value })} placeholder="Banco / persona" />
            </FormField>
            <FormField label="Monto total (Bs.)" required>
              <Input type="number" step="0.01" min="0.01" required value={form.totalAmount} onChange={e => setForm({ ...form, totalAmount: e.target.value })} placeholder="0.00" />
            </FormField>
            <FormField label="Fecha vencimiento">
              <Input type="date" value={form.dueDate} onChange={e => setForm({ ...form, dueDate: e.target.value })} />
            </FormField>
            <FormField label="Responsable">
              <Select value={form.userId} onChange={e => setForm({ ...form, userId: e.target.value })}>
                {USERS.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
              </Select>
            </FormField>
            <FormField label="Moneda">
              <Select value={form.currency} onChange={e => setForm({ ...form, currency: e.target.value })}>
                <option value="BOB">🇧🇴 BOB — Bolivianos</option>
                <option value="USD">🇺🇸 USD — Dólares</option>
              </Select>
            </FormField>
            {editing && (
              <FormField label="Estado">
                <Select value={form.status || 'active'} onChange={e => setForm({ ...form, status: e.target.value })}>
                  {STATUSES.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
                </Select>
              </FormField>
            )}
          </div>
          {debtTags.length > 0 && (
            <FormField label="Etiquetas">
              <div className="flex flex-wrap gap-2 mt-1">
                {debtTags.map(t => (
                  <button key={t.id} type="button" onClick={() => toggleTag(t.id)}
                    className={`px-2.5 py-1 rounded-full text-xs font-medium border transition-colors ${form.tagIds.includes(t.id) ? 'text-white border-transparent' : 'bg-white border-gray-200 text-gray-600 hover:border-gray-400'}`}
                    style={form.tagIds.includes(t.id) ? { backgroundColor: t.color, borderColor: t.color } : {}}>
                    {t.name}
                  </button>
                ))}
              </div>
            </FormField>
          )}
          <FormField label="Notas">
            <Textarea value={form.notes} onChange={e => setForm({ ...form, notes: e.target.value })} placeholder="Notas adicionales" />
          </FormField>
          {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
          <div className="flex justify-end gap-3 pt-2">
            <button type="button" onClick={() => setDebtModal(false)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
            <button type="submit" disabled={saving} className={`px-4 py-2 text-sm rounded-lg disabled:opacity-60 text-white ${form.type === 'payable' ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700'}`}>{saving ? 'Guardando...' : 'Guardar'}</button>
          </div>
        </form>
      </Modal>

      {/* Modal pago */}
      <Modal open={!!paymentModal} onClose={() => setPaymentModal(null)} title={`Registrar pago — ${paymentModal?.description}`} size="sm">
        <form onSubmit={handleAddPayment} className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            <FormField label="Monto (Bs.)" required>
              <Input type="number" step="0.01" min="0.01" required value={payForm.amount} onChange={e => setPayForm({ ...payForm, amount: e.target.value })} placeholder="0.00" />
            </FormField>
            <FormField label="Fecha" required>
              <Input type="date" required value={payForm.payment_date} onChange={e => setPayForm({ ...payForm, payment_date: e.target.value })} />
            </FormField>
          </div>
          <FormField label="Notas">
            <Textarea value={payForm.notes} onChange={e => setPayForm({ ...payForm, notes: e.target.value })} placeholder="Descripción del pago" />
          </FormField>
          {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
          <div className="flex justify-end gap-3 pt-2">
            <button type="button" onClick={() => setPaymentModal(null)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
            <button type="submit" disabled={saving} className="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white">{saving ? 'Guardando...' : 'Registrar pago'}</button>
          </div>
        </form>
      </Modal>

      <ConfirmDialog open={!!deleting} onClose={() => setDeleting(null)} onConfirm={handleDelete} loading={saving}
        title="Eliminar deuda"
        message={`¿Eliminar "${deleting?.description}"? Se perderán todos sus pagos.`} />
    </div>
  )
}
