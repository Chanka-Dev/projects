import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const Expense = sequelize.define('Expense', {
  userId: { type: DataTypes.INTEGER, allowNull: false, field: 'user_id' },
  amount: { type: DataTypes.DECIMAL(12, 2), allowNull: false },
  description: { type: DataTypes.TEXT },
  date: { type: DataTypes.DATEONLY, allowNull: false },
}, { tableName: 'expenses' })

export default Expense
