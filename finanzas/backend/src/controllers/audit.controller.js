import { AuditLog, User } from '../models/index.js'

export const getAll = async (req, res) => {
    try {
        const { tableName, action, limit = 100 } = req.query
        const where = {}
        if (tableName) where.tableName = tableName
        if (action) where.action = action

        const logs = await AuditLog.findAll({
            where,
            include: [{ model: User, attributes: ['id', 'name'] }],
            order: [['createdAt', 'DESC']],
            limit: parseInt(limit),
        })
        res.json(logs)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const getOne = async (req, res) => {
    try {
        const log = await AuditLog.findByPk(req.params.id, {
            include: [{ model: User, attributes: ['id', 'name'] }],
        })
        if (!log) return res.status(404).json({ message: 'No encontrado' })
        res.json(log)
    } catch (err) { res.status(500).json({ message: err.message }) }
}
