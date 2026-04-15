-- Migración: soporte multi-moneda (USD/BOB)

CREATE TABLE IF NOT EXISTS exchange_rates (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  official_rate DECIMAL(10,4) NOT NULL COMMENT 'Tasa oficial BOB/USD (ej: 6.96)',
  parallel_rate DECIMAL(10,4) NOT NULL COMMENT 'Tasa paralela/mercado (ej: 9.40)',
  date          DATE NOT NULL,
  notes         TEXT,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- MySQL 8.0 no soporta IF NOT EXISTS en ALTER, usamos procedure
DROP PROCEDURE IF EXISTS add_currency_col;
DELIMITER $$
CREATE PROCEDURE add_currency_col()
BEGIN
  IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='savings' AND COLUMN_NAME='currency') THEN
    ALTER TABLE savings ADD COLUMN currency ENUM('BOB','USD') NOT NULL DEFAULT 'BOB';
  END IF;
  IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='assets' AND COLUMN_NAME='currency') THEN
    ALTER TABLE assets ADD COLUMN currency ENUM('BOB','USD') NOT NULL DEFAULT 'BOB';
  END IF;
  IF NOT EXISTS (SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='debts' AND COLUMN_NAME='currency') THEN
    ALTER TABLE debts ADD COLUMN currency ENUM('BOB','USD') NOT NULL DEFAULT 'BOB';
  END IF;
END$$
DELIMITER ;
CALL add_currency_col();
DROP PROCEDURE IF EXISTS add_currency_col;

INSERT INTO exchange_rates (official_rate, parallel_rate, date, notes)
SELECT 6.96, 9.40, CURDATE(), 'Tasa inicial configurada al activar multi-moneda'
WHERE NOT EXISTS (SELECT 1 FROM exchange_rates LIMIT 1);

SELECT 'Migration OK' AS result;
