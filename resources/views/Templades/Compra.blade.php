@extends('dashboard')

@section('title', 'Compras')

@section('content')

    <div class="card w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Compras</h5>
            <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#compraModal">
                <i class="fa-solid fa-plus"></i> Nueva Compra
            </button>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table" id="tablaCompra">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Proveedor</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Total</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="compraModal" tabindex="-1" aria-labelledby="compraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="compraModalLabel">Registrar Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="compraForm">
                    <div class="modal-body">
                        @csrf
                        <!-- Selección del proveedor -->
                        <div class="mb-3">
                            <label for="proveedor" class="form-label">Proveedor</label>
                            <select name="proveedor" id="proveedor" class="form-control">
                                <option value="">Seleccione al proveedor</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sección para agregar productos -->
                        <div class="row">
                            <div class="col">
                                <label for="producto" class="form-label">Seleccione el producto</label>
                                <select name="producto" id="producto" class="form-control">
                                    <option value="">Seleccione un producto</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                    @endforeach
                            </select>
                                </select>
                            </div>
                            <div class="col">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" class="form-control">
                            </div>
                            <div class="col">
                                <label for="precio_compra" class="form-label">Precio de Compra</label>
                                <input type="number" id="precio_compra" name="precio_compra" class="form-control">
                            </div>
                            <div class="col d-flex align-items-end">
                                <button class="btn btn-primary w-100" id="btnAgregarProducto">Agregar</button>
                            </div>
                        </div>

                        <br>
                        <!-- Tabla de detalles de la compra -->
                        <div class="table-responsive">
                            <table class="table" id="tablaDetalleCompra">
                                <thead>
                                    <tr>
                                        <th scope="col">Producto</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <!-- Total de la compra -->
                        <div class="text-end">
                            <h4>Total: <span id="totalCompra">0.00</span> </h4>
                            <input type="hidden" name="total" id="total" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar Compra</button>
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
        // Inicialización del modal
        const compraModal = new bootstrap.Modal(document.getElementById('compraModal'));
        let compraId = null;

        // Variables para gestionar productos
        let totalCompra = 0;

        // Función para obtener/establecer el ID de compra
        function setCompraId(id = null) {
            if (id !== null) {
                compraId = id;
            }
            return compraId;
        }

        // Inicializar DataTable
        let table = $('#tablaCompra').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            ajax: {
                url: '/compras/list',
                type: 'GET',
                dataSrc: '',
            },
            columns: [
                { data: 'id' },
                { data: 'proveedor.nombre' },
                { data: 'fecha' },
                { data: 'total', 
                render: function(data) {
                    return parseFloat(data).toFixed(2);
                }
                },
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return `<a href="/compras/reporte/${row.id}" target="_blank" class="btn btn-sm btn-success">Ver Pedido</a>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Eliminar</button>`;
                    }
                }
            ]
        });

        // Configurar eliminación de compras
        deleteEntity(
            table,
            '#tablaCompra',
            '.delete-btn',
            '/compras',
            'Compra eliminada correctamente',
            'No se pudo eliminar la compra'
        );

        // Agregar producto a la tabla temporal
        $('#btnAgregarProducto').click(function(e) {
            e.preventDefault();
            
            let productoId = $('#producto').val();
            let productoNombre = $('#producto option:selected').text();
            let cantidad = parseFloat($('#cantidad').val());
            let precio = parseFloat($('#precio_compra').val());
            
            if (!productoId || isNaN(cantidad) || isNaN(precio) || cantidad <= 0 || precio <= 0) {
                Swal.fire('Error', 'Todos los campos son requeridos y deben ser valores positivos', 'error');
                return;
            }
            
            let subtotal = cantidad * precio;
            
            // Agregar a la tabla temporal
            $('#tablaDetalleCompra tbody').append(`
                <tr>
                    <td>${productoNombre}<span class="d-none producto-id">${productoId}</span></td>
                    <td class="producto-cantidad">${cantidad}</td>
                    <td class="producto-precio">${precio.toFixed(2)}</td>
                    <td>${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm btn-eliminar-producto">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            
            // Actualizar total
            totalCompra += subtotal;
            $('#totalCompra').text(totalCompra.toFixed(2));
            $('#total').val(totalCompra.toFixed(2));
            
            // Limpiar campos de producto pero mantener el proveedor seleccionado
            $('#producto').val(''); $('#cantidad').val(''); $('#precio_compra').val(''); $('#producto').focus();
        });

        // Eliminar producto de la tabla temporal
        $(document).on('click', '.btn-eliminar-producto', function() {
            let fila = $(this).closest('tr');
            let subtotal = parseFloat(fila.find('td:eq(3)').text());
            
            // Actualizar total
            totalCompra -= subtotal;
            $('#totalCompra').text(totalCompra.toFixed(2));
            $('#total').val(totalCompra.toFixed(2));
            
            // Eliminar fila
            fila.remove();
        });

        // Limpiar modal al cerrarlo
        $('#compraModal').on('hidden.bs.modal', function() {
            $('#compraForm')[0].reset();
            $('#tablaDetalleCompra tbody').empty();
            totalCompra = 0;
            $('#totalCompra').text('0.00');
            $('#total').val('0');
            compraId = null;
        });

        // Ver detalle de compra (implementar según necesites)
        $('#tablaCompra').on('click', '.view-btn', function() {
            let id = $(this).data('id');
            // Aquí puedes implementar la lógica para ver detalle
            // Por ejemplo, redireccionar a una página de detalle
            window.location.href = `/compras/detalle/${id}`;
        });

        // Enviar Compra y Productos
        $('#compraForm').submit(function (event) {
            event.preventDefault();
            
            // Verificaciones
            if ($('#tablaDetalleCompra tbody tr').length === 0) {
                Swal.fire('Error', 'Debe agregar al menos un producto', 'error');
                return;
            }
            
            if (!$('#proveedor').val()) {
                Swal.fire('Error', 'Debe seleccionar un proveedor', 'error');
                return;
            }

            // Datos de la compra
            let formData = {
                id_proveedor: $('#proveedor').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            // Guardar compra
            $.ajax({
                url: '/compras',
                method: 'POST',
                data: formData,
                success: function (response) {
                    let compraId = response.compra_id;
                    
                    // Preparar productos para enviar
                    let productos = [];
                    $('#tablaDetalleCompra tbody tr').each(function () {
                        let producto = {
                            id: $(this).find('.producto-id').text(),
                            cantidad: $(this).find('.producto-cantidad').text(),
                            precio: $(this).find('.producto-precio').text()
                        };
                        productos.push(producto);
                    });

                    // Guardar detalle de compra
                    $.ajax({
                        url: '/detalle-compras',
                        method: 'POST',
                        data: {
                            id_compra: compraId,
                            productos: productos,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Compra y productos guardados correctamente',
                                confirmButtonColor: '#3085d6'
                            });
                            
                            table.ajax.reload();
                            compraModal.hide();
                            
                            // Limpiar formulario
                            $('#compraForm')[0].reset();
                            $('#tablaDetalleCompra tbody').empty();
                            totalCompra = 0;
                            $('#totalCompra').text('0.00');
                            $('#total').val('0');
                        },
                        error: function (xhr) {
                            console.log("Error en detalle compra:", xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudieron guardar los productos',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    });
                },
                error: function (xhr) {
                    console.log("Error en compra:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo registrar la compra',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        });

        // Editar compra (si lo necesitas implementar)
        $('#tablaCompra').on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            
            $.ajax({
                url: `/compras/${id}`,
                method: 'GET',
                success: function(response) {
                    compraId = id;
                    $('#proveedor').val(response.id_proveedor);
                    
                    // Cargar detalle de la compra si lo necesitas
                    
                    compraModal.show();
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar la información de la compra', 'error');
                }
            });
        });
    });
    </script>


@endsection