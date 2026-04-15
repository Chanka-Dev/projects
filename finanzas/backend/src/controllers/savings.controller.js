import { Savings, SavingsMovement, User } from '../models/index.js'

export const getAll = async (req, res) => {
    try {
        const items = await Savings.findAll({ order: [['createdAt', 'DESC']] })
        res.json(items)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const getOne = async (req, res) => {
    try {
        const item = await Savings.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        res.json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const create = async (req, res) => {
    try {
        const item = await Savings.create(req.body)
        res.status(201).json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const update = async (req, res) => {
    try {
        const item = await Savings.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        await item.update(req.body)
        res.json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const remove = async (req, res) => {
    try {
        const item = await Savings.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        req.oldData = item.toJSON()
        await item.destroy()
        res.json({ id: item.id })
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const getMovements = async (req, res) => {
    try {
        const movements = await SavingsMovement.findAll({
            where: { savingsId: req.params.id },
            include: [{ model: User, attributes: ['id', 'name'] }],
            order: [['date', 'DESC']],
        })
        res.json(movements)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const addMovement = async (req, res) => {
    try {
        const savings = await Savings.findByPk(req.params.id)
        if (!savings) return res.status(404).json({ message: 'Ahorro no encontrado' })

        const movement = await SavingsMovement.create({
            ...req.body,
            savingsId: req.params.id,
            userId: req.user.id,
        })

        // Actualizar currentAmount
        const newAmount = parseFloat(savings.currentAmount) + parseFloat(req.body.amount)
        await savings.update({ currentAmount: newAmount })

        res.status(201).json(movement)
    } catch (err) { res.status(500).json({ message: err.message }) }
}
