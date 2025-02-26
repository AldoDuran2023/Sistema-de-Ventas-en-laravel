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

    <script src="{{ asset('js/modulos.js') }}"></script>

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
            $('#tablaCategoria').on('click', '.edit-btn', function (event) {

                categoriaId = openEditModal(event, categoriaModal, {
                    'categoria': 'nombre'
                });
                console.log("ID de marca seleccionada:", categoriaId);
            });

            // Enviar datos desde el modal
            submitAjaxForm(
                '#categoriaForm',         // Selector del formulario
                categoriaModal,           // Objeto modal
                table,                    // Objeto DataTable
                '/categorias/list',       // URL base para la API
                () => categoriaId,        // Función para obtener el ID (o variable directa)
                { nombre_categoria: '#categoria' },  // Campos del formulario (clave -> selector)
                { create: 'Categoría agregada correctamente', update: 'Categoría actualizada correctamente' } // Mensajes de éxito
            );


            // Limpiar el formulario cuando se cierra el modal
            $('#categoriaModal').on('hidden.bs.modal', function () {
                $('#categoriaForm')[0].reset();
                categoriaId = null;
            });

            // Falta implementar el botón eliminar
            deleteEntity(
                table,
                '#tablaCategoria',
                '.delete-btn',
                '/categorias/list',
                'Categoria eliminada correctamente', 
                'Error al eliminar la categoria'
            );
        });
    </script>


@endsection