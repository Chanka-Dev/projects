import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const DebtPayment = sequelize.define('DebtPayment', {
    debtId: { type: DataTypes.INTEGER, allowNull: false, field: 'debt_id' },
    userId: { type: DataTypes.INTEGER, allowNull: false, field: 'user_id' },
    amount: { type: DataTypes.DECIMAL(12, 2), allowNull: false },
    date: { type: DataTypes.DATEONLY, allowNull: false },
    notes: { type: DataTypes.TEXT },
}, { tableName: 'debt_payments' })

export default DebtPayment
