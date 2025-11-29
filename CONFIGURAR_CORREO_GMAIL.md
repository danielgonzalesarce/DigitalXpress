# 📧 Configuración de Correo Gmail - Guía Paso a Paso

## ⚠️ Problema Detectado

Actualmente el sistema está configurado en modo `log`, lo que significa que los correos se guardan en archivos de log pero **NO se envían realmente**.

## ✅ Solución: Configurar Gmail SMTP

### Paso 1: Configurar Variables de Entorno

Edita tu archivo `.env` y agrega/modifica estas líneas:

```env
# Configuración de correo Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=soportedigitalxpress@gmail.com
MAIL_PASSWORD=TU_CONTRASEÑA_DE_APLICACION_AQUI
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=soportedigitalxpress@gmail.com
MAIL_FROM_NAME="DigitalXpress"
MAIL_SUPPORT_EMAIL=soportedigitalxpress@gmail.com
```

### Paso 2: Generar Contraseña de Aplicación en Google

**IMPORTANTE:** No uses tu contraseña normal de Gmail. Necesitas generar una "Contraseña de aplicación":

1. Ve a tu cuenta de Google: https://myaccount.google.com/security
2. Asegúrate de tener **Verificación en 2 pasos activada**
3. Ve a la sección "Contraseñas de aplicaciones" (App passwords)
4. Si no la ves, primero activa la verificación en 2 pasos
5. Selecciona "Correo" como aplicación
6. Selecciona "Otro (nombre personalizado)" como dispositivo
7. Escribe "DigitalXpress" como nombre
8. Haz clic en "Generar"
9. **Copia la contraseña de 16 caracteres** (se verá algo como: `abcd efgh ijkl mnop`)
10. **Pégala en tu archivo .env** en `MAIL_PASSWORD` (sin espacios)

### Paso 3: Limpiar Caché

Después de modificar el `.env`, ejecuta:

```bash
php artisan config:clear
php artisan cache:clear
```

### Paso 4: Probar el Envío

Ejecuta este comando para probar:

```bash
php artisan email:test contact
```

Si funciona, deberías ver:
```
✅ Correo enviado exitosamente!
📬 Revisa tu bandeja de entrada en: soportedigitalxpress@gmail.com
```

### Paso 5: Verificar en Gmail

1. Ve a soportedigitalxpress@gmail.com
2. Revisa la bandeja de entrada
3. Si no lo ves, revisa la carpeta de **Spam**

## 🔍 Verificar Configuración Actual

Para ver tu configuración actual, ejecuta:

```bash
php artisan config:show mail
```

Debe mostrar:
- `default`: `smtp` (no `log`)
- `mailers ⇁ smtp ⇁ host`: `smtp.gmail.com` (no `127.0.0.1`)
- `mailers ⇁ smtp ⇁ port`: `587` (no `2525`)
- `mailers ⇁ smtp ⇁ username`: `soportedigitalxpress@gmail.com`
- `mailers ⇁ smtp ⇁ password`: `[tu contraseña]` (no `null`)

## 🐛 Problemas Comunes

### Error: "Could not authenticate"
- Verifica que `MAIL_PASSWORD` sea una contraseña de aplicación (16 caracteres)
- No uses tu contraseña normal de Gmail
- Asegúrate de que la verificación en 2 pasos esté activada

### Error: "Connection timeout"
- Verifica que `MAIL_HOST` sea exactamente `smtp.gmail.com`
- Verifica que `MAIL_PORT` sea `587`
- Verifica que `MAIL_ENCRYPTION` sea `tls`

### Los correos van a Spam
- Esto es normal al principio
- Marca los correos como "No es spam"
- Con el tiempo, Gmail aprenderá que son correos legítimos

### No encuentro "Contraseñas de aplicaciones"
- Primero debes activar la verificación en 2 pasos
- Ve a: https://myaccount.google.com/security
- Activa "Verificación en 2 pasos"
- Luego aparecerá la opción "Contraseñas de aplicaciones"

## 📝 Nota Importante

- La contraseña de aplicación es diferente a tu contraseña de Gmail
- Una vez generada, guárdala en un lugar seguro
- Si cambias tu contraseña de Gmail, necesitarás generar una nueva contraseña de aplicación

