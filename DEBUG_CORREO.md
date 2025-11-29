# 🔍 Debug: Correo No Llega

## ✅ Estado Actual

- ✅ Configuración SMTP correcta
- ✅ Puerto 587 accesible
- ✅ Comando de prueba funciona
- ❌ Formulario de contacto no envía correos

## 🔍 Pasos para Diagnosticar

### 1. Verificar que el Formulario se Está Enviando

Cuando envíes un mensaje desde el formulario, deberías ver:
- Un mensaje de éxito: "Tu mensaje ha sido enviado exitosamente..."
- O un mensaje de error

### 2. Revisar los Logs en Tiempo Real

Después de enviar el formulario, ejecuta:

```powershell
Get-Content storage\logs\laravel.log -Tail 50 -Wait
```

Busca líneas que contengan:
- "Intentando enviar correo de contacto"
- "Correo de contacto enviado exitosamente"
- "Error al enviar correo de contacto"
- "Usuario autenticado enviando mensaje"

### 3. Verificar la Red

Si cambiaste de red (universitaria a móvil), verifica:

```powershell
Test-NetConnection smtp.gmail.com -Port 587
```

Debe mostrar: `TcpTestSucceeded: True`

### 4. Verificar Configuración

```bash
php artisan config:show mail.default
```

Debe mostrar: `smtp` (no `log`)

### 5. Probar Manualmente

```bash
php artisan email:test contact
```

Si esto funciona pero el formulario no, el problema está en el código del formulario.

## 🐛 Posibles Problemas

### Problema 1: Validación Falla Silenciosamente

Si el usuario está autenticado pero hay un error de validación, podría redirigir sin mostrar error.

**Solución:** Verifica que el mensaje tenga al menos 10 caracteres.

### Problema 2: Error en el Envío pero No se Muestra

El correo podría estar fallando pero el error no se está mostrando.

**Solución:** Revisa los logs después de enviar el formulario.

### Problema 3: Red Móvil Bloqueando

Algunas redes móviles bloquean SMTP.

**Solución:** Prueba desde otra red o usa Mailtrap.

## 📝 Información a Revisar

Cuando envíes el formulario, verifica:

1. ¿Ves un mensaje de éxito o error?
2. ¿Qué dice el mensaje?
3. ¿Revisaste la carpeta de Spam en Gmail?
4. ¿Qué muestran los logs?

## ✅ Próximos Pasos

1. Envía un mensaje desde el formulario
2. Revisa los logs inmediatamente después
3. Comparte los resultados para diagnosticar el problema

