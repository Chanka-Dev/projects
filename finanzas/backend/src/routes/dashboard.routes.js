import { Router } from 'express'
import { getSummary } from '../controllers/dashboard.controller.js'
import { verifyToken } from '../middleware/auth.js'

const router = Router()
router.use(verifyToken)
router.get('/summary', getSummary)
export default router
