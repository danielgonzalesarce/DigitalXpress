<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Pedido</title>
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
            background-color: #198754;
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
            border-left: 4px solid #198754;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #198754;
            color: white;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #198754;
            margin-top: 10px;
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
        <h1>🛒 Nuevo Pedido Recibido</h1>
        <p style="margin: 0;">Número de Pedido: <strong>{{ $order->order_number }}</strong></p>
    </div>
    
    <div class="content">
        <p>Se ha recibido un nuevo pedido en DigitalXpress.</p>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #198754;">Información del Cliente</h3>
            <p><span class="label">Nombre:</span><span class="value">{{ $order->customer_name }}</span></p>
            <p><span class="label">Email:</span><span class="value">{{ $order->customer_email }}</span></p>
            <p><span class="label">Teléfono:</span><span class="value">{{ $order->customer_phone }}</span></p>
            @if($order->user)
            <p><span class="label">Usuario registrado:</span><span class="value">{{ $order->user->name }} ({{ $order->user->email }})</span></p>
            @endif
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #198754;">Dirección de Envío</h3>
            <p>{{ $order->shipping_address['address'] ?? 'No especificada' }}</p>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #198754;">Productos Pedidos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                <p style="margin: 0;">Total del Pedido: ${{ number_format($order->total_amount, 2) }}</p>
            </div>
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #198754;">Información de Pago</h3>
            <p><span class="label">Método de pago:</span><span class="value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span></p>
            <p><span class="label">Estado del pago:</span><span class="value">{{ ucfirst($order->payment_status) }}</span></p>
            @if($order->transaction_id)
            <p><span class="label">ID de transacción:</span><span class="value">{{ $order->transaction_id }}</span></p>
            @endif
        </div>
        
        <div class="info-box">
            <h3 style="margin-top: 0; color: #198754;">Información Adicional</h3>
            <p><span class="label">Fecha del pedido:</span><span class="value">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
            <p><span class="label">Estado:</span><span class="value">{{ ucfirst($order->status) }}</span></p>
            @if($order->notes)
            <p><span class="label">Notas:</span><span class="value">{{ $order->notes }}</span></p>
            @endif
        </div>
        
        <p style="margin-top: 20px;">
            <strong>Acción requerida:</strong> Por favor, procesa este pedido y prepara el envío.
        </p>
    </div>
    
    <div class="footer">
        <p style="margin: 0;">Este es un correo automático de DigitalXpress</p>
        <p style="margin: 5px 0 0 0;">No respondas a este correo. Para contactar al cliente, usa el email proporcionado arriba.</p>
    </div>
</body>
</html>

