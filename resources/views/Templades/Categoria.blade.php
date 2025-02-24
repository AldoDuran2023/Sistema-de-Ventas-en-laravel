@extends('dashboard')

@section('title', 'Categorias')

@section('content')
    <!-- Estructura -->
    <div class="card w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Categorias</h5>
            <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#categoriaModal">
                <i class="fa-solid fa-plus"></i> Nueva Categoria
            </button>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table" id="tablaCategoria">
                    <thead>
                        <tr>
                        <th scope="col">id</th>
                            <th scope="col">Categoria</th>
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
    <div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoriaModalLabel">Datos de la Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="categoriaForm">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="marca" class="form-label">Nombre de la categoria</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" required>
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
            const categoriaModal = new bootstrap.Modal(document.getElementById('categoriaModal'));

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Variable para saber el id
            let categoriaId = null;

            // Cargamos la tabla
            let table = $('#tablaCategoria').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                ajax: {
                    url: '/categorias/list',
                    type: 'GET',
                    dataSrc: 'data', // Indica que los datos están dentro de la clave "data"
                },
                columns: [
                    {data: 'id'},
                    { data: 'nombre_categoria' }, // Columna para el nombre de la marca
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}" data-nombre="${row.nombre_categoria}">Editar</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Eliminar</button>
                            `;
                        }
                    }
                ]
            });

            // Evento para abrir el modal en modo edición
            $('#tablaCategoria').on('click', '.edit-btn', function () {
                categoriaId = $(this).data('id');
                let categoriaNombre = $(this).data('nombre');

                $('#categoria').val(categoriaNombre);
                categoriaModal.show(); // Corregido: era escuelaModal.show()
            });

            // Enviar datos desde el modal
            $('#categoriaForm').submit(function(e) { // Corregido: era $('#marcaModal').submit()
                e.preventDefault();
                let categoria_value = $('#categoria').val();

                // Vemos que tipo de petición se realiza
                let url = categoriaId ? `/categorias/list/${categoriaId}` : `/categorias/list`; 
                let method = categoriaId ? 'PUT' : 'POST';

                let formData = {
                    nombre_categoria: categoria_value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                if(categoriaId){
                    formData._method = 'PUT';
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response){
                        $('#categoria').val('');
                        categoriaModal.hide();
                        $('.modal-backdrop').remove();
                        table.ajax.reload();
                        Toast.fire({
                            icon: 'success',
                            title: categoriaId ? 'Categoria actualizada correctamente' : 'Categoria agregada correctamente'
                        });
                        categoriaId = null;
                    },
                    error: function(xhr){
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al guardar la Categoria'
                        });
                    }
                });
            });

            // Limpiar el formulario cuando se cierra el modal
            $('#categoriaModal').on('hidden.bs.modal', function () {
                $('#categoriaForm')[0].reset();
                categoriaId = null;
            });

            // Falta implementar el botón eliminar
            $('#tablaCategoria').on('click', '.delete-btn', function() {
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
                            url: `/categorias/list/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                _method: 'DELETE'
                            },
                            success: function() {
                                table.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Categoria eliminada correctamente'
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error al eliminar la Categoria'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>


@endsection