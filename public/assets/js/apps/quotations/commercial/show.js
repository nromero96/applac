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
if (document.querySelector('.btn-modify')) {
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
}

//if click .btn-rtg-cancel javascript puro
if (document.querySelector('.btn-rtg-cancel')) {
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
}

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
            console.log(data);
            // Iterar sobre las notas de cotización y mostrarlas en el DOM
            let notesContainer = document.getElementById('quotation-notes');  // Suponiendo que tengas un contenedor en el DOM
            notesContainer.innerHTML = '';  // Limpiar contenido previo

            data.forEach(note => {
                // Limpiar y separar el estado
                let [last_status_base, new_status_base] = note.action_base.replace(/'/g, "").split(' to ');
                let [last_status, new_status] = note.action.replace(/'/g, "").split(' to ');

                // Crear los badges usando la función
                let badge_last_status = getBadge(last_status_base, last_status);
                let badge_new_status = getBadge(new_status_base, new_status);

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


                let noteElement = document.createElement('div');
                noteElement.classList.add('activity-log', 'pb-2', 'pt-2');

                if(note.type == 'inquiry_status'){
                    noteElement.innerHTML = `
                        <div class="al-action d-flex align-items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#EFE6FD"/>
                                <path d="M17.5 8V11H14.5" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.5 16V13H9.5" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.755 10.5C8.00858 9.78343 8.43957 9.14274 9.00773 8.63775C9.5759 8.13275 10.2627 7.77992 11.0041 7.61217C11.7456 7.44441 12.5174 7.46721 13.2476 7.67842C13.9778 7.88964 14.6426 8.28239 15.18 8.82004L17.5 11M6.5 13L8.82 15.18C9.35737 15.7177 10.0222 16.1104 10.7524 16.3217C11.4826 16.5329 12.2544 16.5557 12.9959 16.3879C13.7373 16.2202 14.4241 15.8673 14.9923 15.3623C15.5604 14.8573 15.9914 14.2166 16.245 13.5" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <b class="text-result">Status ${note.user_id == 1 ? 'auto-' : ''}${ note.update_type == 'changed' ? 'updated to' : ':'}</b> ${badge_new_status}
                        </div>
                        <div class="log__tab mt-2">
                            ${
                                note.update_type == 'changed'
                                    ? `<b>From: </b> ${ note.action.replace(/'/g, "").replace("to", " → ") } <br>`
                                    : ''
                            }
                            ${ note.contacted_via ? `<b>Channel:</b> ${ note.contacted_via } <br>` : '' }
                            ${ note.options_sent ? `<b>Options sent:</b> ${ note.options_sent } <br>` : '' }

                            ${
                                (note.process_for)
                                ?
                                `
                                    ${
                                        note.processed_by_type
                                        ? `
                                            <b>Processed by:</b> ${note.processed_user_name} ${note.processed_user_lastname} (${note.processed_by_type}) <br>
                                        `
                                        : ''
                                    }
                                    <b>Processing type:</b> ${ note.process_for } <br>
                                `
                                : ''
                            }

                            ${ note.reason ? `<b>Reason:</b> ${ note.reason } <br>` : '' }
                            ${ note.note ? `<b>Comment:</b> ${ note.note } <br>` : '' }

                            <div class="al-date d-flex align-items-center gap-1 mt-2">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.3332 14V12.6667C13.3332 11.9594 13.0522 11.2811 12.5521 10.781C12.052 10.281 11.3737 10 10.6665 10H5.33317C4.62593 10 3.94765 10.281 3.44755 10.781C2.94746 11.2811 2.6665 11.9594 2.6665 12.6667V14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.00016 7.33333C9.47292 7.33333 10.6668 6.13943 10.6668 4.66667C10.6668 3.19391 9.47292 2 8.00016 2C6.5274 2 5.3335 3.19391 5.3335 4.66667C5.3335 6.13943 6.5274 7.33333 8.00016 7.33333Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <small>${note.user_name}</small>
                                <small class="log__bullet">•</small>
                                <small class="date">${formattedDate}</small> -
                                <small class="time">${formattedTime}</small>
                                <span class="badge rounded-pill badge-light-time">${note.time_diff}</span>
                            </div>
                        </div>

                    `;
                }

                if(note.type == 'read'){
                    noteElement.innerHTML = `
                        <div class="al-action d-flex align-items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#D3EAFD"/>
                                <path d="M6.5 12C6.5 12 8.5 8 12 8C15.5 8 17.5 12 17.5 12C17.5 12 15.5 16 12 16C8.5 16 6.5 12 6.5 12Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 13.5C12.8284 13.5 13.5 12.8284 13.5 12C13.5 11.1716 12.8284 10.5 12 10.5C11.1716 10.5 10.5 11.1716 10.5 12C10.5 12.8284 11.1716 13.5 12 13.5Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <b class="text-result">Inquiry opened</b>
                        </div>
                        <div class="log__tab mt-2">
                            <div class="al-date d-flex align-items-center gap-1 mt-2">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.3332 14V12.6667C13.3332 11.9594 13.0522 11.2811 12.5521 10.781C12.052 10.281 11.3737 10 10.6665 10H5.33317C4.62593 10 3.94765 10.281 3.44755 10.781C2.94746 11.2811 2.6665 11.9594 2.6665 12.6667V14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.00016 7.33333C9.47292 7.33333 10.6668 6.13943 10.6668 4.66667C10.6668 3.19391 9.47292 2 8.00016 2C6.5274 2 5.3335 3.19391 5.3335 4.66667C5.3335 6.13943 6.5274 7.33333 8.00016 7.33333Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <small>${note.user_name} ${note.user_lastname}</small>
                                <small class="log__bullet">•</small>
                                <small class="date">${formattedDate}</small> -
                                <small class="time">${formattedTime}</small>
                                <span class="badge rounded-pill badge-light-time">${note.time_diff}</span>
                            </div>
                        </div>
                    `;
                }

                if(note.type == 'result_status'){

                    // ${badge_last_status}
                    noteElement.innerHTML = `

                        ${
                            note.update_type == 'changed'
                            ? `
                                <div class="al-action d-flex align-items-center gap-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#EFE6FD"/>
                                        <path d="M17.5 8V11H14.5" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6.5 16V13H9.5" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7.755 10.5C8.00858 9.78343 8.43957 9.14274 9.00773 8.63775C9.5759 8.13275 10.2627 7.77992 11.0041 7.61217C11.7456 7.44441 12.5174 7.46721 13.2476 7.67842C13.9778 7.88964 14.6426 8.28239 15.18 8.82004L17.5 11M6.5 13L8.82 15.18C9.35737 15.7177 10.0222 16.1104 10.7524 16.3217C11.4826 16.5329 12.2544 16.5557 12.9959 16.3879C13.7373 16.2202 14.4241 15.8673 14.9923 15.3623C15.5604 14.8573 15.9914 14.2166 16.245 13.5" stroke="#6200EE" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <b class="text-result">Outcome ${note.user_id == 1 ? 'auto-' : ''}marked as</b> ${badge_new_status}
                                </div>
                            `
                            : `
                                <div class="al-action d-flex align-items-center gap-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#D3EAFD"/>
                                        <path d="M6.5 12C6.5 12 8.5 8 12 8C15.5 8 17.5 12 17.5 12C17.5 12 15.5 16 12 16C8.5 16 6.5 12 6.5 12Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 13.5C12.8284 13.5 13.5 12.8284 13.5 12C13.5 11.1716 12.8284 10.5 12 10.5C11.1716 10.5 10.5 11.1716 10.5 12C10.5 12.8284 11.1716 13.5 12 13.5Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                    <b class="text-result">Follow-up logged</b>
                                </div>
                            `
                        }
                        <div class="log__tab mt-2">
                            ${
                                note.update_type == 'changed'
                                    ? `<b>From: </b> ${ note.action.replace(/'/g, "").replace("to", " → ") } <br>`
                                    : ''
                            }
                            ${ note.followup_channel ? `<b>Follow-up:</b> ${ note.followup_channel } <br>` : '' }
                            ${ note.followup_feedback ? `<b>Feedback:</b> ${ note.followup_feedback } <br>` : '' }
                            ${ note.followup_comment ? `<b>Follow-up Comment:</b> ${ note.followup_comment } <br>` : '' }
                            ${ note.lost_reason ? `<b>Reason for losing deal:</b> ${ note.lost_reason } <br>` : '' }
                            ${ note.note ? `<b>Comment:</b> ${ note.note } <br>` : '' }
                            <div class="al-date d-flex align-items-center gap-1 mt-2">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.3332 14V12.6667C13.3332 11.9594 13.0522 11.2811 12.5521 10.781C12.052 10.281 11.3737 10 10.6665 10H5.33317C4.62593 10 3.94765 10.281 3.44755 10.781C2.94746 11.2811 2.6665 11.9594 2.6665 12.6667V14" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.00016 7.33333C9.47292 7.33333 10.6668 6.13943 10.6668 4.66667C10.6668 3.19391 9.47292 2 8.00016 2C6.5274 2 5.3335 3.19391 5.3335 4.66667C5.3335 6.13943 6.5274 7.33333 8.00016 7.33333Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <small>${note.user_name}</small>
                                <small class="log__bullet">•</small>
                                <small class="date">${formattedDate}</small> -
                                <small class="time">${formattedTime}</small>
                                <span class="badge rounded-pill badge-light-time">${note.time_diff}</span>
                            </div>
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
function getBadge(status, statusLabel = '', draw = true) {
    const badgeClasses = {
        'Pending': 'badge-light-pending',
        'Contacted': 'badge-light-warning',
        'Qualified': 'badge-light-info',
        'Attended': 'badge-light-info',
        'Quote Sent': 'badge-light-success',
        'Unqualified': 'badge-light-unqualified',
        'Stalled': 'badge-light-stalled',
        'Under Review': 'badge-light-warning',
        'Lost': 'badge-light-danger',
        'Won': 'badge-light-won',
        'Deleted': 'badge-light-danger',
        //
        'Full Quote': 'process_full_quote',
        'Estimate': 'process_estimate',
    };

    if (draw) {
        // Retornar el badge correspondiente o uno por defecto si no se encuentra el estado
        return `<span class="badge ${badgeClasses[status] || 'badge-light-default'}">${statusLabel != '' ? statusLabel : status}</span>`;
    } else {
        return badgeClasses[status];
    }
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


    //form-status animation submit and send
    const formstatus = document.getElementById("form-status");
    const formstatus_submitButton = formstatus.querySelector("button[type='submit']");

        formstatus.addEventListener("submit", function () {
            // Deshabilita el botón
            formstatus_submitButton.disabled = true;
            // Cambia el contenido a un spinner de Bootstrap
            formstatus_submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;
        });
