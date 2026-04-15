import { Router } from 'express'
import { login, me, register } from '../controllers/auth.controller.js'
import { verifyToken } from '../middleware/auth.js'

const router = Router()
router.post('/login', login)
router.post('/register', register)  // Solo usar la primera vez para crear usuarios
router.get('/me', verifyToken, me)
export default router
