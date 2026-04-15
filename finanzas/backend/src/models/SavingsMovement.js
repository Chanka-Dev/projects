import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const SavingsMovement = sequelize.define('SavingsMovement', {
    savingsId: { type: DataTypes.INTEGER, allowNull: false, field: 'savings_id' },
    userId: { type: DataTypes.INTEGER, allowNull: false, field: 'user_id' },
    amount: { type: DataTypes.DECIMAL(12, 2), allowNull: false }, // positivo=depósito, negativo=retiro
    description: { type: DataTypes.TEXT },
    date: { type: DataTypes.DATEONLY, allowNull: false },
}, { tableName: 'savings_movements' })

export default SavingsMovement
