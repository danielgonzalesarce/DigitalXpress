# 🧪 Instrucciones para Probar el Sistema de Correos

## ✅ Estado Actual

- ✅ Servidor iniciado en: **http://127.0.0.1:8081**
- ✅ Caché limpiado completamente
- ✅ Configuración SMTP correcta
- ✅ Puerto 587 accesible

## 📋 Pasos para Probar

### 1. Acceder a la Aplicación

Abre tu navegador y ve a:
```
http://127.0.0.1:8081
```

### 2. Iniciar Sesión

- Inicia sesión con tu cuenta de usuario
- O crea una cuenta nueva si no tienes una

### 3. Ir al Formulario de Contacto

- Haz clic en "Contacto" en el menú
- O ve directamente a: `http://127.0.0.1:8081/contacto`

### 4. Verificar que Estás Logueado

Deberías ver un mensaje azul que dice:
```
Enviando como: [Tu Nombre] ([Tu Email])
```

### 5. Enviar un Mensaje

- **Asunto:** Escribe un asunto (ej: "Prueba de correo")
- **Mensaje:** Escribe un mensaje de **al menos 10 caracteres**
- Haz clic en **"Enviar Mensaje"**

### 6. Verificar el Resultado

Después de enviar, deberías ver:
- ✅ **Mensaje de éxito verde** que dice: "Tu mensaje ha sido enviado exitosamente..."
- O ❌ **Mensaje de error rojo** si algo falló

### 7. Revisar el Correo

1. Ve a: https://mail.google.com
2. Inicia sesión con: **soportedigitalxpress@gmail.com**
3. Revisa la **bandeja de entrada**
4. Si no lo ves, revisa la carpeta de **Spam**
5. Busca un correo con asunto: **"Nuevo Mensaje de Contacto - [Tu asunto]"**

### 8. Verificar el Contenido del Correo

El correo debe mostrar:
- ✅ Tu nombre (del usuario logueado)
- ✅ Tu email (del usuario logueado)
- ✅ El asunto que escribiste
- ✅ El mensaje completo que escribiste
- ✅ Indicador de "Usuario Registrado"

## 🔍 Si No Llega el Correo

### Revisar los Logs

Ejecuta en PowerShell:

```powershell
Get-Content storage\logs\laravel.log -Tail 50
```

Busca:
- "Intentando enviar correo de contacto"
- "Correo de contacto enviado exitosamente"
- "Error al enviar correo de contacto"

### Verificar la Conexión

```powershell
Test-NetConnection smtp.gmail.com -Port 587
```

Debe mostrar: `TcpTestSucceeded: True`

### Probar Manualmente

```bash
php artisan email:test contact
```

Si esto funciona pero el formulario no, hay un problema en el código del formulario.

## 📝 Checklist de Prueba

- [ ] Servidor corriendo en http://127.0.0.1:8081
- [ ] Usuario logueado en la aplicación
- [ ] Veo el mensaje "Enviando como: [Mi nombre]"
- [ ] Escribí un asunto
- [ ] Escribí un mensaje de al menos 10 caracteres
- [ ] Hice clic en "Enviar Mensaje"
- [ ] Vi un mensaje de éxito o error
- [ ] Revisé mi correo en soportedigitalxpress@gmail.com
- [ ] Revisé la carpeta de Spam
- [ ] El correo muestra mi nombre y mensaje correctos

## 🆘 Si Algo No Funciona

Comparte esta información:
1. ¿Qué mensaje ves después de enviar? (éxito/error)
2. ¿Qué aparece en los logs?
3. ¿Revisaste la carpeta de Spam?
4. ¿El comando `php artisan email:test contact` funciona?

