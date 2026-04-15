import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const Asset = sequelize.define('Asset', {
    ownerUserId: { type: DataTypes.INTEGER, allowNull: true, field: 'owner_user_id' },
    name: { type: DataTypes.STRING(150), allowNull: false },
    type: { type: DataTypes.ENUM('real_estate', 'vehicle', 'savings', 'other'), allowNull: false },
    estimatedValue: { type: DataTypes.DECIMAL(12, 2), allowNull: false, field: 'estimated_value' },
    purchaseValue: { type: DataTypes.DECIMAL(12, 2), field: 'purchase_value' },
    purchaseDate: { type: DataTypes.DATEONLY, field: 'purchase_date' },
    currency: { type: DataTypes.ENUM('BOB', 'USD'), defaultValue: 'BOB', allowNull: false },
    debtId: { type: DataTypes.INTEGER, field: 'debt_id' },
    notes: { type: DataTypes.TEXT },
}, { tableName: 'assets' })

export default Asset
