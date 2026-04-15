import 'dotenv/config'
import express from 'express'
import cors from 'cors'

// Routes
import authRoutes from './routes/auth.routes.js'
import incomesRoutes from './routes/incomes.routes.js'
import expensesRoutes from './routes/expenses.routes.js'
import savingsRoutes from './routes/savings.routes.js'
import debtsRoutes from './routes/debts.routes.js'
import assetsRoutes from './routes/assets.routes.js'
import tagsRoutes from './routes/tags.routes.js'
import auditRoutes from './routes/audit.routes.js'
import dashboardRoutes from './routes/dashboard.routes.js'
import exchangeRatesRoutes from './routes/exchangeRates.routes.js'

// DB
import { sequelize } from './config/database.js'

const app = express()
const PORT = process.env.PORT || 3000

// Middlewares
app.use(cors({
    origin: process.env.FRONTEND_URL || 'http://localhost:5173',
    credentials: true,
}))
app.use(express.json())

// Rutas
app.use('/api/auth', authRoutes)
app.use('/api/incomes', incomesRoutes)
app.use('/api/expenses', expensesRoutes)
app.use('/api/savings', savingsRoutes)
app.use('/api/debts', debtsRoutes)
app.use('/api/assets', assetsRoutes)
app.use('/api/tags', tagsRoutes)
app.use('/api/audit-logs', auditRoutes)
app.use('/api/dashboard', dashboardRoutes)
app.use('/api/exchange-rates', exchangeRatesRoutes)

// Health check
app.get('/api/health', (req, res) => res.json({ status: 'ok' }))

// Arrancar servidor y sincronizar DB
sequelize.authenticate()
    .then(() => {
        console.log('✅ Conexión a MariaDB establecida')
        return sequelize.sync({ alter: false })
    })
    .then(() => {
        app.listen(PORT, () => {
            console.log(`🚀 Servidor corriendo en http://localhost:${PORT}`)
        })
    })
    .catch((err) => {
        console.error('❌ Error conectando a la base de datos:', err.message)
        process.exit(1)
    })
