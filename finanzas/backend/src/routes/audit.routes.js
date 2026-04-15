import { Router } from 'express'
import { getAll, getOne } from '../controllers/audit.controller.js'
import { verifyToken } from '../middleware/auth.js'

const router = Router()
router.use(verifyToken)
router.get('/', getAll)
router.get('/:id', getOne)
export default router
