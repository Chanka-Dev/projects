import { DataTypes } from 'sequelize'
import { sequelize } from '../config/database.js'

const ExchangeRate = sequelize.define('ExchangeRate', {
    officialRate: {
        type: DataTypes.DECIMAL(10, 4),
        allowNull: false,
        field: 'official_rate',
        comment: 'Tasa oficial BOB por 1 USD',
    },
    parallelRate: {
        type: DataTypes.DECIMAL(10, 4),
        allowNull: false,
        field: 'parallel_rate',
        comment: 'Tasa paralela/mercado BOB por 1 USD',
    },
    date: {
        type: DataTypes.DATEONLY,
        allowNull: false,
    },
    notes: {
        type: DataTypes.TEXT,
    },
}, {
    tableName: 'exchange_rates',
    updatedAt: false,
})

export default ExchangeRate
