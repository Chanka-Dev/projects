import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const Savings = sequelize.define('Savings', {
    name: { type: DataTypes.STRING(150), allowNull: false },
    targetAmount: { type: DataTypes.DECIMAL(12, 2), field: 'target_amount' },
    currentAmount: { type: DataTypes.DECIMAL(12, 2), defaultValue: 0, field: 'current_amount' },
    currency: { type: DataTypes.ENUM('BOB', 'USD'), defaultValue: 'BOB', allowNull: false },
    notes: { type: DataTypes.TEXT },
}, { tableName: 'savings' })

export default Savings
