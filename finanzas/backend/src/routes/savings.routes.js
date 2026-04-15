import { Router } from 'express'
import { getAll, getOne, create, update, remove, getMovements, addMovement } from '../controllers/savings.controller.js'
import { verifyToken } from '../middleware/auth.js'
import { auditLog } from '../middleware/audit.js'

const router = Router()
router.use(verifyToken)
router.get('/', getAll)
router.get('/:id', getOne)
router.post('/', auditLog('savings', 'create'), create)
router.put('/:id', auditLog('savings', 'update'), update)
router.delete('/:id', auditLog('savings', 'delete'), remove)
router.get('/:id/movements', getMovements)
router.post('/:id/movements', addMovement)
export default router
