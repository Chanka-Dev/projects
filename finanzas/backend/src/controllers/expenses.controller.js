import { Expense, Tag, User } from '../models/index.js'
import { Op } from 'sequelize'

const include = [
    { model: Tag, as: 'tags', through: { attributes: [] } },
    { model: User, attributes: ['id', 'name'] },
]

export const getAll = async (req, res) => {
    try {
        const { from, to } = req.query
        const where = {}
        if (from && to) where.date = { [Op.between]: [from, to] }
        const expenses = await Expense.findAll({ where, include, order: [['date', 'DESC']] })
        res.json(expenses)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const getOne = async (req, res) => {
    try {
        const item = await Expense.findByPk(req.params.id, { include })
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        res.json(item)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const create = async (req, res) => {
    try {
        const { tagIds = [], ...data } = req.body
        const expense = await Expense.create({ ...data, userId: req.user.id })
        if (tagIds.length > 0) await expense.setTags(tagIds)
        const result = await Expense.findByPk(expense.id, { include })
        res.status(201).json(result)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const update = async (req, res) => {
    try {
        const item = await Expense.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        const { tagIds, ...data } = req.body
        await item.update(data)
        if (tagIds !== undefined) await item.setTags(tagIds)
        const result = await Expense.findByPk(item.id, { include })
        res.json(result)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const remove = async (req, res) => {
    try {
        const item = await Expense.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        await item.destroy()
        res.json({ id: item.id })
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}
