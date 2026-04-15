import { Router } from 'express'
import { getAll, getOne, create, update, remove } from '../controllers/expenses.controller.js'
import { verifyToken } from '../middleware/auth.js'
import { auditLog } from '../middleware/audit.js'

const router = Router()
router.use(verifyToken)
router.get('/', getAll)
router.get('/:id', getOne)
router.post('/', auditLog('expenses', 'create'), create)
router.put('/:id', auditLog('expenses', 'update'), update)
router.delete('/:id', auditLog('expenses', 'delete'), remove)
export default router
