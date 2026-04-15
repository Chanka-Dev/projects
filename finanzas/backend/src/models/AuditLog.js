import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const AuditLog = sequelize.define('AuditLog', {
    userId: { type: DataTypes.INTEGER, field: 'user_id' },
    action: { type: DataTypes.ENUM('create', 'update', 'delete'), allowNull: false },
    tableName: { type: DataTypes.STRING(50), allowNull: false, field: 'table_name' },
    recordId: { type: DataTypes.INTEGER, allowNull: false, field: 'record_id' },
    oldData: { type: DataTypes.JSON, field: 'old_data' },
    newData: { type: DataTypes.JSON, field: 'new_data' },
}, { tableName: 'audit_logs', updatedAt: false })

export default AuditLog
