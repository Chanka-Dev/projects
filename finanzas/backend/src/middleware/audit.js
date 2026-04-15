import AuditLog from '../models/AuditLog.js'

/**
 * Factory que retorna un middleware que registra la acción
 * @param {string} tableName - Nombre de la tabla afectada
 * @param {string} action - 'create' | 'update' | 'delete'
 */
export const auditLog = (tableName, action) => async (req, res, next) => {
    const originalJson = res.json.bind(res)

    res.json = async (body) => {
        try {
            if (res.statusCode >= 200 && res.statusCode < 300) {
                const recordId = body?.id || req.params?.id || null
                await AuditLog.create({
                    userId: req.user?.id || null,
                    action,
                    tableName,
                    recordId,
                    oldData: req.oldData || null,
                    newData: action !== 'delete' ? body : null,
                })
            }
        } catch (err) {
            console.error('Error en audit log:', err.message)
        }
        return originalJson(body)
    }
    next()
}
