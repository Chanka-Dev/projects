import { Router } from 'express'
import { getAll, getOne, create, update, remove } from '../controllers/assets.controller.js'
import { verifyToken } from '../middleware/auth.js'
import { auditLog } from '../middleware/audit.js'

const router = Router()
router.use(verifyToken)
router.get('/', getAll)
router.get('/:id', getOne)
router.post('/', auditLog('assets', 'create'), create)
router.put('/:id', auditLog('assets', 'update'), update)
router.delete('/:id', auditLog('assets', 'delete'), remove)
export default router
