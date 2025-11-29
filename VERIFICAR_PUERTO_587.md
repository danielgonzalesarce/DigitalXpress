# 🔍 Cómo Verificar y Permitir el Puerto 587 en Windows Firewall

## 📋 Paso 1: Verificar si Existe una Regla para el Puerto 587

### Opción A: Buscar en el Firewall

1. En la ventana del **Firewall de Windows con seguridad avanzada** que tienes abierta
2. En la sección **"Reglas de salida"** (Outbound Rules)
3. Haz clic en la columna **"Protocolo"** para ordenar por protocolo
4. Busca reglas con protocolo **"TCP"**
5. Busca en la columna **"Puerto local"** o **"Puerto remoto"** el número **587**

### Opción B: Buscar con Filtro

1. En el panel derecho, haz clic en **"Filtrar por perfil"**
2. Selecciona **"Todo"**
3. Luego haz clic derecho en cualquier regla y selecciona **"Ver"** → **"Agregar o quitar columnas"**
4. Asegúrate de que las columnas **"Puerto local"** y **"Puerto remoto"** estén visibles
5. Busca el puerto **587**

## ✅ Paso 2: Crear una Regla si No Existe

Si **NO encuentras** una regla para el puerto 587, créala así:

### Instrucciones Detalladas:

1. **En el panel derecho**, haz clic en **"Nueva regla..."** (New rule...)

2. **Tipo de regla:**
   - Selecciona **"Puerto"**
   - Haz clic en **"Siguiente"**

3. **Protocolo y puertos:**
   - Selecciona **"TCP"**
   - Selecciona **"Puertos remotos específicos"**
   - Escribe: **587**
   - Haz clic en **"Siguiente"**

4. **Acción:**
   - Selecciona **"Permitir la conexión"**
   - Haz clic en **"Siguiente"**

5. **Perfil:**
   - Marca las tres opciones:
     - ✅ **Dominio**
     - ✅ **Privado**
     - ✅ **Público**
   - Haz clic en **"Siguiente"**

6. **Nombre:**
   - Nombre: **"Gmail SMTP Puerto 587"**
   - Descripción (opcional): **"Permite conexiones salientes al puerto 587 de Gmail SMTP"**
   - Haz clic en **"Finalizar"**

## 🔍 Paso 3: Verificar que la Regla Esté Habilitada

1. Busca la regla que acabas de crear: **"Gmail SMTP Puerto 587"**
2. Verifica que en la columna **"Habilitado"** diga **"Sí"**
3. Si dice **"No"**, haz clic derecho en la regla y selecciona **"Habilitar regla"**

## 🧪 Paso 4: Probar la Conexión

Después de crear la regla, prueba la conexión:

```powershell
Test-NetConnection smtp.gmail.com -Port 587
```

Debería mostrar: **TcpTestSucceeded: True**

Luego prueba el envío de correo:

```bash
php artisan config:clear
php artisan email:test contact
```

## 📝 Nota Importante

Si estás en una **red corporativa o universitaria**, es posible que:
- El firewall corporativo bloquee el puerto 587
- Necesites contactar al administrador de red
- Necesites usar un proxy o VPN

## ✅ Verificación Rápida

Para verificar rápidamente si el puerto está bloqueado, ejecuta en PowerShell:

```powershell
Test-NetConnection smtp.gmail.com -Port 587
```

- Si **TcpTestSucceeded: True** → El puerto está abierto ✅
- Si **TcpTestSucceeded: False** → El puerto está bloqueado ❌

