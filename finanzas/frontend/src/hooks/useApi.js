import { useState, useEffect, useCallback } from 'react'

/**
 * useApi — hook genérico para llamadas a la API
 * @param {Function} apiFn — función que retorna una Promise (axios call)
 * @param {*} deps — dependencias para re-ejecutar
 */
export function useApi(apiFn, deps = []) {
    const [data, setData] = useState(null)
    const [loading, setLoading] = useState(true)
    const [error, setError] = useState(null)

    const fetch = useCallback(async () => {
        setLoading(true)
        setError(null)
        try {
            const { data: result } = await apiFn()
            setData(result)
        } catch (err) {
            setError(err.response?.data?.message || 'Error al cargar los datos')
        } finally {
            setLoading(false)
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, deps)

    useEffect(() => { fetch() }, [fetch])

    return { data, loading, error, refetch: fetch }
}
