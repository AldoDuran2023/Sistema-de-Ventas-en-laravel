@extends('dashboard')

@section('title', 'Categorias')

@section('content')
    <!-- Estructura -->
    <div class="card w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Proveedores</h5>
            <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#proveedorModal">
                <i class="fa-solid fa-plus"></i> Nuevo Proveedor
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Añadí esta clase para hacer la tabla más adaptable -->
                <table class="table m-0 w-100" id="tablaProveedor">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Direccion</th>
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
    <div class="modal fade" id="proveedorModal" tabindex="-1" aria-labelledby="proveedorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="proveedorModalLabel">Datos del Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="proveedorForm">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <label for="nombre" class="form-label">Nombre del proveedor</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col">
                                <label for="telefono" class="form-label">Telefono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="direccion" class="form-label">Direccion</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                            <div class="col">
                                <label for="correo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correo" name="correo" required>
                            </div>
                        </div>
                        <div class="row">
                            <label for="imagen" class="form-label">Imagen del proveedor</label>
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

    <script>
        $(document).ready(function () {
            const proveedorModal = new bootstrap.Modal(document.getElementById('proveedorModal'));

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Variable para almacenar el ID del proveedor
            let proveedorId = null;
            let isEditing = false;

            // Inicializar DataTable
            let table = $('#tablaProveedor').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                ajax: {
                    url: '/proveedores/list',
                    type: 'GET',
                    dataSrc: 'data', 
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    { 
                        data: 'imagen',
                        render: function (data, type, row) {
                            let imageUrl = data ? `/imagen/${data}` : `/imagen/default.png`;
                            return `<img src="${imageUrl}" width="70">`;
                        }
                    },
                    { data: 'correo' },
                    { data: 'telefono' },
                    { data: 'direccion' },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning edit-btn"
                                    data-id="${row.id}"
                                    data-nombre="${row.nombre}"
                                    data-telefono="${row.telefono}"
                                    data-direccion="${row.direccion}"
                                    data-correo="${row.correo}"
                                    data-imagen="${row.imagen}">
                                    Editar
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn"
                                    data-id="${row.id}">
                                    Eliminar
                                </button>
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
            $('#tablaProveedor').on('click', '.edit-btn', function () {
                isEditing = true;
                proveedorId = $(this).data('id');
                let nombre = $(this).data('nombre');
                let telefono = $(this).data('telefono');
                let direccion = $(this).data('direccion');
                let correo = $(this).data('correo');
                let imagen = $(this).data('imagen');

                $('#nombre').val(nombre);
                $('#telefono').val(telefono);
                $('#direccion').val(direccion);
                $('#correo').val(correo);
                
                // Mostrar la imagen actual
                let imageUrl = imagen ? `/imagen/${imagen}` : `/imagen/default.png`;
                $('#previewImagen').attr('src', imageUrl);
                
                // Cambiar el requisito de imagen para edición
                $('#imagen').prop('required', false);
                
                // Actualizar el título del modal
                $('#proveedorModalLabel').text('Editar Proveedor');
                
                proveedorModal.show();
            });

            // Evento para abrir el modal en modo creación
            $('[data-bs-target="#proveedorModal"]').on('click', function() {
                isEditing = false;
                proveedorId = null;
                $('#proveedorForm')[0].reset();
                $('#previewImagen').attr('src', '');
                $('#imagen').prop('required', false);
                $('#proveedorModalLabel').text('Datos del Proveedor');
            });

            // Evento para manejar el envío del formulario
            $('#proveedorForm').submit(function (e) {
                e.preventDefault();
                
                // Usar FormData para manejar archivos
                let formData = new FormData(this);
                
                // Definir URL y método HTTP
                let url = proveedorId ? `/proveedores/list/${proveedorId}` : `/proveedores/list`;
                let method = proveedorId ? 'POST' : 'POST'; // Siempre POST con FormData, pero añadir _method para PUT
                
                if (proveedorId) {
                    formData.append('_method', 'PUT');
                }
                
                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    contentType: false, // Necesario para FormData
                    processData: false, // Necesario para FormData
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#proveedorForm')[0].reset();
                        $('#previewImagen').attr('src', '');
                        proveedorModal.hide();
                        table.ajax.reload();
                        
                        Toast.fire({
                            icon: 'success',
                            title: proveedorId ? 'Proveedor actualizado correctamente' : 'Proveedor agregado correctamente'
                        });
                        
                        proveedorId = null;
                        isEditing = false;
                    },
                    error: function (xhr) {
                        let errorMessage = 'Error al guardar el proveedor';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Toast.fire({
                            icon: 'error',
                            title: errorMessage
                        });
                    }
                });
            });

            // Limpiar el formulario cuando se cierra el modal
            $('#proveedorModal').on('hidden.bs.modal', function () {
                $('#proveedorForm')[0].reset();
                $('#previewImagen').attr('src', '');
                proveedorId = null;
                isEditing = false;
                $('#imagen').prop('required', true);
            });

            // Implementación del botón eliminar (corregido para usar la clase correcta)
            $('#tablaProveedor').on('click', '.delete-btn', function () {
                let id = $(this).data('id');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede revertir",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/proveedores/list/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function () {
                                table.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Proveedor eliminado correctamente'
                                });
                            },
                            error: function (xhr) {
                                let errorMessage = 'Error al eliminar el proveedor';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                
                                Toast.fire({
                                    icon: 'error',
                                    title: errorMessage
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>



@endsection