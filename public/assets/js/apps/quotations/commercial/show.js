document.querySelectorAll('input[name="new_rating"]').forEach(function(ratingInput) {
    ratingInput.addEventListener('change', function() {
        var selectedRating = parseInt(this.value);

        document.querySelectorAll('input[name="new_rating"]').forEach(function(input) {
            var starLabel = document.querySelector('label[for="' + input.id + '"]');
            var starValue = parseInt(input.value);

            if (starValue <= selectedRating) {
                starLabel.classList.add('active');
            } else {
                starLabel.classList.remove('active');
            }
        });
    });
});


//if click .btn-modify javascript puro
document.querySelector('.btn-modify').addEventListener('click', function() {
    document.getElementById('rtg_modified_by').classList.remove('d-inline');
    document.getElementById('rtg_modified_by').classList.add('d-none');

    document.getElementById('rtg_comment_input').classList.remove('d-none');
    document.getElementById('rtg_comment_input').classList.add('d-inline');

    document.getElementById('rtg_rating_stars').classList.add('d-none');
    document.getElementById('rtg_rating_stars').classList.remove('d-inline');

    document.getElementById('rtg_modified_stars').classList.remove('d-none');
    document.getElementById('rtg_modified_stars').classList.add('d-inline');

    document.querySelectorAll('input[name="new_rating"]').forEach(function(input) {
        input.removeAttribute('disabled');
    });
});

//if click .btn-rtg-cancel javascript puro
document.querySelector('.btn-rtg-cancel').addEventListener('click', function() {
    document.getElementById('rtg_modified_by').classList.add('d-inline');
    document.getElementById('rtg_modified_by').classList.remove('d-none');

    document.getElementById('rtg_comment_input').classList.add('d-none');
    document.getElementById('rtg_comment_input').classList.remove('d-inline');

    document.getElementById('rtg_rating_stars').classList.remove('d-none');
    document.getElementById('rtg_rating_stars').classList.add('d-inline');

    document.getElementById('rtg_modified_stars').classList.add('d-none');
    document.getElementById('rtg_modified_stars').classList.remove('d-inline');

    document.querySelectorAll('input[name="new_rating"]').forEach(function(input) {
        input.setAttribute('disabled', 'disabled');
    });
});

//if change select id action
document.getElementById('action').addEventListener('change', function() {
    document.getElementById('note').value = '';
    if (this.value === 'Unqualified') {
        document.getElementById('dv_reason').classList.add('d-block');
        document.getElementById('dv_reason').classList.remove('d-none');
        document.getElementById('reason').setAttribute('required', 'required');
        document.getElementById('dv_inquiry_note').classList.add('d-none');
    } else {
        document.getElementById('dv_reason').classList.add('d-none');
        document.getElementById('dv_reason').classList.remove('d-inline');
        //remove required reason
        document.getElementById('reason').removeAttribute('required');
        // dv_inquiry_note
        document.getElementById('dv_inquiry_note').classList.remove('d-none');
    }
});

//if chage reason
document.getElementById('reason').addEventListener('change', function() {
    document.getElementById('note').value = '';
    if (this.value === 'Other') {
        //dv_inquiry_note
        document.getElementById('dv_inquiry_note').classList.remove('d-none');
        document.getElementById('label_note').innerHTML = 'Specify reason <span class="text-danger">*</span>';
        document.getElementById('note').setAttribute('required', 'required');
    } else {
        document.getElementById('dv_inquiry_note').classList.add('d-none');
        document.getElementById('label_note').innerHTML = 'Comment (Optional)';
        document.getElementById('note').removeAttribute('required');
    }
});

listQuotationNotes(document.getElementById('quotation_id').value);

function listQuotationNotes(quotationId) {
    fetch(`/list-quotation-notes/${quotationId}`)  // Cambia la URL según la ruta de tu API
        .then(response => response.json())  // Convertimos la respuesta a JSON
        .then(data => {
            // Iterar sobre las notas de cotización y mostrarlas en el DOM
            let notesContainer = document.getElementById('quotation-notes');  // Suponiendo que tengas un contenedor en el DOM
            notesContainer.innerHTML = '';  // Limpiar contenido previo

            data.forEach(note => {
                // Limpiar y separar el estado
                let [last_status, new_status] = note.action.replace(/'/g, "").split(' to ');

                // Crear los badges usando la función
                let badge_last_status = getBadge(last_status);
                let badge_new_status = getBadge(new_status);

                // manejar el formato de la fecha (YYY-MM-DD) y hora(HH:MM)
                let date = new Date(note.created_at);
                // Formatear la fecha con la zona horaria específica
                let formattedDate = date.toLocaleDateString('es-US', {
                    day: '2-digit', month: '2-digit', year: 'numeric', timeZone: 'America/New_York'
                });

                // Formatear la hora con la zona horaria específica
                let formattedTime = date.toLocaleTimeString('en-US', {
                    hour: '2-digit', minute: '2-digit', second:'2-digit', hour12: false, timeZone: 'America/New_York'
                });

                //Calcular la diferencia de tiempo que diga 'in 30 minutes' o '2 hours' o '1 day' se debe calcular del estus anterior al actual

                //imprimir todo en log


                if(note.reason){
                    var notereason = `
                        <div class="al-info">
                            <span class="name">Reason: </span>
                            <span class="comment">${note.reason}</span>
                        </div>
                    `;
                }else{
                    var notereason = '';
                }


                let noteElement = document.createElement('div');
                noteElement.classList.add('activity-log', 'pb-2', 'pt-2');

                if(note.type == 'inquiry_status'){
                    noteElement.innerHTML = `
                                    <div class="al-action">
                                        <span class="text-result">Inquiry status changed</span> ${badge_last_status}
                                        <svg width="15" height="15" fill="none" stroke="#595959" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m9 18 6-6-6-6"></path>
                                          </svg>
                                        ${badge_new_status}
                                    </div>
                                    <div class="al-date">
                                        <small class="date">${formattedDate}</small> - <small class="time">${formattedTime}</small>
                                        <span class="badge rounded-pill badge-light-time">${note.time_diff}</span>
                                    </div>
                                    ${notereason}
                                    <div class="al-info">
                                        <span class="name">${note.user_name}</span>
                                        <svg width="13" height="13" fill="none" stroke="#0a6ab7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                          </svg>
                                        <span class="comment">${note.note}</span>
                                    </div>

                    `;
                }

                if(note.type == 'result_status'){

                    noteElement.innerHTML = `
                                    <div class="al-action">
                                        <span class="text-result">Result status changed</span> ${badge_last_status}
                                        <svg width="15" height="15" fill="none" stroke="#595959" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m9 18 6-6-6-6"></path>
                                          </svg>
                                        ${badge_new_status}
                                    </div>
                                    <div class="al-date">
                                        <small class="date">${formattedDate}</small> - <small class="time">${formattedTime}</small>
                                    </div>
                                    <div class="al-info">
                                        <span class="name">${note.user_name}</span>
                                        <svg width="13" height="13" fill="none" stroke="#0a6ab7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                          </svg>
                                        <span class="comment">${note.note}</span>
                                    </div>

                    `;
                }

                if(note.type == 'rating'){


                    let last_stars = '';
                    for (let i = 1; i <= last_status; i++) {
                        last_stars += '★';
                    }

                    let new_stars = '';
                    for (let i = 1; i <= new_status; i++) {
                        new_stars += '★';
                    }



                    noteElement.innerHTML = `
                                    <div class="al-action">
                                        <span class="text-result">Rating changed</span>
                                        <span class="badge badge-light-warning">${last_stars}</span>
                                        <svg width="15" height="15" fill="none" stroke="#595959" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m9 18 6-6-6-6"></path>
                                          </svg>
                                        <span class="badge badge-light-info">${new_stars}</span>
                                    </div>
                                    <div class="al-date">
                                        <small class="date">${formattedDate}</small> - <small class="time">${formattedTime}</small>
                                    </div>
                                    <div class="al-info">
                                        <span class="name">${note.user_name}</span>
                                        <svg width="13" height="13" fill="none" stroke="#0a6ab7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                          </svg>
                                        <span class="comment">${note.note}</span>
                                    </div>

                    `;
                }

                // Añadir la nota al contenedor
                notesContainer.appendChild(noteElement);

            });

        })
        .catch(error => {
            console.error('Error fetching quotation notes:', error);
        });
}


// Función para generar los badges según el estado
function getBadge(status) {
    const badgeClasses = {
        'Pending': 'badge-light-pending',
        'Qualifying': 'badge-light-warning',
        'Processing': 'badge-light-info',
        'Attended': 'badge-light-info',
        'Quote Sent': 'badge-light-success',
        'Unqualified': 'badge-light-unqualified',
        'Under Review': 'badge-light-warning',
        'Lost': 'badge-light-danger',
        'Won': 'badge-light-success',
        'Deleted': 'badge-light-danger',
    };

    // Retornar el badge correspondiente o uno por defecto si no se encuentra el estado
    return `<span class="badge ${badgeClasses[status] || 'badge-light-default'}">${status}</span>`;
}

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

//click action 'a' classs delete_inquiry
const deleteInquiry = document.querySelectorAll('.delete_inquiry');
deleteInquiry.forEach((element) => {
    element.addEventListener('click', function(event) {
        event.preventDefault();
        var inquiry_id = this.getAttribute('data-inquiry-id');
        var mymodal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        mymodal.show();
        

    });
});

//print content id 'data_inquiry' by click class 'print_inquiry'
document
    .getElementById("print_inquiry")
    .addEventListener("click", function () {
        const elementToCapture = document.querySelector(".data_inquiry");

        const ignoreElements = function (element) {
            // Retorna true para excluir el elemento con la clase ''
            return (
                element.classList.contains("bxstatus") ||
                element.classList.contains("bxresult") ||
                element.classList.contains("btn-modify") ||
                element.classList.contains("dropdown-menu") ||
                element.classList.contains("page-meta")
            );
        };

        html2canvas(elementToCapture, {
            ignoreElements: ignoreElements,
        }).then(function (canvas) {
            const image = canvas.toDataURL("image/png");
            printJS({
                printable: image,
                type: "image",
                onPrintDialogClose: function () {
                    // Aquí puedes realizar acciones adicionales después de que se cierra el diálogo de impresión
                },
            });
        });
    });