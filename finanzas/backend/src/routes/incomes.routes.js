import { Router } from 'express'
import { getAll, getOne, create, update, remove } from '../controllers/incomes.controller.js'
import { verifyToken } from '../middleware/auth.js'
import { auditLog } from '../middleware/audit.js'

const router = Router()
router.use(verifyToken)
router.get('/', getAll)
router.get('/:id', getOne)
router.post('/', auditLog('incomes', 'create'), create)
router.put('/:id', auditLog('incomes', 'update'), update)
router.delete('/:id', auditLog('incomes', 'delete'), remove)
export default router
