const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

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

function updateImagePreview(element, imgAttr, imgPath, imgDefault, imgSelector) {
    let imagen = $(element).data(imgAttr);
    let imageUrl = imagen ? `${imgPath}/${imagen}` : imgDefault;
    $(`#${imgSelector}`).attr('src', imageUrl);
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
                console.log("Respuesta del servidor:", response);

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
                    getEntityId(null); 
                }
            },
            error: function(xhr) {
                console.log("Error en la petición:", xhr.responseJSON);

                Toast.fire({
                    icon: 'error',
                    title: 'Error al procesar la solicitud'
                });
            }
        });
    });
}

function submitAjaxFormFile(formSelector, modalObject, tableObject, urlBase, getEntityId, dataVariables, successMessages) {
    $(formSelector).submit(function(event) {
        event.preventDefault();

        let entityId = typeof getEntityId === 'function' ? getEntityId() : getEntityId;
        let formData = new FormData(this); // Captura todos los datos del formulario, incluyendo archivos
        
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        if (entityId) {
            formData.append('_method', 'PUT'); // Laravel requiere esto para actualizaciones
        }

        let url = entityId ? `${urlBase}/${entityId}` : urlBase;
        let method = 'POST'; // Siempre 'POST' con FormData, Laravel manejará PUT con _method

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false, // Necesario para FormData
            contentType: false, // Necesario para FormData
            success: function(response) {
                console.log("Respuesta del servidor:", response);
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

                if (typeof getEntityId === 'function') {
                    getEntityId(null);
                }
            },
            error: function(xhr) {
                console.log("Error en la petición:", xhr.responseJSON);

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
        console.log(`Intentando eliminar: ${urlBase}/${id}`);

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
                        tableInstance.ajax.reload(); 

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

function updateSelect(selectId, value) {
    let selectElement = $('#' + selectId);
    console.log(value);
    if (selectElement.length) {
        selectElement.val(value).trigger('change'); // Dispara el evento "change" por si usas Select2 u otro plugin
    } else {
        console.error(`updateSelect: No se encontró el select con id "${selectId}"`);
    }
}

function Finalizar(url, urlRedireccion, tituloExito, mensajeExito, tituloError, mensajeError, tituloConfirmacion, mensajeConfirmacion, datosExtra = {}) {
    Swal.fire({
        title: tituloConfirmacion,
        text: mensajeConfirmacion,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, finalizar'
    }).then((result) => {
        if (result.isConfirmed) {
            let data = {
                _token: $('input[name="_token"]').val(),
                ...datosExtra 
            };

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        Swal.fire(tituloExito, mensajeExito, 'success')
                            .then(() => {
                                window.location.href = urlRedireccion;
                            });
                    } else {
                        Swal.fire(tituloError, mensajeError, 'error');
                    }
                },
                error: function() {
                    Swal.fire(tituloError, 'Error al procesar la solicitud', 'error');
                }
            });
        }
    });
}


