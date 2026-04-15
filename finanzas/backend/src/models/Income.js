import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const Income = sequelize.define('Income', {
  userId: { type: DataTypes.INTEGER, allowNull: false, field: 'user_id' },
  amount: { type: DataTypes.DECIMAL(12, 2), allowNull: false },
  description: { type: DataTypes.TEXT },
  date: { type: DataTypes.DATEONLY, allowNull: false },
}, { tableName: 'incomes' })

export default Income
