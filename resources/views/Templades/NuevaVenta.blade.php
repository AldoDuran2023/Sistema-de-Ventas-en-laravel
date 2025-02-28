@extends('dashboard')

@section('title','Detalle de Venta')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1>Detalles de la venta #{{ $venta->id }}</h1>
            <button class="btn btn-success ms-auto" id="btnAbrirModal" data-bs-toggle="modal" data-bs-target="#detalleVentaModal">Añadir nuevo Producto</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="tablaProductos">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total:</th>
                        <th id="totalVenta">0.00</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary" id="btnFinalizarVenta" data-venta-id="{{ $venta->id }}">Finalizar Venta</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detalleVentaModal" tabindex="-1" aria-labelledby="detalleVentaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleVentaModalLabel">Añadir Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="detalleVentaForm">
                    <div class="modal-body">
                        @csrf
                        <!-- Campo oculto para el ID de la venta -->
                        <input type="hidden" id="idventa" name="idventa" value="{{ $venta->id }}">
                        <!-- Campo oculto para el precio del producto -->
                        <input type="hidden" id="precio" name="precio" value="">
                        
                        <div class="row">
                            <div class="col">
                                <label for="producto" class="form-label">Seleccione el producto</label>
                                <select name="producto" id="producto" class="form-control">
                                    <option value="">Seleccione un producto</option>
                                    @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                            data-precio="{{ $producto->precio_venta }}"
                                            data-stock="{{ $producto->stock }}">
                                        {{ $producto->nombre }} - ${{ $producto->precio_venta }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')

<script src="{{ asset('js/modulos.js') }}"></script>

<script>
    $(document).ready(function () {
        const detalleVentaModal = new bootstrap.Modal(document.getElementById('detalleVentaModal'));
        const ventaId = $('#idventa').val();// ID de la venta desde el formulario
        const detalleid = null;
        
        // Actualizar el precio oculto cuando se selecciona un producto
        $('#producto').change(function() {
            const selectedOption = $(this).find('option:selected');
            const precio = selectedOption.data('precio');
            const stock = selectedOption.data('stock');
            
            $('#precio').val(precio);
            $('#cantidad').attr('max', stock);
            if (parseInt($('#cantidad').val()) > stock) {
                $('#cantidad').val(stock);
            }
        });
        
        // Cargar la tabla con los detalles de la venta
        let table = $('#tablaProductos').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            ajax: {
                url: `/NuevaVenta/list/${ventaId}`,
                type: 'GET',
                dataSrc: function(json) {
                    // Calcular el total
                    let total = 0;
                    json.forEach(item => {
                        total += parseFloat(item.cantidad * item.precio_unitario);
                    });
                    $('#totalVenta').text(total.toFixed(2));
                    return json;
                }
            },
            columns: [
                {data: 'id'},
                {data: 'producto.nombre'}, // Acceder al nombre del producto a través de la relación
                {data: 'precio_unitario', render: function(data) { return '$' + parseFloat(data).toFixed(2); }},
                {data: 'cantidad'},
                {
                    data: null,
                    render: function(data) {
                        const subtotal = parseFloat(data.cantidad * data.precio_unitario).toFixed(2);
                        return '$' + subtotal;
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">Eliminar</button>
                        `;
                    }
                }
            ]
        });

        //enviar los datos desde el modal
        submitAjaxFormFile(
            '#detalleVentaForm',
            detalleVentaModal,
            table,
            '/NuevaVenta/detalle',
            () => detalleid,
            {}, // Ya no es necesario pasar `dataVariables`, pues se captura automáticamente
            { create: 'Producto agregado correctamente', update: 'Producto actualizado correctamente' }
        );
        
        // implementar el botón eliminar
        deleteEntity(
            table,          // La instancia de DataTables
            '#tablaProductos',       // Selector de la tabla
            '.delete-btn',       // Botón de eliminación
            '/NuevaVenta/detalle',      // URL base para eliminar
            'Producto quitado correctamente',  // Mensaje de éxito
            'Error al quitar el producto'      // Mensaje de error
        );

        // modal de finalziar y redireccionar
        $('#btnFinalizarVenta').click(function() {
            const ventaId = $(this).data('venta-id');
            const url = `/ventas/${ventaId}/finalizar`;
            const urlRedireccion = '{{ route("ventas") }}';
            
            // Obtener el total desde la interfaz (asumiendo que está en un elemento con id="totalVenta")
            const totalVenta = parseFloat($('#totalVenta').text()) || 0;

            // Crear objeto con datos extra
            const datosExtra = {
                total: totalVenta
            };

            Finalizar(url, urlRedireccion, 
            'Venta finalizada', 'La venta se finalizó correctamente.', 
            'Error', 'Error al finalizar la venta', 
            '¿Está seguro?', '¿Desea finalizar esta venta?', 
            datosExtra);
        });

    });
</script>

@endsection