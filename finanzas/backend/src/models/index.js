// Pivot tables for tags (many-to-many)
import { sequelize } from '../config/database.js'
import { DataTypes } from 'sequelize'

import User from './User.js'
import Tag from './Tag.js'
import Income from './Income.js'
import Expense from './Expense.js'
import Savings from './Savings.js'
import SavingsMovement from './SavingsMovement.js'
import Debt from './Debt.js'
import DebtPayment from './DebtPayment.js'
import Asset from './Asset.js'
import AuditLog from './AuditLog.js'
import ExchangeRate from './ExchangeRate.js'

// Tablas pivot para tags
const IncomeTag = sequelize.define('IncomeTag', {}, { tableName: 'income_tags', timestamps: false })
const ExpenseTag = sequelize.define('ExpenseTag', {}, { tableName: 'expense_tags', timestamps: false })
const DebtTag = sequelize.define('DebtTag', {}, { tableName: 'debt_tags', timestamps: false })

// Asociaciones
User.hasMany(Income, { foreignKey: 'user_id' })
Income.belongsTo(User, { foreignKey: 'user_id' })

User.hasMany(Expense, { foreignKey: 'user_id' })
Expense.belongsTo(User, { foreignKey: 'user_id' })

User.hasMany(SavingsMovement, { foreignKey: 'user_id' })
SavingsMovement.belongsTo(User, { foreignKey: 'user_id' })

Savings.hasMany(SavingsMovement, { foreignKey: 'savings_id' })
SavingsMovement.belongsTo(Savings, { foreignKey: 'savings_id' })

User.hasMany(Debt, { foreignKey: 'user_id' })
Debt.belongsTo(User, { foreignKey: 'user_id' })

Debt.hasMany(DebtPayment, { foreignKey: 'debt_id' })
DebtPayment.belongsTo(Debt, { foreignKey: 'debt_id' })
DebtPayment.belongsTo(User, { foreignKey: 'user_id' })

User.hasMany(Asset, { foreignKey: 'owner_user_id', as: 'ownedAssets' })
Asset.belongsTo(User, { foreignKey: 'owner_user_id', as: 'owner' })

Asset.belongsTo(Debt, { foreignKey: 'debt_id', as: 'associatedDebt' })

AuditLog.belongsTo(User, { foreignKey: 'user_id' })

// Tags many-to-many
Income.belongsToMany(Tag, { through: IncomeTag, foreignKey: 'income_id', as: 'tags' })
Tag.belongsToMany(Income, { through: IncomeTag, foreignKey: 'tag_id' })

Expense.belongsToMany(Tag, { through: ExpenseTag, foreignKey: 'expense_id', as: 'tags' })
Tag.belongsToMany(Expense, { through: ExpenseTag, foreignKey: 'tag_id' })

Debt.belongsToMany(Tag, { through: DebtTag, foreignKey: 'debt_id', as: 'tags' })
Tag.belongsToMany(Debt, { through: DebtTag, foreignKey: 'tag_id' })

export {
    User, Tag, Income, Expense,
    Savings, SavingsMovement,
    Debt, DebtPayment,
    Asset, AuditLog,
    ExchangeRate,
    IncomeTag, ExpenseTag, DebtTag,
}
