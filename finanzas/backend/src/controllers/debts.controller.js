import { Debt, DebtPayment, Tag, User } from '../models/index.js'

const include = [
    { model: Tag, as: 'tags', through: { attributes: [] } },
    { model: User, attributes: ['id', 'name'] },
]

export const getAll = async (req, res) => {
    try {
        const where = {}
        if (req.query.type) where.type = req.query.type
        if (req.query.status) where.status = req.query.status
        const items = await Debt.findAll({ where, include, order: [['createdAt', 'DESC']] })
        res.json(items)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const getOne = async (req, res) => {
    try {
        const item = await Debt.findByPk(req.params.id, { include })
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        res.json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const create = async (req, res) => {
    try {
        const { tagIds = [], ...data } = req.body
        const debt = await Debt.create({ ...data, userId: req.user.id })
        if (tagIds.length > 0) await debt.setTags(tagIds)
        const result = await Debt.findByPk(debt.id, { include })
        res.status(201).json(result)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const update = async (req, res) => {
    try {
        const item = await Debt.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        const { tagIds, ...data } = req.body
        await item.update(data)
        if (tagIds !== undefined) await item.setTags(tagIds)
        const result = await Debt.findByPk(item.id, { include })
        res.json(result)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const remove = async (req, res) => {
    try {
        const item = await Debt.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        await item.destroy()
        res.json({ id: item.id })
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const getPayments = async (req, res) => {
    try {
        const payments = await DebtPayment.findAll({
            where: { debtId: req.params.id },
            include: [{ model: User, attributes: ['id', 'name'] }],
            order: [['date', 'DESC']],
        })
        res.json(payments)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const addPayment = async (req, res) => {
    try {
        const debt = await Debt.findByPk(req.params.id)
        if (!debt) return res.status(404).json({ message: 'Deuda no encontrada' })

        const payment = await DebtPayment.create({
            ...req.body, debtId: req.params.id, userId: req.user.id,
        })

        const newPaid = parseFloat(debt.paidAmount) + parseFloat(req.body.amount)
        const status = newPaid >= parseFloat(debt.totalAmount) ? 'paid' : 'active'
        await debt.update({ paidAmount: newPaid, status })

        res.status(201).json(payment)
    } catch (err) { res.status(500).json({ message: err.message }) }
}
