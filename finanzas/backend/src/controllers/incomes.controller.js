import { Income, Tag, User } from '../models/index.js'
import { Op } from 'sequelize'

const include = [
    { model: Tag, as: 'tags', through: { attributes: [] } },
    { model: User, attributes: ['id', 'name'] },
]

export const getAll = async (req, res) => {
    try {
        const { from, to, tagId } = req.query
        const where = {}
        if (from && to) where.date = { [Op.between]: [from, to] }

        const incomes = await Income.findAll({ where, include, order: [['date', 'DESC']] })
        res.json(incomes)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const getOne = async (req, res) => {
    try {
        const income = await Income.findByPk(req.params.id, { include })
        if (!income) return res.status(404).json({ message: 'No encontrado' })
        res.json(income)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const create = async (req, res) => {
    try {
        const { tagIds = [], ...data } = req.body
        const income = await Income.create({ ...data, userId: req.user.id })
        if (tagIds.length > 0) await income.setTags(tagIds)
        const result = await Income.findByPk(income.id, { include })
        res.status(201).json(result)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const update = async (req, res) => {
    try {
        const income = await Income.findByPk(req.params.id)
        if (!income) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = income.toJSON()
        const { tagIds, ...data } = req.body
        await income.update(data)
        if (tagIds !== undefined) await income.setTags(tagIds)
        const result = await Income.findByPk(income.id, { include })
        res.json(result)
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}

export const remove = async (req, res) => {
    try {
        const income = await Income.findByPk(req.params.id)
        if (!income) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = income.toJSON()
        await income.destroy()
        res.json({ id: income.id })
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}
