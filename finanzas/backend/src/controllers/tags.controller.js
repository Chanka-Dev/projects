import { Tag } from '../models/index.js'

export const getAll = async (req, res) => {
    try {
        const where = {}
        if (req.query.type) where.type = req.query.type
        const tags = await Tag.findAll({ where, order: [['name', 'ASC']] })
        res.json(tags)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const create = async (req, res) => {
    try {
        const tag = await Tag.create(req.body)
        res.status(201).json(tag)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const update = async (req, res) => {
    try {
        const tag = await Tag.findByPk(req.params.id)
        if (!tag) return res.status(404).json({ message: 'No encontrado' })
        await tag.update(req.body)
        res.json(tag)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

export const remove = async (req, res) => {
    try {
        const tag = await Tag.findByPk(req.params.id)
        if (!tag) return res.status(404).json({ message: 'No encontrado' })
        await tag.destroy()
        res.json({ id: tag.id })
    } catch (err) { res.status(500).json({ message: err.message }) }
}
