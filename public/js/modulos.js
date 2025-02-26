function openEditModal(event, modalObject, dataVariables) {
    const btn = $(event.currentTarget);// Obtenemos el botón que disparó el evento
    const id = btn.data('id');// Capturamos el ID
    for (let varName in dataVariables) { // Llenamos los valores
        let dataAttr = dataVariables[varName];
        let value = btn.data(dataAttr);
        $(`#${varName}`).val(value);
    }
    modalObject.show();// Mostramos el modal
    return id;// Retornamos el ID
}

function submitAjaxForm(formSelector, modalObject, tableObject, urlBase, getEntityId, dataVariables, successMessages) {
    $(formSelector).submit(function(event) {
        event.preventDefault();

        // Obtener el ID de la entidad (llamar a la función en caso de ser dinámica)
        let entityId = typeof getEntityId === 'function' ? getEntityId() : getEntityId;

        // Capturar los valores dinámicos
        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        for (let varName in dataVariables) {
            formData[varName] = $(dataVariables[varName]).val();
        }

        // Verificar si es actualización o creación
        let url = entityId ? `${urlBase}/${entityId}` : urlBase;
        let method = entityId ? 'PUT' : 'POST';

        if (entityId) {
            formData._method = 'PUT'; // Necesario para Laravel cuando se usa 'POST' en formularios HTML
        }

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function(response) {
                console.log("✅ Respuesta del servidor:", response);

                // Limpiar formulario y cerrar modal
                $(formSelector)[0].reset();
                modalObject.hide();
                $('.modal-backdrop').remove();
                
                if (tableObject) tableObject.ajax.reload();

                // Mensaje de éxito
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: entityId ? successMessages.update : successMessages.create
                });

                // Resetear el ID después de guardar
                if (typeof getEntityId === 'function') {
                    getEntityId(null); // Resetear la variable si es función
                }
            },
            error: function(xhr) {
                console.log("❌ Error en la petición:", xhr.responseJSON);

                Toast.fire({
                    icon: 'error',
                    title: 'Error al procesar la solicitud'
                });
            }
        });
    });
}



function deleteEntity(tableInstance, tableSelector, buttonSelector, urlBase, successMessage, errorMessage) {
    $(tableSelector).on('click', buttonSelector, function() {
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
                    url: `${urlBase}/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function() {
                        tableInstance.ajax.reload(); // Ahora tableInstance está definido

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'success',
                            title: successMessage
                        });
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: errorMessage
                        });
                    }
                });
            }
        });
    });
}

