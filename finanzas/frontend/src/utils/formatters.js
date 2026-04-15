/**
 * Formatea un número como moneda boliviana (Bs.)
 */
export function formatCurrency(amount) {
    if (amount === null || amount === undefined) return 'Bs. 0,00'
    return `Bs. ${Number(amount).toLocaleString('es-BO', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`
}

/**
 * Formatea una fecha ISO a formato legible en español
 */
export function formatDate(dateStr) {
    if (!dateStr) return '-'
    const date = new Date(dateStr)
    return date.toLocaleDateString('es-BO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    })
}

/**
 * Formatea fecha y hora
 */
export function formatDateTime(dateStr) {
    if (!dateStr) return '-'
    const date = new Date(dateStr)
    return date.toLocaleString('es-BO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}

/**
 * Retorna clase de color según tipo de transacción
 */
export function amountColor(amount) {
    return Number(amount) >= 0 ? 'text-emerald-600' : 'text-red-600'
}

/**
 * Calcula porcentaje completado (para ahorros/deudas)
 */
export function calcPercent(current, total) {
    if (!total || total === 0) return 0
    return Math.min(100, Math.round((current / total) * 100))
}

// ─── Utilidades multi-moneda (USD / BOB) ─────────────────────────────────────

/**
 * Formatea un monto en USD
 */
export function formatUSD(amount) {
    if (amount === null || amount === undefined) return '$ 0.00'
    return `$ ${Number(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })}`
}

/**
 * Formatea según la moneda del registro: si es USD muestra $, si es BOB muestra Bs.
 */
export function formatAmount(amount, currency = 'BOB') {
    return currency === 'USD' ? formatUSD(amount) : formatCurrency(amount)
}

/**
 * Convierte un monto a BOB usando la tasa paralela
 * @param {number} amount
 * @param {'BOB'|'USD'} currency  moneda original del monto
 * @param {object} rate           { officialRate, parallelRate }
 * @returns {number}
 */
export function toBOB(amount, currency, rate) {
    if (!rate || currency === 'BOB') return Number(amount)
    return Number(amount) * Number(rate.parallelRate)
}

/**
 * Convierte un monto a USD usando la tasa paralela
 */
export function toUSD(amount, currency, rate) {
    if (!rate || currency === 'USD') return Number(amount)
    if (!rate.parallelRate || Number(rate.parallelRate) === 0) return 0
    return Number(amount) / Number(rate.parallelRate)
}

/**
 * Muestra el equivalente en la otra moneda entre paréntesis
 * Ej: "Bs. 94.000" → "≈ $ 10.000" o viceversa
 */
export function equivalentLabel(amount, currency, rate) {
    if (!rate) return null
    if (currency === 'USD') {
        const inBOB = toBOB(amount, 'USD', rate)
        return `≈ ${formatCurrency(inBOB)}`
    } else {
        const inUSD = toUSD(amount, 'BOB', rate)
        return `≈ ${formatUSD(inUSD)}`
    }
}

