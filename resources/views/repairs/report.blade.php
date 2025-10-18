<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Reparaciones - DigitalXpress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .user-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .user-info h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item h4 {
            margin: 0;
            color: #007bff;
            font-size: 24px;
        }
        .summary-item p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .repairs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .repairs-table th,
        .repairs-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .repairs-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .repairs-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-in_progress {
            background-color: #17a2b8;
            color: #fff;
        }
        .status-completed {
            background-color: #28a745;
            color: #fff;
        }
        .status-cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            color: #666;
        }
        .no-repairs {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔧 DigitalXpress</h1>
        <p>Reporte de Reparaciones</p>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="user-info">
        <h3>👤 Información del Cliente</h3>
        <p><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        <p><strong>Fecha de Reporte:</strong> {{ date('d/m/Y') }}</p>
    </div>

    @if($repairs->count() > 0)
        <div class="summary">
            <div class="summary-item">
                <h4>{{ $repairs->count() }}</h4>
                <p>Total de Reparaciones</p>
            </div>
            <div class="summary-item">
                <h4>{{ $repairs->where('status', 'pending')->count() }}</h4>
                <p>Pendientes</p>
            </div>
            <div class="summary-item">
                <h4>{{ $repairs->where('status', 'in_progress')->count() }}</h4>
                <p>En Progreso</p>
            </div>
            <div class="summary-item">
                <h4>{{ $repairs->where('status', 'completed')->count() }}</h4>
                <p>Completadas</p>
            </div>
        </div>

        <table class="repairs-table">
            <thead>
                <tr>
                    <th>N° Reparación</th>
                    <th>Dispositivo</th>
                    <th>Problema</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Costo Estimado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repairs as $repair)
                <tr>
                    <td><strong>{{ $repair->repair_number }}</strong></td>
                    <td>
                        <strong>{{ $repair->device_type }}</strong><br>
                        <small>{{ $repair->brand }} {{ $repair->model }}</small>
                    </td>
                    <td>
                        {{ Str::limit($repair->problem_description, 50) }}
                        @if(strlen($repair->problem_description) > 50)
                            ...
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $repair->status }}">
                            {{ $repair->status_text }}
                        </span>
                    </td>
                    <td>{{ $repair->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($repair->estimated_cost)
                            ${{ number_format($repair->estimated_cost, 2) }}
                        @else
                            <em>Por cotizar</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-repairs">
            <h3>📋 Sin Reparaciones Registradas</h3>
            <p>No tienes reparaciones registradas en el sistema.</p>
            <p>Visita nuestro servicio técnico para solicitar una reparación.</p>
        </div>
    @endif

    <div class="footer">
        <p><strong>DigitalXpress - Servicio Técnico Profesional</strong></p>
        <p>📞 Teléfono: +1 (234) 567-890 | 📧 Email: soporte@digitalxpress.com</p>
        <p>🌐 Web: www.digitalxpress.com</p>
        <p><em>Este reporte fue generado automáticamente el {{ date('d/m/Y') }} a las {{ date('H:i:s') }}</em></p>
    </div>
</body>
</html>
