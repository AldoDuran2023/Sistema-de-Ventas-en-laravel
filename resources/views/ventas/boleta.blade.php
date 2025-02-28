<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .boleta-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }
        .boleta-body {
            padding: 15px;
        }
        .boleta-footer {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-radius: 0 0 8px 8px;
        }
        @media print {
            .container {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container bg-white shadow p-4 my-4 rounded">
        <div class="boleta-header text-center">
            <h2 class="fw-bold">Boleta de Venta #{{ $venta->id }}</h2>
        </div>

        <div class="boleta-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Datos del Cliente</h5>
                    <p><strong>ID Cliente:</strong> {{ $venta->id_cliente }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5>Detalles de la Venta</h5>
                    <p><strong>Fecha:</strong> {{ $venta->fecha }}</p>
                    <p><strong>Total:</strong> <span class="fs-4 text-success">S/. {{ number_format($venta->total, 2) }}</span></p>
                </div>
            </div>
            
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Precio Unitario</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td class="text-center">{{ $detalle->cantidad }}</td>
                        <td class="text-end">S/. {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td class="text-end">S/. {{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td class="text-end fw-bold">S/. {{ number_format($venta->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="boleta-footer">
            <p class="small">Gracias por su compra | Fecha: {{ date('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
