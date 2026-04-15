import { ExchangeRate } from '../models/index.js'

// Obtener historial de tasas (últimas 30)
export const getAll = async (req, res) => {
    try {
        const items = await ExchangeRate.findAll({
            order: [['date', 'DESC']],
            limit: 30,
        })
        res.json(items)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

// Obtener la tasa más reciente
export const getLatest = async (req, res) => {
    try {
        const item = await ExchangeRate.findOne({ order: [['date', 'DESC']] })
        if (!item) return res.status(404).json({ message: 'No hay tasas registradas' })
        res.json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

// Registrar nueva tasa
export const create = async (req, res) => {
    try {
        const { officialRate, parallelRate, date, notes } = req.body
        if (!officialRate || !parallelRate) {
            return res.status(400).json({ message: 'Se requieren tasa oficial y tasa paralela' })
        }
        const item = await ExchangeRate.create({ officialRate, parallelRate, date, notes })
        res.status(201).json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

// Editar tasa existente
export const update = async (req, res) => {
    try {
        const item = await ExchangeRate.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        const { officialRate, parallelRate, date, notes } = req.body
        await item.update({ officialRate, parallelRate, date, notes })
        res.json(item)
    } catch (err) { res.status(500).json({ message: err.message }) }
}

// Eliminar tasa
export const remove = async (req, res) => {
    try {
        const item = await ExchangeRate.findByPk(req.params.id)
        if (!item) return res.status(404).json({ message: 'No encontrado' })
        await item.destroy()
        res.json({ id: item.id })
    } catch (err) { res.status(500).json({ message: err.message }) }
}
