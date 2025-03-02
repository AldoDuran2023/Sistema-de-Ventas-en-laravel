@extends('dashboard')

@section('title', 'Usuarios')

@section('content')

    <!-- Estructura -->
    <div class="card w-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Usuarios</h5>
            <button class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#usuarioModal">
                <i class="fa-solid fa-plus"></i> Registrar Nuevo Usuario
            </button>
        </div>
        <div class="card-body">
            <div class="table">
                <table class="table" id="tablaUsuario">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Rol</th>
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
    <div class="modal fade" id="usuarioModal" tabindex="-1" aria-labelledby="usuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usuarioModalLabel">Datos del usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="usuarioForm">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Usuario</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="rol" class="form-label">Rol</label>
                                    <select name="rol" id="rol" class="form-control">
                                        <option value="">Seleccione el rol del usuario</option>
                                        <option value="admin">Administrador</option>
                                        <option value="empleado">Empleado</option>
                                    </select>
                                </div>
                            </div>
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
<script src="{{ asset('js/modulos.js') }}"></script>

<script>
    $(document).ready(function () {
        const usuarioModal = new bootstrap.Modal(document.getElementById('usuarioModal'));

        // Variable para saber el id
        let usuarioId = null;

        // Cargamos la tabla
        let table = $('#tablaUsuario').DataTable({
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
            ajax: {
                url: '/usuarios/list',
                type: 'GET',
                dataSrc: '', 
            },
            columns: [
                { data: 'id'},
                { data: 'name' },
                { data: 'email' },
                { data: 'rol' }, 
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return `
                            <button class="btn btn-sm btn-warning edit-btn" 
                                data-id="${row.id}" 
                                data-name="${row.name}" 
                                data-email="${row.email}" 
                                data-password="${row.password}" 
                                data-rol="${row.rol}" >
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Eliminar</button>
                        `;
                    }
                }
            ]
        });

        submitAjaxForm(
            '#usuarioForm',         
            usuarioModal,           
            table,                
            '/usuarios/list',       
            () => usuarioId,        
            {
                'name': '#name',
                'email': '#email',
                'password': '#password',
                'rol': '#rol'
            }, 
            { create: 'Usuario agregada correctamente', update: 'Usuario actualizada correctamente' } // Mensajes de éxito
        );

        // Evento para abrir el modal en modo edición
        $('#tablaUsuario').on('click', '.edit-btn', function (event) {
            // Definir las variables de datos
            let dataVariables = {
                name: 'name',
                email: 'email',
                password: 'password',
                rol: 'rol'
            };

            updateSelect('rol', dataVariables.rol);

            // Llamar a la función y obtener el ID del proveedor
            usuarioId = openEditModal(event, usuarioModal, dataVariables);
            console.log("ID del usuario seleccionado:", productoId);
        });

        deleteEntity(
            table,          // La instancia de DataTables
            '#tablaUsuario',       // Selector de la tabla
            '.delete-btn',       // Botón de eliminación
            '/usuarios/list',      // URL base para eliminar
            'Usuario eliminada correctamente',  // Mensaje de éxito
            'Error al eliminar el Usuario'      // Mensaje de error
        );
    });
</script>
@endsection