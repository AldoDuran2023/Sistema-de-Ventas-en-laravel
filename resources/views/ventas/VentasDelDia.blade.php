<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas del Día</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .reporte-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .reporte-body {
            padding: 15px;
        }
        .reporte-footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container bg-white shadow p-4 my-4 rounded">
        <div class="reporte-header text-center">
            <h2 class="fw-bold">Ventas del Día: {{ $hoy }}</h2>
        </div>

        <div class="reporte-body">
            @if($ventas->isEmpty())
                <p class="text-center text-danger">No hay ventas registradas para hoy.</p>
            @else
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Venta</th>
                            <th>Fecha</th>
                            <th>Total (S/.)</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                            <tr>
                                <td>{{ $venta->id }}</td>
                                <td>{{ $venta->fecha }}</td>
                                <td class="text-end">{{ number_format($venta->total, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/ventas/boleta/' . $venta->id) }}" class="btn btn-info btn-sm">Ver Boleta</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total Ventas:</td>
                            <td class="text-end fw-bold">S/. {{ number_format($totalVentas, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>

        <div class="reporte-footer">
            <p class="small">Reporte generado el {{ date('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
