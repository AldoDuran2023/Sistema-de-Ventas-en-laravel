<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Compra</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-logo {
            max-height: 60px;
        }
        .purchase-header {
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        @media print {
            .container {
                width: 100%;
                max-width: 100%;
            }
            body {
                font-size: 14px;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container bg-white shadow p-4 my-4 rounded">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="fw-bold text-primary">Reporte de Compra #{{ $compra->id }}</h2>
                <hr class="my-3">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Datos del Proveedor</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Proveedor:</span>
                                <span>{{ $compra->proveedor->nombre }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Correo:</span>
                                <span>{{ $compra->proveedor->correo }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Teléfono:</span>
                                <span>{{ $compra->proveedor->telefono }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="fw-bold">Dirección:</span>
                                <span>{{ $compra->proveedor->direccion }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Detalles de la Compra</h5>
                    </div>
                    <div class="card-body">
                        <div class="purchase-header p-3 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Fecha:</strong></p>
                                    <p class="fs-5">{{ $compra->fecha }}</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-1"><strong>Total:</strong></p>
                                    <p class="fs-4 text-primary">S/. {{ number_format($compra->total, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Lista de Productos</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Precio Unitario</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if ($compra->detalles && count($compra->detalles) > 0)
                                    @foreach ($compra->detalles as $detalle)
                                        <tr>
                                            <td>{{ $detalle->producto->nombre }}</td>
                                            <td class="text-center">{{ $detalle->cantidad }}</td>
                                            <td class="text-end">S/. {{ number_format($detalle->precio_compra, 2) }}</td>
                                            <td class="text-end">S/. {{ number_format($detalle->cantidad * $detalle->precio_compra, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">No hay productos en esta compra.</td>
                                    </tr>
                                @endif
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold">S/. {{ number_format($compra->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center text-muted">
                <p class="small">Documento generado el {{ date('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>