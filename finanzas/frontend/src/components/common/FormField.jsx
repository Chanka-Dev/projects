/**
 * FormField — wrapper para inputs, selects y textareas con label y error
 */
export default function FormField({ label, error, required, children }) {
    return (
        <div className="space-y-1">
            {label && (
                <label className="block text-sm font-medium text-gray-700">
                    {label} {required && <span className="text-red-500">*</span>}
                </label>
            )}
            {children}
            {error && <p className="text-xs text-red-600">{error}</p>}
        </div>
    )
}

const base = 'w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent disabled:bg-gray-50 disabled:text-gray-500'

export function Input({ className = '', ...props }) {
    return <input className={`${base} ${className}`} {...props} />
}

export function Select({ className = '', children, ...props }) {
    return (
        <select className={`${base} ${className}`} {...props}>
            {children}
        </select>
    )
}

export function Textarea({ className = '', ...props }) {
    return <textarea className={`${base} min-h-[80px] resize-y ${className}`} {...props} />
}
