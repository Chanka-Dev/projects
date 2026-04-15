import { Router } from 'express'
import { getAll, getOne, create, update, remove, getPayments, addPayment } from '../controllers/debts.controller.js'
import { verifyToken } from '../middleware/auth.js'
import { auditLog } from '../middleware/audit.js'

const router = Router()
router.use(verifyToken)
router.get('/', getAll)
router.get('/:id', getOne)
router.post('/', auditLog('debts', 'create'), create)
router.put('/:id', auditLog('debts', 'update'), update)
router.delete('/:id', auditLog('debts', 'delete'), remove)
router.get('/:id/payments', getPayments)
router.post('/:id/payments', addPayment)
export default router
