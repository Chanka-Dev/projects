import { useState } from 'react'
import { useApi } from '@/hooks/useApi'
import { expensesApi } from '@/api/expenses'
import { tagsApi } from '@/api/tags'
import { formatCurrency, formatDate } from '@/utils/formatters'
import PageHeader from '@/components/common/PageHeader'
import Modal from '@/components/common/Modal'
import ConfirmDialog from '@/components/common/ConfirmDialog'
import EmptyState from '@/components/common/EmptyState'
import FormField, { Input, Select, Textarea } from '@/components/common/FormField'
import TagBadge from '@/components/common/TagBadge'

const emptyForm = { amount: '', description: '', date: new Date().toISOString().slice(0, 10), tagIds: [] }

export default function Expenses() {
  const { data: incomes, loading, refetch } = useApi(() => expensesApi.getAll())
  const { data: tags } = useApi(() => tagsApi.getAll({ type: 'expense' }))
  const [modal, setModal] = useState(false)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyForm)
  const [deleting, setDeleting] = useState(null)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState(null)

  const total = (incomes || []).reduce((s, i) => s + parseFloat(i.amount), 0)

  const openCreate = () => { setEditing(null); setForm(emptyForm); setError(null); setModal(true) }
  const openEdit = (i) => {
    setEditing(i)
    setForm({ amount: i.amount, description: i.description || '', date: i.date, tagIds: (i.tags || []).map(t => t.id) })
    setError(null); setModal(true)
  }

  const toggleTag = (id) => setForm(f => ({
    ...f, tagIds: f.tagIds.includes(id) ? f.tagIds.filter(x => x !== id) : [...f.tagIds, id]
  }))

  const handleSave = async (e) => {
    e.preventDefault(); setSaving(true); setError(null)
    try {
      if (editing) await expensesApi.update(editing.id, form)
      else await expensesApi.create(form)
      setModal(false); refetch()
    } catch (err) { setError(err.response?.data?.message || 'Error al guardar') }
    finally { setSaving(false) }
  }

  const handleDelete = async () => {
    setSaving(true)
    try { await expensesApi.remove(deleting.id); setDeleting(null); refetch() }
    finally { setSaving(false) }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        title="Gastos"
        subtitle={`${(incomes || []).length} registros · Total: ${formatCurrency(total)}`}
        action={
          <button onClick={openCreate} className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
            + Nuevo gasto
          </button>
        }
      />

      <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
        {loading && <p className="text-gray-400 text-sm p-6">Cargando...</p>}
        {!loading && !incomes?.length && <EmptyState icon="💸" message="No hay gastos registrados" />}
        {!loading && !!incomes?.length && (
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead className="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th className="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                  <th className="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Descripción</th>
                  <th className="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tags</th>
                  <th className="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Monto</th>
                  <th className="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Por</th>
                  <th className="px-4 py-3" />
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100">
                {incomes.map(item => (
                  <tr key={item.id} className="hover:bg-gray-50">
                    <td className="px-4 py-3 text-gray-500 whitespace-nowrap">{formatDate(item.date)}</td>
                    <td className="px-4 py-3 text-gray-800">{item.description || <span className="text-gray-400">—</span>}</td>
                    <td className="px-4 py-3">
                      <div className="flex flex-wrap gap-1">
                        {(item.tags || []).map(t => <TagBadge key={t.id} tag={t} />)}
                      </div>
                    </td>
                    <td className="px-4 py-3 text-right font-semibold text-red-600 whitespace-nowrap">{formatCurrency(item.amount)}</td>
                    <td className="px-4 py-3 text-gray-500 whitespace-nowrap">{item.User?.name}</td>
                    <td className="px-4 py-3">
                      <div className="flex gap-2 justify-end">
                        <button onClick={() => openEdit(item)} className="text-gray-400 hover:text-blue-600 transition-colors">✏️</button>
                        <button onClick={() => setDeleting(item)} className="text-gray-400 hover:text-red-600 transition-colors">🗑️</button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
              <tfoot className="border-t-2 border-gray-200 bg-gray-50">
                <tr>
                  <td colSpan={3} className="px-4 py-3 text-sm font-semibold text-gray-600">Total</td>
                  <td className="px-4 py-3 text-right font-bold text-red-600">{formatCurrency(total)}</td>
                  <td colSpan={2} />
                </tr>
              </tfoot>
            </table>
          </div>
        )}
      </div>

      <Modal open={modal} onClose={() => setModal(false)} title={editing ? 'Editar ingreso' : 'Nuevo gasto'}>
        <form onSubmit={handleSave} className="space-y-4">
          <div className="grid grid-cols-2 gap-4">
            <FormField label="Monto (Bs.)" required>
              <Input type="number" step="0.01" min="0.01" required value={form.amount}
                onChange={e => setForm({ ...form, amount: e.target.value })} placeholder="0.00" />
            </FormField>
            <FormField label="Fecha" required>
              <Input type="date" required value={form.date}
                onChange={e => setForm({ ...form, date: e.target.value })} />
            </FormField>
          </div>
          <FormField label="Descripción">
            <Textarea value={form.description} onChange={e => setForm({ ...form, description: e.target.value })}
              placeholder="Ej: Supermercado" />
          </FormField>
          {tags?.length > 0 && (
            <FormField label="Tags">
              <div className="flex flex-wrap gap-2 mt-1">
                {tags.map(t => (
                  <button key={t.id} type="button" onClick={() => toggleTag(t.id)}
                    className={`px-3 py-1 rounded-full text-xs font-medium border transition-all ${form.tagIds.includes(t.id) ? 'opacity-100 shadow-sm' : 'opacity-40 hover:opacity-70'}`}
                    style={{ backgroundColor: `${t.color}18`, borderColor: t.color, color: t.color }}>
                    {t.name}
                  </button>
                ))}
              </div>
            </FormField>
          )}
          {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
          <div className="flex justify-end gap-3 pt-2">
            <button type="button" onClick={() => setModal(false)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
            <button type="submit" disabled={saving} className="px-4 py-2 text-sm rounded-lg bg-red-600 hover:bg-red-700 disabled:opacity-60 text-white">{saving ? 'Guardando...' : 'Guardar'}</button>
          </div>
        </form>
      </Modal>

      <ConfirmDialog open={!!deleting} onClose={() => setDeleting(null)} onConfirm={handleDelete} loading={saving}
        title="Eliminar gasto"
        message={`¿Eliminar este gasto de ${formatCurrency(deleting?.amount)} del ${formatDate(deleting?.date)}?`} />
    </div>
  )
}
