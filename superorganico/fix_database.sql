-- Arreglar productos
ALTER TABLE productos CHANGE stock stock_actual DECIMAL(10,2) DEFAULT 0;

-- Agregar columnas faltantes a productos si no existen
ALTER TABLE productos 
ADD COLUMN IF NOT EXISTS codigo VARCHAR(255) UNIQUE AFTER id,
ADD COLUMN IF NOT EXISTS descripcion TEXT NULL AFTER nombre,
ADD COLUMN IF NOT EXISTS tipo ENUM('verdura','fruta','grano','lacteo','otro') DEFAULT 'otro' AFTER categoria,
ADD COLUMN IF NOT EXISTS unidad_medida VARCHAR(255) DEFAULT 'kg' AFTER tipo,
ADD COLUMN IF NOT EXISTS stock_minimo DECIMAL(10,2) DEFAULT 10 AFTER stock_actual,
ADD COLUMN IF NOT EXISTS dias_alerta_vencimiento INT DEFAULT 7 AFTER dias_caducidad,
ADD COLUMN IF NOT EXISTS activo TINYINT(1) DEFAULT 1 AFTER perecedero;

-- Agregar columnas faltantes a clientes
ALTER TABLE clientes
ADD COLUMN IF NOT EXISTS nit VARCHAR(255) NULL AFTER ci,
ADD COLUMN IF NOT EXISTS tipo ENUM('persona','empresa') DEFAULT 'persona' AFTER nombre,
ADD COLUMN IF NOT EXISTS direccion TEXT NULL AFTER email,
ADD COLUMN IF NOT EXISTS ciudad VARCHAR(255) NULL AFTER direccion,
ADD COLUMN IF NOT EXISTS pais VARCHAR(255) DEFAULT 'Bolivia' AFTER ciudad,
ADD COLUMN IF NOT EXISTS activo TINYINT(1) DEFAULT 1 AFTER pais;

-- Agregar columnas faltantes a proveedores  
ALTER TABLE proveedores
ADD COLUMN IF NOT EXISTS nit VARCHAR(255) NULL AFTER ci,
ADD COLUMN IF NOT EXISTS direccion TEXT NULL AFTER email,
ADD COLUMN IF NOT EXISTS ciudad VARCHAR(255) NULL AFTER direccion,
ADD COLUMN IF NOT EXISTS pais VARCHAR(255) DEFAULT 'Bolivia' AFTER ciudad,
ADD COLUMN IF NOT EXISTS activo TINYINT(1) DEFAULT 1 AFTER pais;

-- Agregar columnas faltantes a compras
ALTER TABLE compras
ADD COLUMN IF NOT EXISTS numero_factura VARCHAR(255) NULL AFTER numero_compra,
ADD COLUMN IF NOT EXISTS fecha DATE NULL AFTER numero_factura,
ADD COLUMN IF NOT EXISTS credito_fiscal DECIMAL(10,2) DEFAULT 0 AFTER impuestos,
ADD COLUMN IF NOT EXISTS observaciones TEXT NULL AFTER total;

-- Copiar fecha_compra a fecha si fecha está NULL
UPDATE compras SET fecha = fecha_compra WHERE fecha IS NULL;

-- Agregar columnas faltantes a ventas
ALTER TABLE ventas
ADD COLUMN IF NOT EXISTS tipo_comprobante ENUM('factura','nota_venta') DEFAULT 'factura' AFTER fecha_hora,
ADD COLUMN IF NOT EXISTS iva DECIMAL(10,2) DEFAULT 0 AFTER subtotal,
ADD COLUMN IF NOT EXISTS it DECIMAL(10,2) DEFAULT 0 AFTER iva,
ADD COLUMN IF NOT EXISTS observaciones TEXT NULL AFTER cambio;
