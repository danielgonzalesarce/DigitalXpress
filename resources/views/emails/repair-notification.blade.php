<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud de Reparación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            color: #212529;
            margin-left: 10px;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔧 Nueva Solicitud de Reparación</h1>
        <p style="margin: 0;">Número: <strong>{{ $repair->repair_number }}</strong></p>
    </div>
    
    <div class="content">
        <p>Se ha recibido una nueva solicitud de reparación en DigitalXpress.</p>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #0d6efd;">Información del Cliente</h3>
            <p><span class="label">Nombre:</span><span class="value">{{ $repair->full_name }}</span></p>
            <p><span class="label">Email:</span><span class="value">{{ $repair->email }}</span></p>
            <p><span class="label">Teléfono:</span><span class="value">{{ $repair->phone }}</span></p>
            @if($repair->user)
            <p><span class="label">Usuario registrado:</span><span class="value">{{ $repair->user->name }} ({{ $repair->user->email }})</span></p>
            @endif
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #0d6efd;">Información del Dispositivo</h3>
            <p><span class="label">Tipo:</span><span class="value">{{ $repair->device_type }}</span></p>
            <p><span class="label">Marca:</span><span class="value">{{ $repair->brand }}</span></p>
            <p><span class="label">Modelo:</span><span class="value">{{ $repair->model }}</span></p>
            <p><span class="label">Estado:</span><span class="value">
                <span style="background-color: #ffc107; color: #000; padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                    {{ ucfirst($repair->status) }}
                </span>
            </span></p>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #0d6efd;">Descripción del Problema</h3>
            <p style="white-space: pre-wrap;">{{ $repair->problem_description }}</p>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #0d6efd;">Información Adicional</h3>
            <p><span class="label">Fecha de solicitud:</span><span class="value">{{ $repair->created_at->format('d/m/Y H:i') }}</span></p>
            @if($repair->device_image)
            <p><span class="label">Imagen adjunta:</span><span class="value">Sí (ver adjunto)</span></p>
            @endif
        </div>
        
        <p style="margin-top: 20px;">
            <strong>Acción requerida:</strong> Por favor, revisa esta solicitud y contacta al cliente para coordinar la reparación.
        </p>
    </div>
    
    <div class="footer">
        <p style="margin: 0;">Este es un correo automático de DigitalXpress</p>
        <p style="margin: 5px 0 0 0;">No respondas a este correo. Para contactar al cliente, usa el email proporcionado arriba.</p>
    </div>
</body>
</html>

