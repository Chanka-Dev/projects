import Modal from './Modal'

export default function ConfirmDialog({ open, onClose, onConfirm, title, message, confirmLabel = 'Eliminar', loading = false }) {
    return (
        <Modal open={open} onClose={onClose} title={title} size="sm">
            <p className="text-sm text-gray-600 mb-6">{message}</p>
            <div className="flex justify-end gap-3">
                <button
                    onClick={onClose}
                    className="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors"
                >
                    Cancelar
                </button>
                <button
                    onClick={onConfirm}
                    disabled={loading}
                    className="px-4 py-2 text-sm rounded-lg bg-red-600 hover:bg-red-700 disabled:opacity-60 text-white transition-colors"
                >
                    {loading ? 'Procesando...' : confirmLabel}
                </button>
            </div>
        </Modal>
    )
}
