-- ============================================================
-- Seeds iniciales - Finanzas Familiares
-- Ejecutar DESPUÉS del schema.sql
-- ============================================================

USE finanzas_db;

-- Usuarios reales de la familia
INSERT INTO users (name, email, password, role) VALUES
  ('Pedro', 'clintz210394@gmail.com', '$2a$12$W8ffb9X4iQBCh6K2m/0l/uFyem2fiU4K0UeqE2iRd7aut25FPMcDK', 'admin'),
  ('Marce', 'marcemirandacuba@gmail.com', '$2a$12$QZ8xt4NTN0CLxlTgsJfhhuVKgocS7L2/fDQ4Bodu8ZgyckuaLO3Sm', 'admin');

-- Tags base
INSERT INTO tags (name, type, color) VALUES
  -- Ingresos
  ('Salario', 'income', '#10b981'),
  ('Freelance', 'income', '#3b82f6'),
  ('Bono', 'income', '#8b5cf6'),
  ('Otros ingresos', 'income', '#6b7280'),

  -- Gastos
  ('Alimentación', 'expense', '#f59e0b'),
  ('Servicios básicos', 'expense', '#ef4444'),
  ('Transporte', 'expense', '#f97316'),
  ('Salud', 'expense', '#ec4899'),
  ('Educación', 'expense', '#06b6d4'),
  ('Entretenimiento', 'expense', '#8b5cf6'),
  ('Vestimenta', 'expense', '#84cc16'),
  ('Hogar', 'expense', '#14b8a6'),
  ('Otros gastos', 'expense', '#6b7280'),

  -- Deudas
  ('Hipoteca', 'debt', '#dc2626'),
  ('Crédito vehicular', 'debt', '#ea580c'),
  ('Préstamo personal', 'debt', '#d97706'),
  ('Tarjeta crédito', 'debt', '#c026d3'),

  -- Ahorros
  ('Emergencia', 'savings', '#059669'),
  ('Vacaciones', 'savings', '#0284c7'),
  ('Inversión', 'savings', '#7c3aed'),

  -- General
  ('Mensual', 'general', '#64748b'),
  ('Anual', 'general', '#475569'),
  ('Compartido', 'general', '#94a3b8');

-- Ahorros iniciales de ejemplo
INSERT INTO savings (name, target_amount, current_amount, notes) VALUES
  ('Fondo de emergencia', 5000.00, 0.00, 'Meta: 3 meses de gastos'),
  ('Vacaciones', 2000.00, 0.00, 'Viaje familiar');
