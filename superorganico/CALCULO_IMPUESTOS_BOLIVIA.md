# Sistema de Cálculo de Precios e Impuestos - SuperOrgánico

## Cálculo de Precios de Venta

### Paso 1: Precio Base con Margen
```
Precio Compra:     Bs. 100.00
Margen (50%):      + 50.00
Precio Base:       Bs. 150.00
```

### Paso 2: Aplicar Tasa Efectiva (14.91%)
```
Precio Base:       Bs. 150.00
Tasa Efectiva:     × 1.1491
PRECIO FACTURA:    Bs. 172.37
```

**Este es el precio que el cliente ve y paga** (ya incluye IVA incorporado)

## Sistema de Impuestos

### 1. IVA (Impuesto al Valor Agregado)
- **Tasa**: 13%
- **¿Quién lo paga?**: Ya está incluido en el precio factura
- **Cálculo para declaración**: `IVA = Precio Factura × 0.13`
- **Ejemplo**: 172.37 × 0.13 = Bs. 22.41

### 2. IT (Impuesto a las Transacciones)
- **Tasa**: 3%
- **¿Quién lo paga?**: El negocio (NO se cobra al cliente)
- **Cálculo**: `IT = Precio Factura × 0.03`
- **Ejemplo**: 172.37 × 0.03 = Bs. 5.17

## ¿Qué paga el cliente?

El cliente paga únicamente el **PRECIO FACTURA**: Bs. 172.37

**NO se le cobra** IVA ni IT adicional porque:
- El IVA ya está incorporado en el precio (por eso usamos tasa efectiva 14.91%)
- El IT es responsabilidad del negocio

## Ejemplo Completo

**Producto: Manzana Orgánica**

```
1. Precio Compra:              Bs. 20.00
2. Margen 50%:                 × 1.5
   Precio Base:                Bs. 30.00
3. Tasa Efectiva 14.91%:       × 1.1491
   PRECIO FACTURA:             Bs. 34.47

Cliente paga:                  Bs. 34.47
───────────────────────────────────────
Impuestos a declarar/pagar:
- IVA (34.47 × 0.13):          Bs. 4.48
- IT (34.47 × 0.03):           Bs. 1.03
```

## Desglose Financiero del Negocio

**Ingreso del cliente**:        Bs. 172.37
**Menos IT (3%)**:              - Bs. 5.17
**Ingreso neto**:               Bs. 167.20

## Verificación de Cálculos

Para verificar que los cálculos estén correctos:

```php
// Ejemplo con subtotal de Bs. 470.00
$subtotal = 470.00;
$iva = $subtotal * 0.13;              // Bs. 61.10
$baseParaIT = $subtotal + $iva;       // Bs. 531.10
$it = $baseParaIT * 0.03;             // Bs. 15.93
$total = $subtotal + $iva + $it;      // Bs. 547.03

// O usando la fórmula directa:
$total = $subtotal * 1.1639;          // Bs. 547.03
```

## Error Común

❌ **INCORRECTO**: Calcular IT sobre una "tasa efectiva" artificial
```php
$precioFactura = $subtotal * 1.1491;  // ← No existe este concepto
$it = $precioFactura * 0.03;          // ← IT calculado incorrectamente
```

✅ **CORRECTO**: Calcular IT sobre el total con IVA
```php
$iva = $subtotal * 0.13;
$it = ($subtotal + $iva) * 0.03;
$total = $subtotal + $iva + $it;
```

## Impacto en la Contabilidad

### Registro contable de una venta:

```
DEBE                                    HABER
─────────────────────────────────────────────────
Caja/Bancos        Bs. 1,163.90
                                        Ventas            Bs. 1,000.00
                                        IVA por Pagar     Bs.   130.00
                                        IT por Pagar      Bs.    33.90
```

El negocio debe pagar al Estado:
- IVA: Bs. 130.00
- IT: Bs. 33.90
Total impuestos: Bs. 163.90

## Referencias

- Ley 843 (Reforma Tributaria Bolivia)
- IVA: Art. 1-17
- IT: Art. 72-77
