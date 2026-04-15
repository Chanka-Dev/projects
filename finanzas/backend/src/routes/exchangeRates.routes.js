import { Router } from 'express'
import { getAll, getLatest, create, update, remove } from '../controllers/exchangeRates.controller.js'
import { verifyToken } from '../middleware/auth.js'

const router = Router()
router.use(verifyToken)

router.get('/', getAll)
router.get('/latest', getLatest)
router.post('/', create)
router.put('/:id', update)
router.delete('/:id', remove)

export default router
