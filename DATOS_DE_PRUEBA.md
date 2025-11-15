# 🛒 DATOS DE PRUEBA - SISTEMA DEMO

## ⚠️ IMPORTANTE: SISTEMA DE DEMOSTRACIÓN INDEPENDIENTE
Este es un sistema de **simulación/demo completamente independiente**. 
- ✅ **Todos los pagos son ficticios** y no procesan dinero real
- ✅ **No afecta las estadísticas del administrador**
- ✅ **Solo es una simulación local para pruebas**
- ✅ **Las órdenes de demo no aparecen en el panel de admin**

---

## 💳 TARJETAS DE PRUEBA (DEMO)

### ✅ **Cualquier tarjeta con formato válido funciona:**
- **Visa**: `4532 1234 5678 9012` (16 dígitos)
- **Mastercard**: `5555 5555 5555 4444` (16 dígitos)
- **American Express**: `3782 8224 6310 005` (15 dígitos)
- **Discover**: `6011 1111 1111 1117` (16 dígitos)
- **O cualquier número**: `1234 5678 9012 3456` (16 dígitos)

### 📝 **Requisitos de formato:**
- **Número de tarjeta**: Entre 13 y 19 dígitos (solo números)
- **CVV**: 3 o 4 dígitos (ej: `123`, `4567`)
- **Vencimiento**: Mes 1-12, Año actual o futuro (ej: `12/2026`, `06/2027`)
- **Nombre del titular**: Cualquier texto (ej: `Juan Pérez`, `María García`)

---

## 📱 YAPE (DEMO)

### ✅ **Números de Yape que SIEMPRE funcionan:**
- `912345678`
- `987654321`
- `901234567`
- `998877665`

### 📝 **Formato requerido:**
- Debe empezar con `9`
- Debe tener 9 dígitos en total
- Ejemplo: `912345678`

---

## 🛍️ DATOS DE CLIENTE (DEMO)

### 📋 **Información de envío:**
- **Nombre**: `Juan Pérez` (o cualquier nombre)
- **Email**: `juan@email.com` (o cualquier email válido)
- **Teléfono**: `912345678` (cualquier número de 9 dígitos)
- **Dirección**: `Av. Principal 123, Lima` (cualquier dirección)

---

## 🎯 INSTRUCCIONES DE PRUEBA

### 1. **Agregar productos al carrito:**
   - Ve a `http://127.0.0.1:8081/productos`
   - Haz clic en "Agregar al Carrito" en varios productos

### 2. **Hacer checkout:**
   - Ve a `http://127.0.0.1:8081/carrito`
   - Haz clic en "Proceder al Pago"

### 3. **Completar datos:**
   - Llena todos los campos marcados con *
   - Usa cualquier información válida

### 4. **Probar pago:**
   - **Con Tarjeta**: Usa cualquiera de las tarjetas de prueba
   - **Con Yape**: Usa cualquiera de los números de Yape
   - **Con PayPal**: Simplemente selecciona PayPal

### 5. **Resultado esperado:**
   - ✅ **Siempre exitoso** (es un demo)
   - ✅ **Mensaje de éxito** "¡Pago Exitoso!"
   - ✅ **Redirección** a la página de confirmación
   - ✅ **Orden creada** en la base de datos

---

## 🔧 CARACTERÍSTICAS DEL SISTEMA DEMO

- **Sin validaciones reales**: Todas las tarjetas y números funcionan
- **Sin procesamiento real**: No se cobra dinero real
- **Solo simulación**: Perfecto para demostraciones y pruebas
- **Datos ficticios**: Todos los IDs de transacción son simulados

---

## 🔒 SISTEMA INDEPENDIENTE DEL ADMINISTRADOR

### ✅ **Características de Independencia:**
- **Órdenes de demo**: Marcadas como `demo_simulation` (no aparecen en admin)
- **Estadísticas limpias**: El panel de admin solo muestra órdenes reales
- **Simulación pura**: Perfecto para demostraciones sin afectar datos reales
- **Aislamiento completo**: Las pruebas no interfieren con el sistema real

### 📊 **Panel de Administrador:**
- **No muestra simulaciones**: Solo órdenes reales y pagos verdaderos
- **Estadísticas precisas**: Ingresos y ventas reales únicamente
- **Datos limpios**: Sin contaminación de datos de prueba

## 🎉 ¡LISTO PARA PROBAR!

El sistema está configurado para ser una **demostración completa e independiente** sin complicaciones. ¡Disfruta probando todas las funcionalidades sin afectar el sistema real!