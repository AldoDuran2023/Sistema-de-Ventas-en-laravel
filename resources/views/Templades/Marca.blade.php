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

    <script>
        $(document).ready(function () {
            const marcaModal = new bootstrap.Modal(document.getElementById('marcaModal'));

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Variable para saber el id
            let marcaId = null;

            // Cargamos la tabla
            let table = $('#tablaMarca').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
                ajax: {
                    url: '/marcas/list',
                    type: 'GET',
                    dataSrc: 'data', // Indica que los datos están dentro de la clave "data"
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

            // Evento para abrir el modal en modo edición
            $('#tablaMarca').on('click', '.edit-btn', function () {
                marcaId = $(this).data('id');
                let marcaNombre = $(this).data('nombre');

                $('#marca').val(marcaNombre);
                marcaModal.show(); // Corregido: era escuelaModal.show()
            });

            // Enviar datos desde el modal
            $('#marcaForm').submit(function(e) { // Corregido: era $('#marcaModal').submit()
                e.preventDefault();
                let marca_value = $('#marca').val();

                // Vemos que tipo de petición se realiza
                let url = marcaId ? `/marcas/list/${marcaId}` : `/marcas/list`; // Corregido: reemplazado {id} con marcaId
                let method = marcaId ? 'PUT' : 'POST';

                let formData = {
                    nombre_marca: marca_value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                if(marcaId){
                    formData._method = 'PUT';
                }

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response){
                        $('#marca').val('');
                        marcaModal.hide();
                        $('.modal-backdrop').remove();
                        table.ajax.reload();
                        Toast.fire({
                            icon: 'success',
                            title: marcaId ? 'Marca actualizada correctamente' : 'Marca agregada correctamente'
                            // Corregido: era marca ? ... pero debe ser marcaId ?
                        });
                        marcaId = null;
                    },
                    error: function(xhr){
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al guardar la Marca'
                        });
                    }
                });
            });

            // Limpiar el formulario cuando se cierra el modal
            $('#marcaModal').on('hidden.bs.modal', function () {
                $('#marcaForm')[0].reset();
                marcaId = null;
            });

            // Falta implementar el botón eliminar
            $('#tablaMarca').on('click', '.delete-btn', function() {
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
                            url: `/marca/list/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                _method: 'DELETE'
                            },
                            success: function() {
                                table.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Marca eliminada correctamente'
                                });
                            },
                            error: function() {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error al eliminar la marca'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>


@endsection