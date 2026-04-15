import { useState, useEffect } from 'react'
import { exchangeRatesApi } from '@/api/exchangeRates'

/**
 * Hook para obtener la última tasa de cambio registrada.
 * Retorna { rate, loading } donde rate = { officialRate, parallelRate, date, ... }
 */
export function useExchangeRate() {
    const [rate, setRate] = useState(null)
    const [loading, setLoading] = useState(true)

    useEffect(() => {
        exchangeRatesApi.getLatest()
            .then(({ data }) => setRate(data))
            .catch(() => setRate(null))
            .finally(() => setLoading(false))
    }, [])

    return { rate, loading }
}
