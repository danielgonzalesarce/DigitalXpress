# 🔧 Crear Regla de Firewall para Puerto 587 - Guía Visual

## 📍 Estás en el lugar correcto

Ya tienes abierto el **Firewall de Windows con seguridad avanzada** y estás viendo las **"Reglas de salida"**. Perfecto.

## 🔍 Paso 1: Verificar si Ya Existe una Regla

### Método Visual:

1. **En la lista de reglas** que ves en el centro de la ventana
2. **Busca en la columna "Nombre"** reglas que contengan:
   - "587"
   - "SMTP"
   - "Gmail"
   - "Correo"
   - "Mail"

3. **Si encuentras alguna regla** con el puerto 587:
   - Verifica que en la columna **"Habilitado"** diga **"Sí"**
   - Si dice **"No"**, haz clic derecho → **"Habilitar regla"**

4. **Si NO encuentras ninguna regla** para el puerto 587, continúa con el Paso 2

## ✅ Paso 2: Crear Nueva Regla (Si No Existe)

### Instrucciones Paso a Paso:

1. **En el panel derecho** (donde dice "Acciones"), haz clic en:
   ```
   Nueva regla...
   ```

2. **Se abrirá un asistente**. En la primera pantalla:
   - Selecciona: **"Puerto"** (debe estar seleccionado por defecto)
   - Haz clic en **"Siguiente"**

3. **Protocolo y puertos:**
   - Protocolo: Selecciona **"TCP"**
   - Puertos remotos específicos: Selecciona esta opción
   - En el cuadro de texto, escribe: **587**
   - Haz clic en **"Siguiente"**

4. **Acción:**
   - Selecciona: **"Permitir la conexión"**
   - Haz clic en **"Siguiente"**

5. **Perfil:**
   - Marca **TODAS** las casillas:
     - ✅ **Dominio**
     - ✅ **Privado**  
     - ✅ **Público**
   - Haz clic en **"Siguiente"**

6. **Nombre:**
   - Nombre: **Gmail SMTP Puerto 587**
   - Descripción (opcional): **Permite conexiones salientes al puerto 587 de Gmail SMTP para envío de correos**
   - Haz clic en **"Finalizar"**

## ✅ Paso 3: Verificar la Nueva Regla

1. **Busca en la lista** la regla que acabas de crear: **"Gmail SMTP Puerto 587"**
2. **Verifica** que:
   - En la columna **"Habilitado"** diga **"Sí"** ✅
   - En la columna **"Acción"** diga **"Permitir"** ✅
   - En la columna **"Protocolo"** diga **"TCP"** ✅

## 🧪 Paso 4: Probar la Conexión

Después de crear la regla, **cierra el Firewall** y ejecuta estos comandos en PowerShell:

```powershell
# Probar conexión al puerto 587
Test-NetConnection smtp.gmail.com -Port 587
```

**Resultado esperado:**
```
TcpTestSucceeded       : True    ← Esto debe decir True
```

Si dice **True**, entonces prueba el envío de correo:

```bash
cd "C:\Users\DANIEL ALEXANDER\Desktop\DigitalXpress"
php artisan config:clear
php artisan email:test contact
```

## 📋 Resumen Visual

```
Firewall → Reglas de salida → Nueva regla...
         ↓
    Tipo: Puerto
         ↓
    TCP → Puerto remoto: 587
         ↓
    Permitir la conexión
         ↓
    Todos los perfiles (Dominio, Privado, Público)
         ↓
    Nombre: "Gmail SMTP Puerto 587"
         ↓
    Finalizar ✅
```

## ⚠️ Nota Importante

Si después de crear la regla **sigue sin funcionar**, puede ser porque:
- Estás en una red corporativa/universitaria con firewall adicional
- Tu antivirus está bloqueando la conexión
- Necesitas permisos de administrador

En ese caso, prueba desactivar temporalmente el antivirus o contacta al administrador de red.

