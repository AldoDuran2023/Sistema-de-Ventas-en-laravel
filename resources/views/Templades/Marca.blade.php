@extends('dashboard')

@section('title', 'Marcas')

@section('content')
    <!-- Estructura -->
    <div class="card w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Marcas</h5>
            <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#marcaModal">
                <i class="fa-solid fa-plus"></i> Nueva Marca
            </button>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table" id="tablaMarca">
                    <thead>
                        <tr>
                        <th scope="col">id</th>
                            <th scope="col">Marca</th>
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
    <div class="modal fade" id="marcaModal" tabindex="-1" aria-labelledby="marcaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="marcaModalLabel">Datos de la Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="marcaForm">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="marca" class="form-label">Nombre de la marca</label>
                            <input type="text" class="form-control" id="marca" name="marca" required>
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
            const marcaModal = new bootstrap.Modal(document.getElementById('marcaModal'));

            // Variable para saber el id
            let marcaId = null;

            // Cargamos la tabla
            let table = $('#tablaMarca').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                ajax: {
                    url: '/marcas/list',
                    type: 'GET',
                    dataSrc: 'data', 
                },
                columns: [
                    {data: 'id', title: 'id'},
                    { data: 'nombre_marca', title: 'Marca' }, // Columna para el nombre de la marca
                    {
                        data: 'id',
                        title: 'Acciones',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}" data-nombre="${row.nombre_marca}">Editar</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Eliminar</button>
                            `;
                        }
                    }
                ]
            });

            $('#tablaMarca').on('click', '.edit-btn', function (event) {
                // Llamamos a la función y obtenemos el ID
                marcaId = openEditModal(event, marcaModal, {
                    'marca': 'nombre'
                });
                console.log("ID de marca seleccionada:", marcaId);
            });

            

            // Enviar datos desde el modal

            submitAjaxForm(
                '#marcaForm',         // Selector del formulario
                marcaModal,           // Objeto del modal
                table,                // Objeto DataTable
                '/marcas/list',       // URL base
                () => marcaId,        // Función que devuelve el ID actual
                { nombre_marca: '#marca' }, // Campos dinámicos { campo: selector }
                { create: 'Marca agregada correctamente', update: 'Marca actualizada correctamente' } // Mensajes de éxito
            );

            // Limpiar el formulario cuando se cierra el modal
            $('#marcaModal').on('hidden.bs.modal', function () {
                $('#marcaForm')[0].reset();
                marcaId = null;
            });

            // implementar el botón eliminar
            deleteEntity(
                table,          // La instancia de DataTables
                '#tablaMarca',       // Selector de la tabla
                '.delete-btn',       // Botón de eliminación
                '/marcas/list',      // URL base para eliminar
                'Marca eliminada correctamente',  // Mensaje de éxito
                'Error al eliminar la marca'      // Mensaje de error
            );

        });
        
    </script>


@endsection