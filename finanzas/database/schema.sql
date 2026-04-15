-- ============================================================
-- Finanzas Familiares - Schema MariaDB
-- Autor: Equipo Finanzas
-- Fecha: 2026-02-27
-- ============================================================

CREATE DATABASE IF NOT EXISTS finanzas_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE finanzas_db;

-- ------------------------------------------------------------
-- Usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) UNIQUE NOT NULL,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','viewer') DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW()
);

-- ------------------------------------------------------------
-- Tags (etiquetas flexibles, muchos a muchos)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tags (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  name  VARCHAR(100) NOT NULL,
  type  ENUM('income','expense','debt','savings','asset','general') NOT NULL,
  color VARCHAR(7)   -- hex: #FF5733
);

-- ------------------------------------------------------------
-- Ingresos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS incomes (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  amount      DECIMAL(12,2) NOT NULL,
  description TEXT,
  date        DATE NOT NULL,
  created_at  TIMESTAMP DEFAULT NOW(),
  updated_at  TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS income_tags (
  income_id INT NOT NULL,
  tag_id    INT NOT NULL,
  PRIMARY KEY (income_id, tag_id),
  FOREIGN KEY (income_id) REFERENCES incomes(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id)    REFERENCES tags(id)    ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Gastos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS expenses (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  amount      DECIMAL(12,2) NOT NULL,
  description TEXT,
  date        DATE NOT NULL,
  created_at  TIMESTAMP DEFAULT NOW(),
  updated_at  TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS expense_tags (
  expense_id INT NOT NULL,
  tag_id     INT NOT NULL,
  PRIMARY KEY (expense_id, tag_id),
  FOREIGN KEY (expense_id) REFERENCES expenses(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id)     REFERENCES tags(id)     ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Ahorros (metas de ahorro)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS savings (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  name           VARCHAR(150) NOT NULL,
  target_amount  DECIMAL(12,2),
  current_amount DECIMAL(12,2) DEFAULT 0,
  notes          TEXT,
  created_at     TIMESTAMP DEFAULT NOW(),
  updated_at     TIMESTAMP DEFAULT NOW() ON UPDATE NOW()
);

CREATE TABLE IF NOT EXISTS savings_movements (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  savings_id  INT NOT NULL,
  user_id     INT NOT NULL,
  amount      DECIMAL(12,2) NOT NULL, -- positivo=depósito, negativo=retiro
  description TEXT,
  date        DATE NOT NULL,
  created_at  TIMESTAMP DEFAULT NOW(),
  updated_at  TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
  FOREIGN KEY (savings_id) REFERENCES savings(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id)    REFERENCES users(id)   ON DELETE RESTRICT
);

-- ------------------------------------------------------------
-- Deudas (por pagar y por cobrar)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS debts (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT NOT NULL,
  type         ENUM('payable','receivable') NOT NULL,
  counterpart  VARCHAR(150) NOT NULL,
  description  TEXT,
  total_amount DECIMAL(12,2) NOT NULL,
  paid_amount  DECIMAL(12,2) DEFAULT 0,
  due_date     DATE,
  status       ENUM('active','paid','cancelled') DEFAULT 'active',
  created_at   TIMESTAMP DEFAULT NOW(),
  updated_at   TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS debt_payments (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  debt_id    INT NOT NULL,
  user_id    INT NOT NULL,
  amount     DECIMAL(12,2) NOT NULL,
  date       DATE NOT NULL,
  notes      TEXT,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
  FOREIGN KEY (debt_id) REFERENCES debts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS debt_tags (
  debt_id INT NOT NULL,
  tag_id  INT NOT NULL,
  PRIMARY KEY (debt_id, tag_id),
  FOREIGN KEY (debt_id) REFERENCES debts(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id)  REFERENCES tags(id)  ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Patrimonio
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS assets (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  owner_user_id    INT NOT NULL,
  name             VARCHAR(150) NOT NULL,
  type             ENUM('real_estate','vehicle','savings','other') NOT NULL,
  estimated_value  DECIMAL(12,2) NOT NULL,
  purchase_value   DECIMAL(12,2),
  purchase_date    DATE,
  debt_id          INT,  -- deuda asociada (ej: hipoteca, crédito vehicular)
  notes            TEXT,
  created_at       TIMESTAMP DEFAULT NOW(),
  updated_at       TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
  FOREIGN KEY (owner_user_id) REFERENCES users(id)  ON DELETE RESTRICT,
  FOREIGN KEY (debt_id)       REFERENCES debts(id)  ON DELETE SET NULL
);

-- ------------------------------------------------------------
-- Logs de auditoría
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS audit_logs (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT,
  action     ENUM('create','update','delete') NOT NULL,
  table_name VARCHAR(50) NOT NULL,
  record_id  INT NOT NULL,
  old_data   JSON,
  new_data   JSON,
  created_at TIMESTAMP DEFAULT NOW(),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ------------------------------------------------------------
-- Índices útiles
-- ------------------------------------------------------------
CREATE INDEX idx_incomes_date      ON incomes(date);
CREATE INDEX idx_expenses_date     ON expenses(date);
CREATE INDEX idx_debts_type_status ON debts(type, status);
CREATE INDEX idx_audit_table       ON audit_logs(table_name, record_id);
CREATE INDEX idx_audit_user        ON audit_logs(user_id);
