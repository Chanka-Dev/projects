import { Router } from 'express'
import { getAll, create, update, remove } from '../controllers/tags.controller.js'
import { verifyToken } from '../middleware/auth.js'

const router = Router()
router.use(verifyToken)
router.get('/', getAll)
router.post('/', create)
router.put('/:id', update)
router.delete('/:id', remove)
export default router
