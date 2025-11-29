# 🔧 Solución: Error de Conexión con Gmail SMTP

## ✅ Configuración Actual

Tu archivo `.env` ya está configurado correctamente:
- ✅ MAIL_MAILER=smtp
- ✅ MAIL_HOST=smtp.gmail.com
- ✅ MAIL_PORT=587
- ✅ MAIL_USERNAME=soportedigitalxpress@gmail.com
- ✅ MAIL_PASSWORD=qsojbfyckwgihroa (configurada)
- ✅ MAIL_ENCRYPTION=tls

## ❌ Error Actual

```
Connection could not be established with host "smtp.gmail.com:587"
```

Este error generalmente indica un problema de **conexión de red** o **firewall**.

## 🔍 Soluciones Posibles

### Solución 1: Verificar Firewall de Windows

1. Abre "Firewall de Windows Defender"
2. Ve a "Configuración avanzada"
3. Verifica que el puerto 587 esté permitido para conexiones salientes
4. Si no está permitido, agrega una regla para permitir el puerto 587 (TCP saliente)

### Solución 2: Verificar Antivirus

Algunos antivirus bloquean conexiones SMTP. Intenta:
1. Desactivar temporalmente el antivirus
2. Probar el envío de correo
3. Si funciona, agrega una excepción para PHP en tu antivirus

### Solución 3: Verificar Proxy/VPN

Si estás usando un proxy o VPN:
1. Desactívalo temporalmente
2. Prueba el envío de correo
3. Si funciona, configura el proxy en PHP o desactívalo para conexiones SMTP

### Solución 4: Verificar Conexión a Internet

Prueba si puedes conectarte a Gmail SMTP:

```powershell
Test-NetConnection smtp.gmail.com -Port 587
```

Si falla, hay un problema de red.

### Solución 5: Usar Mailtrap para Desarrollo (Alternativa Temporal)

Si necesitas probar el sistema mientras solucionas el problema de red, puedes usar Mailtrap:

1. Regístrate en https://mailtrap.io (gratis)
2. Obtén las credenciales SMTP
3. Actualiza tu `.env`:

```env
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=tls
```

### Solución 6: Verificar Extensión OpenSSL en PHP

Ejecuta:

```bash
php -m | findstr openssl
```

Si no aparece, necesitas habilitar OpenSSL en PHP.

## 📝 Verificación Rápida

Ejecuta estos comandos para verificar:

```bash
# Ver configuración actual
php artisan config:show mail

# Probar conexión
php artisan email:test contact
```

## 🆘 Si Nada Funciona

Como alternativa temporal, puedes:

1. **Usar el modo log** para desarrollo:
   ```env
   MAIL_MAILER=log
   ```
   Los correos se guardarán en `storage/logs/laravel.log`

2. **Usar un servicio de correo alternativo** como:
   - Mailgun
   - SendGrid
   - Amazon SES

3. **Contactar al administrador de red** si estás en una red corporativa que bloquea SMTP

## ✅ Estado Actual

- ✅ Contraseña de aplicación configurada
- ✅ Variables de entorno correctas
- ❌ Problema de conexión de red/firewall

Una vez que resuelvas el problema de conexión, el sistema funcionará correctamente.

