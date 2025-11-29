<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto</title>
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
            background-color: #0dcaf0;
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
            border-left: 4px solid #0dcaf0;
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
        .message-box {
            background-color: white;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #0dcaf0;
            border-radius: 4px;
            white-space: pre-wrap;
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
        <h1>📧 Nuevo Mensaje de Contacto</h1>
        <p style="margin: 0;">Asunto: <strong>{{ $contactData['subject'] ?? 'Sin asunto' }}</strong></p>
    </div>
    
    <div class="content">
        <p>Se ha recibido un nuevo mensaje desde el formulario de contacto de DigitalXpress.</p>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #0dcaf0;">Información del Remitente</h3>
            <p><span class="label">Nombre:</span><span class="value"><strong>{{ $contactData['name'] }}</strong></span></p>
            <p><span class="label">Email:</span><span class="value"><a href="mailto:{{ $contactData['email'] }}" style="color: #0dcaf0; text-decoration: none;">{{ $contactData['email'] }}</a></span></p>
            @if(isset($contactData['is_authenticated']) && $contactData['is_authenticated'])
            <p><span class="label">Tipo:</span><span class="value">
                <span style="background-color: #198754; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: bold;">
                    ✓ Usuario Registrado
                </span>
            </span></p>
            @if(isset($contactData['user_id']))
            <p><span class="label">ID de Usuario:</span><span class="value">#{{ $contactData['user_id'] }}</span></p>
            @endif
            @else
            <p><span class="label">Tipo:</span><span class="value">
                <span style="background-color: #6c757d; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px;">
                    Visitante
                </span>
            </span></p>
            @endif
            <p><span class="label">Asunto:</span><span class="value"><strong>{{ $contactData['subject'] ?? 'Sin asunto' }}</strong></span></p>
            @if(isset($contactData['priority']))
            <p><span class="label">Prioridad:</span><span class="value">
                @if($contactData['priority'] == 'high')
                <span style="background-color: #dc3545; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: bold;">
                    🔴 ALTA - Urgente
                </span>
                @elseif($contactData['priority'] == 'medium')
                <span style="background-color: #ffc107; color: #000; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: bold;">
                    🟡 MEDIA - Problema técnico
                </span>
                @else
                <span style="background-color: #198754; color: white; padding: 4px 10px; border-radius: 3px; font-size: 12px; font-weight: bold;">
                    🟢 BAJA - Consulta general
                </span>
                @endif
            </span></p>
            @endif
            <p><span class="label">Fecha y Hora:</span><span class="value">{{ now()->format('d/m/Y H:i:s') }}</span></p>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #0dcaf0;">Mensaje del Usuario</h3>
            <div class="message-box" style="background-color: #ffffff; border: 1px solid #dee2e6; padding: 20px; border-radius: 5px; font-size: 14px; line-height: 1.8;">
                {!! nl2br(e($contactData['message'])) !!}
            </div>
        </div>
        
        <p style="margin-top: 20px;">
            <strong>Acción requerida:</strong> Por favor, responde a este mensaje lo antes posible usando el email del remitente: 
            <strong>{{ $contactData['email'] }}</strong>
        </p>
        
        <div style="background-color: #e7f3ff; padding: 15px; border-left: 4px solid #0dcaf0; margin-top: 20px; border-radius: 4px;">
            <p style="margin: 0;">
                <strong>💡 Para responder:</strong> Haz clic en "Responder" en tu cliente de correo o envía un correo a 
                <a href="mailto:{{ $contactData['email'] }}" style="color: #0dcaf0; font-weight: bold;">{{ $contactData['email'] }}</a>
            </p>
        </div>
    </div>
    
    <div class="footer">
        <p style="margin: 0;">Este es un correo automático de DigitalXpress</p>
        <p style="margin: 5px 0 0 0;">Para responder, usa el email del remitente proporcionado arriba.</p>
    </div>
</body>
</html>

