import bcrypt from 'bcryptjs'
import jwt from 'jsonwebtoken'
import { User } from '../models/index.js'

export const login = async (req, res) => {
    try {
        const { email, password } = req.body
        if (!email || !password) return res.status(400).json({ message: 'Email y contraseña requeridos' })

        const user = await User.findOne({ where: { email } })
        if (!user) return res.status(401).json({ message: 'Credenciales incorrectas' })

        const valid = await bcrypt.compare(password, user.password)
        if (!valid) return res.status(401).json({ message: 'Credenciales incorrectas' })

        const token = jwt.sign(
            { id: user.id, email: user.email, name: user.name, role: user.role },
            process.env.JWT_SECRET,
            { expiresIn: process.env.JWT_EXPIRES_IN || '7d' }
        )

        res.json({
            token,
            user: { id: user.id, name: user.name, email: user.email, role: user.role },
        })
    } catch (err) {
        res.status(500).json({ message: 'Error interno', error: err.message })
    }
}

export const me = async (req, res) => {
    try {
        const user = await User.findByPk(req.user.id, {
            attributes: ['id', 'name', 'email', 'role', 'createdAt'],
        })
        if (!user) return res.status(404).json({ message: 'Usuario no encontrado' })
        res.json(user)
    } catch (err) {
        res.status(500).json({ message: 'Error interno', error: err.message })
    }
}

export const register = async (req, res) => {
    try {
        const { name, email, password } = req.body
        const exists = await User.findOne({ where: { email } })
        if (exists) return res.status(409).json({ message: 'El email ya está registrado' })

        const hash = await bcrypt.hash(password, 12)
        const user = await User.create({ name, email, password: hash, role: 'admin' })
        res.status(201).json({ id: user.id, name: user.name, email: user.email })
    } catch (err) {
        res.status(500).json({ message: 'Error interno', error: err.message })
    }
}
