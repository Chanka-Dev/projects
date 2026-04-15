import { Income, Expense, Savings, Debt, Asset, ExchangeRate } from '../models/index.js'
import { Op, fn, col, literal } from 'sequelize'

export const getSummary = async (req, res) => {
    try {
        const now = new Date()
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1)
        const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0)
        const monthRange = { [Op.between]: [firstDay, lastDay] }

        // Tipo de cambio vigente
        const latestRate = await ExchangeRate.findOne({ order: [['date', 'DESC']] })
        const parallelRate = parseFloat(latestRate?.parallelRate || 9.40)
        const officialRate = parseFloat(latestRate?.officialRate || 6.96)

        // Agrupador por moneda → { bob, usd, totalBOB }
        const byCurrency = (rows) => {
            const r = { bob: 0, usd: 0 }
            rows.forEach(row => {
                const v = parseFloat(row.total || 0)
                if (row.currency === 'USD') r.usd += v
                else r.bob += v
            })
            r.totalBOB = r.bob + r.usd * parallelRate
            return r
        }

        // Ingresos del mes (sólo BOB por ahora)
        const [incomeResult] = await Income.findAll({
            attributes: [[fn('COALESCE', fn('SUM', col('amount')), 0), 'total']],
            where: { date: monthRange },
            raw: true,
        })

        // Gastos del mes (sólo BOB por ahora)
        const [expenseResult] = await Expense.findAll({
            attributes: [[fn('COALESCE', fn('SUM', col('amount')), 0), 'total']],
            where: { date: monthRange },
            raw: true,
        })

        // Ahorros por moneda
        const savingsRows = await Savings.findAll({
            attributes: ['currency', [fn('COALESCE', fn('SUM', col('current_amount')), 0), 'total']],
            group: ['currency'],
            raw: true,
        })

        // Deudas por pagar activas - por moneda
        const payableRows = await Debt.findAll({
            attributes: ['currency', [literal('COALESCE(SUM(total_amount - paid_amount), 0)'), 'total']],
            where: { type: 'payable', status: 'active' },
            group: ['currency'],
            raw: true,
        })

        // Por cobrar activas - por moneda
        const receivableRows = await Debt.findAll({
            attributes: ['currency', [literal('COALESCE(SUM(total_amount - paid_amount), 0)'), 'total']],
            where: { type: 'receivable', status: 'active' },
            group: ['currency'],
            raw: true,
        })

        // Activos por moneda
        const assetsRows = await Asset.findAll({
            attributes: ['currency', [fn('COALESCE', fn('SUM', col('estimated_value')), 0), 'total']],
            group: ['currency'],
            raw: true,
        })

        const monthlyIncome = parseFloat(incomeResult?.total || 0)
        const monthlyExpenses = parseFloat(expenseResult?.total || 0)
        const savings = byCurrency(savingsRows)
        const debtsPayable = byCurrency(payableRows)
        const debtsReceivable = byCurrency(receivableRows)
        const assets = byCurrency(assetsRows)

        res.json({
            rate: { parallelRate, officialRate },
            monthlyIncome,
            monthlyExpenses,
            monthlyBalance: monthlyIncome - monthlyExpenses,
            savings,
            debtsPayable,
            debtsReceivable,
            assets,
            netWorth: assets.totalBOB - debtsPayable.totalBOB,
        })
    } catch (err) {
        res.status(500).json({ message: err.message })
    }
}
