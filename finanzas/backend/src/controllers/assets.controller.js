import { Asset, User, Debt } from '../models/index.js'

const include = [
    { model: User, as: 'owner', attributes: ['id', 'name'] },
    { model: Debt, as: 'associatedDebt', attributes: ['id', 'totalAmount', 'paidAmount', 'status'] },
]

export const getAll = async (req, res) => {
    try {
        const items = await Asset.findAll({ include, order: [['createdAt', 'DESC']] })
        res.json(items)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const getOne = async (req, res) => {
    try {
        const item = await Asset.findByPk(req.params.id, { include })
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        res.json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const create = async (req, res) => {
    try {
        const { ownerUserId, debtId, ...rest } = req.body
        const item = await Asset.create({
            ...rest,
            ownerUserId: ownerUserId || null,
            debtId: debtId || null,
        })
        const result = await Asset.findByPk(item.id, { include })
        res.status(201).json(result)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const update = async (req, res) => {
    try {
        const item = await Asset.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        const { ownerUserId, debtId, ...rest } = req.body
        await item.update({
            ...rest,
            ownerUserId: ownerUserId || null,
            debtId: debtId || null,
        })
        const result = await Asset.findByPk(item.id, { include })
        res.json(result)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const remove = async (req, res) => {
    try {
        const item = await Asset.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        await item.destroy()
        res.json({ id: item.id })
    } catch (err) { res.status(500).json({ message: err.message }) }
}
