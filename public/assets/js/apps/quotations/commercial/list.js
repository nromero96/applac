document.addEventListener('DOMContentLoaded', function () {


    //onchange event for slect user-select-assigned
    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('user-select-assigned')) {
            var user_id = event.target.value;
            var quotation_id = event.target.getAttribute('data-quotation-id');
            var url = baseurl + '/quotations/' + quotation_id + '/assignuser';

            var data = {
                user_id: user_id,
                quotation_id: quotation_id
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.text(); // Si esperas JSON, usa response.json() aquí
            })
            .then(data => {

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: 'Assigned successfully'
                })

            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error); // Muestra un mensaje de error
                // Aquí podrías mostrar un mensaje de error en la interfaz de usuario si es necesario
            });
        }
    });


    const slider = document.querySelector('.drag-scroll');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        // Verifica si el elemento clicado es un <select>, <a>, u otro interactivo
        if (e.target.tagName === 'SELECT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
            return; // No hacer nada si es un elemento interactivo
        }

        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
        e.preventDefault();  // Evita el comportamiento predeterminado
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();  // Evita el desplazamiento vertical

        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; // Ajusta la velocidad si es necesario
        slider.scrollLeft = scrollLeft - walk;
    });

    const tableContainer = document.querySelector('.drag-scroll');
    const stickyColumn = document.querySelectorAll('.sticky-column');
    tableContainer.addEventListener('scroll', () => {
        if (tableContainer.scrollLeft > 0) {
            stickyColumn.forEach(column => {
                column.classList.add('has-shadow');
            });
        } else {
            stickyColumn.forEach(column => {
                column.classList.remove('has-shadow');
            });
        }
    });


    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', (event) => {
            const svgIcon = document.getElementById('icon' + event.target.id.replace('checkbox', ''));
            svgIcon.classList.toggle('checked', event.target.checked);

            // Enviar solicitud AJAX para guardar el estado
            const quotationId = event.target.value; // Obtiene el ID de la cotización
            const featured = event.target.checked; // true si está marcado, false si no

            fetch(`/quotations/${quotationId}/featured`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ featured }),
            })
            .then(response => response.json())
            .then(data => {
                const tr = event.target.closest('tr');
                // Actualiza la clase de la fila según el estado de 'featured'
                tr.classList.toggle('tr-featured', featured); // Usa 'featured' directamente
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    });


    var start = moment().subtract(60, 'days');
    var end = moment();

    if($('#daterequest').val() != '') {
        var dates = $('#daterequest').val().split(' - ');
        start = moment(dates[0]);
        end = moment(dates[1]);
    }

    // Función para actualizar el campo con las fechas seleccionadas
    function cb(start, end, label) {
        if (label === 'All time') {
            $('#daterequest').val(''); // Limpiar el campo de entrada cuando se selecciona "All time"
        } else {
            $('#daterequest').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        }
    }

    // Inicializar el daterangepicker
    $('#daterequest').daterangepicker({
        startDate: start,
        endDate: end,
        autoUpdateInput: false, // No actualizar automáticamente el campo de entrada
        locale: {
            format: 'YYYY-MM-DD', // Establecer el formato de la fecha
            cancelLabel: 'Clear' // Texto del botón para limpiar la selección
        },
        ranges: {
            'All time': [moment().subtract(60, 'days'), moment()], // Desde hace 10 años hasta hoy
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, cb);


    // Cuando se seleccionan fechas, actualizar el input con el formato adecuado
    $('#daterequest').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    // Limpiar el campo y restaurar el placeholder al hacer clic en "Clear"
    $('#daterequest').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val(''); // Limpiar el campo
        $(this).attr('placeholder', 'Date/Range'); // Restaurar el placeholder
    });

});



function selectFilter(inputId, value) {
    // Asignar el valor al input correspondiente
    var input = document.getElementById(inputId);
    input.value = value;

    const form = document.getElementById('form-search');

    // Obtener los parámetros actuales de la URL
    const urlParams = new URLSearchParams(window.location.search);

    // Añadir los parámetros actuales al formulario como inputs ocultos
    urlParams.forEach((paramValue, key) => {
        if (!form.querySelector(`input[name="${key}"]`)) { // Evitar duplicados
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = key;
            hiddenInput.value = paramValue;
            form.appendChild(hiddenInput);
        }
    });

    // Enviar el formulario
    form.submit();
}


function selectResult(result) {
    selectFilter('inputsearchresult', result);
}

function selectStatus(status) {
    selectFilter('inputsearchstatus', status);
}

function selectSource(source) {
    selectFilter('inputsearchsource', source);
}

function selectTeam(assignedto) {
    selectFilter('inputsearchassignedto', assignedto);
}




function submitSearch() {
    // Obtener la URL actual y sus parámetros
    const currentUrl = new URL(window.location.href);
    const formData = new URLSearchParams(currentUrl.search); // Crear un nuevo objeto URLSearchParams con los parámetros actuales

    // Obtener el valor seleccionado de listforpage
    const listforpage = document.getElementById('listforpage').value;
    const daterequest = document.getElementById('daterequest').value;

    // Añadir o actualizar listforpage y daterequest a formData
    formData.set('listforpage', listforpage);
    formData.set('daterequest', daterequest);

    //Limpiar los checkboxes anteriores de type_inquiry
    formData.delete('type_inquiry[]');

    // Limpiar los checkboxes anteriores
    formData.delete('rating[]');

    // Obtener los valores de los inputs dentro del formulario y añadirlos a formData
    const inputs = document.getElementById('form-search').querySelectorAll('input');
    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            // Manejar checkboxes: si están seleccionados, añadir su valor
            if (input.checked) {
                // Si el checkbox es parte de un array, usamos 'append' para agregarlo
                formData.append(input.name, input.value);
            }
        } else {
            // Para otros tipos de input, usamos 'set' para asegurarnos de que se actualizan los valores
            formData.set(input.name, input.value);
        }
    });

    // Redirigir a la URL con los parámetros actualizados
    window.location.href = `${document.getElementById('form-search').action}?${formData.toString()}`;
}

// Añadir eventos de cambio a los inputs dentro del formulario
document.getElementById('form-search').querySelectorAll('input').forEach(input => {
    input.addEventListener('change', () => {
        submitSearch();
    });
});

// Añadir evento de cambio al input adicional
document.getElementById('listforpage').addEventListener('change', () => {
    submitSearch();
});

// Añadir evento de cambio al input adicional change date
document.getElementById('daterequest').addEventListener('change', () => {
    submitSearch();
});

// Exportar Data
if (document.getElementById('exportData')) {
    document.getElementById('exportData').addEventListener('click', function (e) {
        e.preventDefault();

        const button = e.target; // El botón que fue clicado

        // Desactivar el botón y añadir animación
        button.disabled = true;
        button.classList.add('disabled', 'animate');

        // Redirección con exportación
        const currentUrl = new URL(window.location.href);
        const formData = new URLSearchParams(currentUrl.search);
        formData.set('export', 'csv');

        // Simular la exportación (habilitar después de un tiempo estimado)
        setTimeout(() => {
            button.disabled = false; // Habilitar el botón
            button.classList.remove('disabled', 'animate');
        }, 5000); // Tiempo estimado para la descarga (ajústalo según sea necesario)

        // Realizar la redirección para exportar
        window.location.href = `${document.getElementById('form-search').action}?${formData.toString()}`;
    });
}




// Añadir evento de cambio al input adicional change date
$('#daterequest').on('apply.daterangepicker', function(ev, picker) {
    submitSearch();
});

// cunado haga click en clear del datepicker limpiar el campo
$('#daterequest').on('cancel.daterangepicker', function(ev, picker) {
    //de la url limpiar el campo daterequest pero mantener los demas parametros
    const currentUrl = new URL(window.location.href);
    const formData = new URLSearchParams(currentUrl.search); // Crear un nuevo objeto URLSearchParams con los parámetros actuales
    formData.delete('daterequest');
    window.location.href = `${document.getElementById('form-search').action}?${formData.toString()}`;
});

// Internal Inquiry
jQuery('#btn-new-internal-inquiry').on('click', function(e){
    e.preventDefault();
    jQuery('#newinquiryForm').fadeIn('fast');
    jQuery('html').css('overflowY', 'hidden');
})

jQuery('body').on('click', '#newinquiryForm:not(.__stored)', function(e){
    e.preventDefault();
    jQuery('#newinquiryForm').fadeOut('fast');
    setTimeout(() => {
        // Livewire.emit('clean_data_after_close');
        jQuery('html').css('overflowY', 'auto');
    }, 300);
})
jQuery('#newinquiry__close').on('click', function(e){
    e.preventDefault();
    jQuery('#newinquiryForm').fadeOut('fast');
    setTimeout(() => {
        // Livewire.emit('clean_data_after_close');
        jQuery('html').css('overflowY', 'auto');
    }, 300);
})

jQuery('body').on('click', '#newinquiryForm.__stored', function(e){
    e.preventDefault();
    jQuery('#newinquiryForm.__stored').show();
    location.reload();
})

jQuery('#newinquiryForm .newinquiry__content').on('click', function(e){
    e.stopPropagation();
})

jQuery('#newinquiryForm').on('click', '.newinquiry__thanks', function(e){
    e.stopPropagation();
})


document.addEventListener('livewire:load', function () {
    window.livewire.on("add_stored_class_to_internal_inquiry", function(){
        jQuery('#newinquiryForm').addClass('__stored');
    })
})



