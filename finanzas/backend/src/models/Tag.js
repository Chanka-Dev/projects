import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const Tag = sequelize.define('Tag', {
  name: { type: DataTypes.STRING(100), allowNull: false },
  type: { type: DataTypes.ENUM('income','expense','debt','savings','asset','general'), allowNull: false },
  color: { type: DataTypes.STRING(7) },
}, { tableName: 'tags', timestamps: false })

export default Tag
