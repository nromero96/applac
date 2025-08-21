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
                                    <div class="al-action d-flex align-items-center">
                                        <span class="text-result">Status changed</span> ${badge_last_status}
                                        <svg width="15" height="15" fill="none" stroke="#595959" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m9 18 6-6-6-6"></path>
                                          </svg>
                                        ${badge_new_status}
                                        ${
                                            note.contacted_via
                                            ?
                                                `
                                                <div class="d-flex align-items-center gap-2">
                                                    <span style="padding-left: 4px">/ Contacted via</span>
                                                    <span class="__contacted_via">
                                                        ${note.contacted_via == 'Call' ? `<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_11616_12093)"><path d="M14.665 11.28V13.28C14.6657 13.4657 14.6277 13.6494 14.5533 13.8195C14.479 13.9897 14.3699 14.1424 14.233 14.2679C14.0962 14.3934 13.9347 14.489 13.7588 14.5485C13.5829 14.6079 13.3966 14.63 13.2117 14.6133C11.1602 14.3904 9.18966 13.6894 7.45833 12.5667C5.84755 11.5431 4.48189 10.1774 3.45833 8.56665C2.33165 6.82745 1.63049 4.84731 1.41166 2.78665C1.395 2.60229 1.41691 2.41649 1.47599 2.24107C1.53508 2.06564 1.63004 1.90444 1.75484 1.76773C1.87964 1.63102 2.03153 1.52179 2.20086 1.447C2.37018 1.37221 2.55322 1.33349 2.73833 1.33332H4.73833C5.06187 1.33013 5.37552 1.4447 5.62084 1.65567C5.86615 1.86664 6.02638 2.15961 6.07166 2.47998C6.15608 3.12003 6.31263 3.74847 6.53833 4.35332C6.62802 4.59193 6.64744 4.85126 6.59427 5.10057C6.5411 5.34988 6.41757 5.57872 6.23833 5.75998L5.39166 6.60665C6.3407 8.27568 7.72263 9.65761 9.39166 10.6067L10.2383 9.75998C10.4196 9.58074 10.6484 9.45722 10.8977 9.40405C11.1471 9.35088 11.4064 9.37029 11.645 9.45998C12.2498 9.68568 12.8783 9.84224 13.5183 9.92665C13.8422 9.97234 14.1379 10.1355 14.3494 10.385C14.5608 10.6345 14.6731 10.953 14.665 11.28Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_11616_12093"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>` : ``}
                                                        ${note.contacted_via == 'Email' ? `<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.16536 2.66675H13.832C14.5654 2.66675 15.1654 3.26675 15.1654 4.00008V12.0001C15.1654 12.7334 14.5654 13.3334 13.832 13.3334H3.16536C2.43203 13.3334 1.83203 12.7334 1.83203 12.0001V4.00008C1.83203 3.26675 2.43203 2.66675 3.16536 2.66675Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.1654 4L8.4987 8.66667L1.83203 4" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>` : ``}
                                                        ${note.contacted_via == 'Text' ? `<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 7.66669C14.0023 8.5466 13.7967 9.41461 13.4 10.2C12.9296 11.1412 12.2065 11.9328 11.3116 12.4862C10.4168 13.0396 9.3855 13.3329 8.33333 13.3334C7.45342 13.3356 6.58541 13.1301 5.8 12.7334L2 14L3.26667 10.2C2.86995 9.41461 2.66437 8.5466 2.66667 7.66669C2.66707 6.61452 2.96041 5.58325 3.51381 4.68839C4.06722 3.79352 4.85884 3.0704 5.8 2.60002C6.58541 2.20331 7.45342 1.99772 8.33333 2.00002H8.66667C10.0562 2.07668 11.3687 2.66319 12.3528 3.64726C13.3368 4.63132 13.9233 5.94379 14 7.33335V7.66669Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>` : ``}
                                                        ${note.contacted_via}
                                                    </span>
                                                </div>
                                                `
                                            : ''
                                        }
                                    </div>

                                    ${notereason}
                                    <div class="al-info">
                                        <span class="name">${note.user_name}</span>
                                        <svg width="13" height="13" fill="none" stroke="#0a6ab7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                          </svg>
                                        <span class="comment">${note.note}</span>
                                    </div>
                                    <div class="al-date">
                                        <small class="date">${formattedDate}</small> - <small class="time">${formattedTime}</small>
                                        <span class="badge rounded-pill badge-light-time">${note.time_diff}</span>
                                    </div>

                    `;
                }

                if(note.type == 'read'){
                    noteElement.innerHTML = `
                    <div class="activity-log border-0 mb-0 mt-2">
                        <div class="al-action">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_11627_9498)"><path d="M1.16797 8.50008C1.16797 8.50008 3.83464 3.16675 8.5013 3.16675C13.168 3.16675 15.8346 8.50008 15.8346 8.50008C15.8346 8.50008 13.168 13.8334 8.5013 13.8334C3.83464 13.8334 1.16797 8.50008 1.16797 8.50008Z" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 10.5C9.60457 10.5 10.5 9.60457 10.5 8.5C10.5 7.39543 9.60457 6.5 8.5 6.5C7.39543 6.5 6.5 7.39543 6.5 8.5C6.5 9.60457 7.39543 10.5 8.5 10.5Z" stroke="#1877F2" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_11627_9498"><rect width="16" height="16" fill="white" transform="translate(0.5 0.5)"/></clipPath></defs></svg>
                            <span class="text-result">Inquiry opened</span>
                        </div>
                        <div class="al-date">
                            <small class="date">${formattedDate}</small> - <small class="time">${formattedTime}</small>
                            <span class="badge rounded-pill badge-light-time">${note.time_diff}</span>
                        </div>
                    </div>
                    `;
                }

                if(note.type == 'result_status'){

                    // ${badge_last_status}
                    noteElement.innerHTML = `
                                    <div class="al-action d-flex align-items-center">
                                        <span class="text-result">Outcome ${note.update_type}</span>
                                        <svg width="15" height="15" fill="none" stroke="#595959" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m9 18 6-6-6-6"></path>
                                          </svg>
                                        ${badge_new_status}
                                        ${
                                            note.followup_channel
                                            ?
                                                `
                                                <div class="d-flex align-items-center gap-2">
                                                    <span style="padding-left: 4px">/ Follow-up via</span>
                                                    <span class="__contacted_via">
                                                        ${note.followup_channel == 'Call' ? `<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_11616_12093)"><path d="M14.665 11.28V13.28C14.6657 13.4657 14.6277 13.6494 14.5533 13.8195C14.479 13.9897 14.3699 14.1424 14.233 14.2679C14.0962 14.3934 13.9347 14.489 13.7588 14.5485C13.5829 14.6079 13.3966 14.63 13.2117 14.6133C11.1602 14.3904 9.18966 13.6894 7.45833 12.5667C5.84755 11.5431 4.48189 10.1774 3.45833 8.56665C2.33165 6.82745 1.63049 4.84731 1.41166 2.78665C1.395 2.60229 1.41691 2.41649 1.47599 2.24107C1.53508 2.06564 1.63004 1.90444 1.75484 1.76773C1.87964 1.63102 2.03153 1.52179 2.20086 1.447C2.37018 1.37221 2.55322 1.33349 2.73833 1.33332H4.73833C5.06187 1.33013 5.37552 1.4447 5.62084 1.65567C5.86615 1.86664 6.02638 2.15961 6.07166 2.47998C6.15608 3.12003 6.31263 3.74847 6.53833 4.35332C6.62802 4.59193 6.64744 4.85126 6.59427 5.10057C6.5411 5.34988 6.41757 5.57872 6.23833 5.75998L5.39166 6.60665C6.3407 8.27568 7.72263 9.65761 9.39166 10.6067L10.2383 9.75998C10.4196 9.58074 10.6484 9.45722 10.8977 9.40405C11.1471 9.35088 11.4064 9.37029 11.645 9.45998C12.2498 9.68568 12.8783 9.84224 13.5183 9.92665C13.8422 9.97234 14.1379 10.1355 14.3494 10.385C14.5608 10.6345 14.6731 10.953 14.665 11.28Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_11616_12093"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>` : ``}
                                                        ${note.followup_channel == 'Email' ? `<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.16536 2.66675H13.832C14.5654 2.66675 15.1654 3.26675 15.1654 4.00008V12.0001C15.1654 12.7334 14.5654 13.3334 13.832 13.3334H3.16536C2.43203 13.3334 1.83203 12.7334 1.83203 12.0001V4.00008C1.83203 3.26675 2.43203 2.66675 3.16536 2.66675Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.1654 4L8.4987 8.66667L1.83203 4" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>` : ``}
                                                        ${note.followup_channel == 'Text' ? `<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 7.66669C14.0023 8.5466 13.7967 9.41461 13.4 10.2C12.9296 11.1412 12.2065 11.9328 11.3116 12.4862C10.4168 13.0396 9.3855 13.3329 8.33333 13.3334C7.45342 13.3356 6.58541 13.1301 5.8 12.7334L2 14L3.26667 10.2C2.86995 9.41461 2.66437 8.5466 2.66667 7.66669C2.66707 6.61452 2.96041 5.58325 3.51381 4.68839C4.06722 3.79352 4.85884 3.0704 5.8 2.60002C6.58541 2.20331 7.45342 1.99772 8.33333 2.00002H8.66667C10.0562 2.07668 11.3687 2.66319 12.3528 3.64726C13.3368 4.63132 13.9233 5.94379 14 7.33335V7.66669Z" stroke="#0A6AB7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>` : ``}
                                                        ${note.followup_channel}
                                                    </span>
                                                </div>
                                                `
                                            : ''
                                        }
                                    </div>
                                    <div class="d-flex gap-2">
                                        ${
                                            note.followup_feedback
                                                ? `<div><strong>Feedback:</strong> <span>${note.followup_feedback}</span></div>`
                                                : ''
                                        }
                                        ${
                                            note.followup_comment
                                                ? `
                                                    <div>
                                                        <svg width="13" height="13" fill="none" stroke="#0a6ab7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                                        <span>${note.followup_comment}</span>
                                                    </div>`
                                                : ''
                                        }
                                    </div>
                                    ${
                                        note.lost_reason
                                            ? `<div><strong>Reason for losing deal:</strong> <span>${note.lost_reason}</span></div>`
                                            : ''
                                    }
                                    <div class="al-info">
                                        <span class="name">${note.user_name}</span>
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.33203 3.16675H2.66536C2.31174 3.16675 1.9726 3.30722 1.72256 3.55727C1.47251 3.80732 1.33203 4.14646 1.33203 4.50008V13.8334C1.33203 14.187 1.47251 14.5262 1.72256 14.7762C1.9726 15.0263 2.31174 15.1667 2.66536 15.1667H11.9987C12.3523 15.1667 12.6915 15.0263 12.9415 14.7762C13.1916 14.5262 13.332 14.187 13.332 13.8334V9.16675" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12.332 2.16665C12.5972 1.90144 12.957 1.75244 13.332 1.75244C13.7071 1.75244 14.0668 1.90144 14.332 2.16665C14.5972 2.43187 14.7462 2.79158 14.7462 3.16665C14.7462 3.54173 14.5972 3.90144 14.332 4.16665L7.9987 10.5L5.33203 11.1667L5.9987 8.49999L12.332 2.16665Z" stroke="#0A6AB7" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="comment">${note.note}</span>
                                    </div>
                                    <div class="al-date">
                                        <small class="date">${formattedDate}</small> - <small class="time">${formattedTime}</small>
                                        ${
                                            note.note != 'Result status auto-updated'
                                                ? `<span class="badge rounded-pill badge-light-time">${note.time_diff}</span>`
                                                : ''
                                        }
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


    //form-status animation submit and send
    const formstatus = document.getElementById("form-status");
    const formstatus_submitButton = formstatus.querySelector("button[type='submit']");

        formstatus.addEventListener("submit", function () {
            // Deshabilita el botón
            formstatus_submitButton.disabled = true;
            // Cambia el contenido a un spinner de Bootstrap
            formstatus_submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`;
        });
