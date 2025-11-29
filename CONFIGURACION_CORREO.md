# 📧 Configuración de Correo Electrónico - DigitalXpress

Este documento explica cómo configurar el sistema de correos electrónicos para recibir notificaciones de usuarios.

## 📋 Descripción

El sistema envía automáticamente correos electrónicos a **soportedigitalxpress@gmail.com** cuando:
- Un usuario solicita una reparación
- Un usuario realiza un pedido
- Un usuario envía un mensaje desde el formulario de contacto

## ⚙️ Configuración

### 1. Variables de Entorno (.env)

Agrega las siguientes variables en tu archivo `.env`:

```env
# Configuración de correo
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_correo@gmail.com
MAIL_PASSWORD=tu_contraseña_de_aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_correo@gmail.com
MAIL_FROM_NAME="DigitalXpress"
MAIL_SUPPORT_EMAIL=soportedigitalxpress@gmail.com
```

### 2. Configuración de Gmail

Para usar Gmail como servidor SMTP, necesitas:

1. **Habilitar la verificación en 2 pasos** en tu cuenta de Google
2. **Generar una contraseña de aplicación**:
   - Ve a [Google Account Security](https://myaccount.google.com/security)
   - Activa la verificación en 2 pasos si no está activada
   - Ve a "Contraseñas de aplicaciones"
   - Genera una nueva contraseña para "Correo"
   - Usa esta contraseña en `MAIL_PASSWORD` (no tu contraseña normal de Gmail)

### 3. Correo de Destino

El correo de destino está configurado en `config/mail.php`:
- Variable: `MAIL_SUPPORT_EMAIL`
- Valor por defecto: `soportedigitalxpress@gmail.com`
- Todos los correos se envían a esta dirección

## 📨 Tipos de Correos

### 1. Notificación de Reparación
- **Cuándo se envía**: Cuando un usuario crea una nueva solicitud de reparación
- **Contenido**: 
  - Información del cliente (nombre, email, teléfono)
  - Información del dispositivo (tipo, marca, modelo)
  - Descripción del problema
  - Imagen del dispositivo (si se adjuntó)
  - Número de reparación

### 2. Notificación de Pedido
- **Cuándo se envía**: Cuando un usuario completa un pedido
- **Contenido**:
  - Información del cliente
  - Dirección de envío
  - Lista de productos pedidos
  - Total del pedido
  - Método de pago
  - Número de pedido

### 3. Notificación de Contacto
- **Cuándo se envía**: Cuando un usuario envía un mensaje desde el formulario de contacto
- **Contenido**:
  - Nombre y email del remitente
  - Asunto del mensaje
  - Contenido del mensaje

## 🔧 Modo de Desarrollo

Para desarrollo local, puedes usar el driver `log` que guarda los correos en archivos de log:

```env
MAIL_MAILER=log
```

Los correos se guardarán en `storage/logs/laravel.log`

## ✅ Verificación

Para verificar que el sistema funciona correctamente:

1. **Crear una reparación de prueba**:
   - Ve a `/reparaciones/nueva`
   - Completa el formulario
   - Verifica que llegue el correo a soportedigitalxpress@gmail.com

2. **Realizar un pedido de prueba**:
   - Agrega productos al carrito
   - Completa el checkout
   - Verifica que llegue el correo de notificación

3. **Enviar mensaje de contacto**:
   - Ve a `/contacto`
   - Completa el formulario
   - Verifica que llegue el correo

## 🐛 Solución de Problemas

### Error: "Could not authenticate"
- Verifica que `MAIL_PASSWORD` sea una contraseña de aplicación, no tu contraseña normal
- Asegúrate de que la verificación en 2 pasos esté activada

### Error: "Connection timeout"
- Verifica que `MAIL_HOST` sea `smtp.gmail.com`
- Verifica que `MAIL_PORT` sea `587`
- Verifica que `MAIL_ENCRYPTION` sea `tls`

### Los correos no se envían pero no hay error
- Revisa `storage/logs/laravel.log` para ver errores detallados
- Verifica que las variables de entorno estén correctamente configuradas
- Ejecuta `php artisan config:clear` después de cambiar el `.env`

## 📝 Notas Importantes

- Los correos se envían de forma asíncrona, por lo que pueden tardar unos segundos
- Si hay un error al enviar el correo, se registra en el log pero no interrumpe el flujo de la aplicación
- El correo de destino está hardcodeado en `config/mail.php` como `soportedigitalxpress@gmail.com`
- Para cambiar el correo de destino, modifica `MAIL_SUPPORT_EMAIL` en el `.env` o edita `config/mail.php`

