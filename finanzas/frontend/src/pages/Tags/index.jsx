import { useState } from 'react'
import { useApi } from '@/hooks/useApi'
import { tagsApi } from '@/api/tags'
import PageHeader from '@/components/common/PageHeader'
import Modal from '@/components/common/Modal'
import ConfirmDialog from '@/components/common/ConfirmDialog'
import EmptyState from '@/components/common/EmptyState'
import FormField, { Input, Select } from '@/components/common/FormField'

const TAG_TYPES = [
  { value: 'income',   label: 'Ingreso' },
  { value: 'expense',  label: 'Gasto' },
  { value: 'debt',     label: 'Deuda' },
  { value: 'savings',  label: 'Ahorro' },
  { value: 'asset',    label: 'Patrimonio' },
  { value: 'general',  label: 'General' },
]

const COLORS = ['#10b981','#3b82f6','#8b5cf6','#ef4444','#f59e0b','#f97316','#ec4899','#06b6d4','#84cc16','#64748b']

const emptyForm = { name: '', type: 'general', color: '#10b981' }

export default function Tags() {
  const { data: tags, loading, refetch } = useApi(() => tagsApi.getAll())
  const [modal, setModal] = useState(false)
  const [editing, setEditing] = useState(null)
  const [form, setForm] = useState(emptyForm)
  const [deleting, setDeleting] = useState(null)
  const [saving, setSaving] = useState(false)
  const [error, setError] = useState(null)

  const openCreate = () => { setEditing(null); setForm(emptyForm); setModal(true) }
  const openEdit = (t) => { setEditing(t); setForm({ name: t.name, type: t.type, color: t.color || '#10b981' }); setModal(true) }

  const handleSave = async (e) => {
    e.preventDefault()
    setSaving(true); setError(null)
    try {
      if (editing) await tagsApi.update(editing.id, form)
      else await tagsApi.create(form)
      setModal(false); refetch()
    } catch (err) {
      setError(err.response?.data?.message || 'Error al guardar')
    } finally { setSaving(false) }
  }

  const handleDelete = async () => {
    setSaving(true)
    try { await tagsApi.remove(deleting.id); setDeleting(null); refetch() }
    catch { setDeleting(null) }
    finally { setSaving(false) }
  }

  const grouped = (tags || []).reduce((acc, t) => {
    acc[t.type] = acc[t.type] || []
    acc[t.type].push(t)
    return acc
  }, {})

  return (
    <div className="space-y-6">
      <PageHeader
        title="Tags"
        subtitle="Etiquetas para clasificar movimientos"
        action={
          <button onClick={openCreate} className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
            + Nuevo tag
          </button>
        }
      />

      {loading && <p className="text-gray-400 text-sm">Cargando...</p>}

      {!loading && !tags?.length && (
        <div className="bg-white rounded-xl border border-gray-200">
          <EmptyState icon="🏷️" message="No hay tags creados aún" />
        </div>
      )}

      {Object.entries(grouped).map(([type, items]) => (
        <div key={type} className="bg-white rounded-xl border border-gray-200">
          <div className="px-5 py-3 border-b border-gray-100">
            <h3 className="text-sm font-semibold text-gray-600 uppercase tracking-wide">
              {TAG_TYPES.find(t => t.value === type)?.label || type}
            </h3>
          </div>
          <div className="p-4 flex flex-wrap gap-2">
            {items.map(tag => (
              <div
                key={tag.id}
                className="group flex items-center gap-2 px-3 py-1.5 rounded-full border text-sm font-medium"
                style={{ backgroundColor: `${tag.color}18`, borderColor: tag.color, color: tag.color }}
              >
                <span className="w-2 h-2 rounded-full flex-shrink-0" style={{ backgroundColor: tag.color }} />
                {tag.name}
                <div className="hidden group-hover:flex gap-1 ml-1">
                  <button onClick={() => openEdit(tag)} className="opacity-60 hover:opacity-100 text-xs">✏️</button>
                  <button onClick={() => setDeleting(tag)} className="opacity-60 hover:opacity-100 text-xs">🗑️</button>
                </div>
              </div>
            ))}
          </div>
        </div>
      ))}

      {/* Modal crear/editar */}
      <Modal open={modal} onClose={() => setModal(false)} title={editing ? 'Editar tag' : 'Nuevo tag'} size="sm">
        <form onSubmit={handleSave} className="space-y-4">
          <FormField label="Nombre" required>
            <Input required value={form.name} onChange={e => setForm({ ...form, name: e.target.value })} placeholder="Ej: Alimentación" />
          </FormField>
          <FormField label="Tipo" required>
            <Select required value={form.type} onChange={e => setForm({ ...form, type: e.target.value })}>
              {TAG_TYPES.map(t => <option key={t.value} value={t.value}>{t.label}</option>)}
            </Select>
          </FormField>
          <FormField label="Color">
            <div className="flex flex-wrap gap-2">
              {COLORS.map(c => (
                <button key={c} type="button" onClick={() => setForm({ ...form, color: c })}
                  className={`w-7 h-7 rounded-full border-2 transition-transform ${form.color === c ? 'border-gray-800 scale-110' : 'border-transparent'}`}
                  style={{ backgroundColor: c }}
                />
              ))}
            </div>
          </FormField>
          {error && <p className="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg">{error}</p>}
          <div className="flex justify-end gap-3 pt-2">
            <button type="button" onClick={() => setModal(false)} className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancelar</button>
            <button type="submit" disabled={saving} className="px-4 py-2 text-sm rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white">{saving ? 'Guardando...' : 'Guardar'}</button>
          </div>
        </form>
      </Modal>

      <ConfirmDialog
        open={!!deleting}
        onClose={() => setDeleting(null)}
        onConfirm={handleDelete}
        loading={saving}
        title="Eliminar tag"
        message={`¿Eliminar el tag "${deleting?.name}"? Los movimientos que lo usen perderán esta etiqueta.`}
      />
    </div>
  )
}
