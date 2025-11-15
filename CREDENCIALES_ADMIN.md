# 🔐 Credenciales de Administrador

## Sistema de Permisos

**IMPORTANTE:** Solo los usuarios con email `@digitalxpress.com` pueden acceder al panel de administración.

- ✅ **Usuarios con `@digitalxpress.com`** → Acceso completo al panel de administración
- ❌ **Usuarios con otros dominios (ej: `@gmail.com`)** → Solo acceso al home de usuario, sin acceso al panel de administración

## Usuario Administrador

**Email:** `admin@digitalxpress.com`  
**Contraseña:** `password`

## Acceso al Panel de Administración

1. **Inicia sesión** en: `http://127.0.0.1:8081/login`
   - Usa las credenciales de arriba (debe ser `@digitalxpress.com`)

2. **Accede al panel** en: `http://127.0.0.1:8081/admin/dashboard`
   - Si intentas acceder con un email que no sea `@digitalxpress.com`, serás redirigido al home con un mensaje de error

## Rutas del Panel de Administración

- **Dashboard:** `/admin/dashboard`
- **Órdenes:** `/admin/orders`
- **Detalles de Orden:** `/admin/orders/{order}`
- **Ingresos:** `/admin/revenue`

## Otros Usuarios de Prueba

### Usuario Cliente
- **Email:** `cliente@digitalxpress.com`
- **Contraseña:** `password`

### Usuario Técnico
- **Email:** `tecnico@digitalxpress.com`
- **Contraseña:** `password`

### Usuario VIP
- **Email:** `vip@digitalxpress.com`
- **Contraseña:** `password`

---

**Nota:** Todos los usuarios tienen la contraseña `password` por defecto. Se recomienda cambiar las contraseñas en producción.

