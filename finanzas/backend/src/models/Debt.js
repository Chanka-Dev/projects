import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const Debt = sequelize.define('Debt', {
    userId: { type: DataTypes.INTEGER, allowNull: false, field: 'user_id' },
    type: { type: DataTypes.ENUM('payable', 'receivable'), allowNull: false },
    counterpart: { type: DataTypes.STRING(150), allowNull: false },
    description: { type: DataTypes.TEXT },
    totalAmount: { type: DataTypes.DECIMAL(12, 2), allowNull: false, field: 'total_amount' },
    paidAmount: { type: DataTypes.DECIMAL(12, 2), defaultValue: 0, field: 'paid_amount' },
    dueDate: { type: DataTypes.DATEONLY, field: 'due_date' },
    currency: { type: DataTypes.ENUM('BOB', 'USD'), defaultValue: 'BOB', allowNull: false },
    status: { type: DataTypes.ENUM('active', 'paid', 'cancelled'), defaultValue: 'active' },
}, { tableName: 'debts' })

export default Debt
