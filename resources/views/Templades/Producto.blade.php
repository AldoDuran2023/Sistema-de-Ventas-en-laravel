@extends('dashboard')

@section('title', 'Productos')

@section('content')

    <div class="card w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Productos</h5>
            <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#productoModal">
                <i class="fa-solid fa-plus"></i> Nuevo Producto
            </button>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table" id="tablaProducto">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripcion</th>
                            <th scope="col">imagen</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Precio Venta</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Estado</th>
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
    <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="productoModalLabel">Datos del producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productoForm">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <label for="nombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ingrese el nombre del producto">
                        </div><br>
                        <div class="row">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Describa el producto"></textarea>
                        </div><br>
                        <div class="row">
                            <div class="col">
                                <label for="marca" class="form-label">Marca</label>
                                <select class="form-control" id="marca" name="marca">
                                    <option value="">Seleccione una categoria</option>
                                        @foreach($marcas as $marca)
                                            <option value="{{ $marca->id }}">{{ $marca->nombre_marca }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="categoria" class="form-label">Categoria</label>
                                <select class="form-control" id="categoria" name="categoria">
                                    <option value="">Seleccione una categoria</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre_categoria }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col">
                                <label for="precio_venta" class="form-label">Precio de venta</label>
                                <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" placeholder="Ingrese el precio de venta unitaria" min="0.01">
                            </div>                      
                            <div class="col">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" placeholder="Ingrese el stock" min="1">
                            </div>
                        </div> <br>
                        <div class="row">
                            <label for="imagen" class="form-label">Imagen del producto</label>
                            <img id="previewImagen" src="" class="w-25">
                            <input type="file" class="form-control" id="imagen" name="imagen">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/modulos.js') }}"></script>

    <script>
        $(document).ready(function () {
            const productoModal = new bootstrap.Modal(document.getElementById('productoModal'));

            // Variable para saber el id
            let productoId = null;

            // Cargamos la tabla
            let table = $('#tablaProducto').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                ajax: {
                    url: '/productos/list',
                    type: 'GET',
                    dataSrc: '', 
                },
                columns: [
                    { data: 'id'},
                    { data: 'nombre' }, 
                    { data: 'descripcion' }, 
                    { 
                        data: 'imagen',
                        render: function (data, type, row) {
                            let imageUrl = data ? `/imagen/productos/${data}` : `/imagen/productos/producto.png`;
                            return `<img src="${imageUrl}" width="70">`;
                        }
                    }, 
                    { data: 'marca.nombre_marca' }, 
                    { data: 'categoria.nombre_categoria' }, 
                    { data: 'precio_venta' }, 
                    { data: 'stock' }, 
                    {
                        data: 'estado',
                        render: function (data, type, row) {
                            let badgeClass = (data === 'activo') ? 'badge bg-success' : 'badge bg-danger';
                            return `<span class="${badgeClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning edit-btn" 
                                    data-id="${row.id}" 
                                    data-nombre="${row.nombre}" 
                                    data-descripcion="${row.descripcion}" 
                                    data-imagen="${row.imagen}" 
                                    data-marca-id="${row.marca.id}" 
                                    data-categoria-id="${row.categoria.id}"
                                    data-precio_venta="${row.precio_venta}" 
                                    data-stock="${row.stock}" >
                                    Editar
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Eliminar</button>
                            `;
                        }
                    }
                ]
            });

            // Preview de imagen cuando se selecciona un archivo
            $('#imagen').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImagen').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Evento para abrir el modal en modo edición
            $('#tablaProducto').on('click', '.edit-btn', function (event) {
                // Definir las variables de datos
                let dataVariables = {
                    nombre: 'nombre',
                    descripcion: 'descripcion',
                    marca: 'marca-id',
                    categoria: 'categoria-id',
                    precio_venta: 'precio_venta',
                    stock: 'stock'
                };

                updateSelect('marca', dataVariables.marca);
                updateSelect('categoria', dataVariables.categoria);

                // Llamar a la función y obtener el ID del proveedor
                productoId = openEditModal(event, productoModal, dataVariables);
                console.log("ID del producto seleccionado:", productoId);

                // Manejo especial para la imagen
                let imagen = $(this).data('imagen');
                let imageUrl = imagen ? `/imagen/productos/${imagen}` : `/imagen/productos/producto.png`;
                $('#previewImagen').attr('src', imageUrl);
            });

            // Evento para abrir el modal en modo creación
            $('[data-bs-target="#productoModal"]').on('click', function() {
                isEditing = false;
                proveedorId = null;
                $('#productoForm')[0].reset();
                $('#previewImagen').attr('src', '');
                $('#imagen').prop('required', false);
                $('#productoModalLabel').text('Datos del Producto');
            });

            //enviar los datos desde el modal
            submitAjaxFormFile(
                '#productoForm',
                productoModal,
                table,
                '/productos/list',
                () => productoId,
                {}, // Ya no es necesario pasar `dataVariables`, pues se captura automáticamente
                { create: 'Producto agregado correctamente', update: 'Producto actualizado correctamente' }
            );

            // Limpiar el formulario cuando se cierra el modal
            $('#productoModal').on('hidden.bs.modal', function () {
                $('#productoForm')[0].reset();
                $('#previewImagen').attr('src', '');
                productoId = null;
                isEditing = false;
                $('#imagen').prop('required', false);
            });

            // Falta implementar el botón eliminar
            deleteEntity(
                table,          // La instancia de DataTables
                '#tablaProducto',       // Selector de la tabla
                '.delete-btn',       // Botón de eliminación
                '/productos/list',      // URL base para eliminar
                'Producto eliminada correctamente',  // Mensaje de éxito
                'Error al eliminar el Producto'      // Mensaje de error
            );
        });
    </script>

@endsection