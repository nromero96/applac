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

                if(note.type == 'followup') {
                    noteElement.innerHTML = `
                        <div class="al-action d-flex align-items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#FAE6E6"/>
                                <path d="M15.5 16.5L12 14L8.5 16.5V8.5C8.5 8.23478 8.60536 7.98043 8.79289 7.79289C8.98043 7.60536 9.23478 7.5 9.5 7.5H14.5C14.7652 7.5 15.0196 7.60536 15.2071 7.79289C15.3946 7.98043 15.5 8.23478 15.5 8.5V16.5Z" stroke="#B80000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <b class="text-result">${ note.action }</b>
                        </div>
                        <div class="log__tab mt-2">
                            ${ (() => {
                                if (!note.note) return '';
                                let data;
                                try {
                                    data = JSON.parse(note.note);
                                } catch (e) {
                                    return '';
                                }
                                if (note.update_type != 'done') {
                                    const formattedDate = data.date ? data.date.split('-').reverse().join('/') : '';
                                    return `
                                        ${ formattedDate ? `<b>Reminder date:</b> ${ formattedDate } <br>` : '' }
                                        ${ data.notes ? `<b>Note:</b> ${ data.notes } <br>` : '' }
                                        ${ data.priority ? `<b>Priority:</b> ${ data.priority } <br>` : '' }
                                    `;
                                } else {
                                    const formattedDate = data.date_done ? data.date_done.split('-').reverse().join('/') : '';
                                    return `
                                        ${ formattedDate ? `<b>Completed date:</b> ${ formattedDate } <br>` : '' }
                                        ${ data.note ? `<b>Note:</b> ${ data.note } <br>` : '' }
                                    `;
                                }
                            })() }
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

                if(note.type == 'result_status'){

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

                    noteElement.innerHTML = `
                        <div class="al-action d-flex align-items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#FFF2E8"/>
                                <g clip-path="url(#clip0_13624_3750)">
                                    <path d="M14.5 7.50015C14.6313 7.36883 14.7872 7.26466 14.9588 7.19359C15.1304 7.12252 15.3143 7.08594 15.5 7.08594C15.6857 7.08594 15.8696 7.12252 16.0412 7.19359C16.2128 7.26466 16.3687 7.36883 16.5 7.50015C16.6313 7.63147 16.7355 7.78737 16.8066 7.95895C16.8776 8.13054 16.9142 8.31443 16.9142 8.50015C16.9142 8.68587 16.8776 8.86977 16.8066 9.04135C16.7355 9.21293 16.6313 9.36883 16.5 9.50015L9.75 16.2502L7 17.0002L7.75 14.2502L14.5 7.50015Z" stroke="#EB6200" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_13624_3750">
                                    <rect width="12" height="12" fill="white" transform="translate(6 6)"/>
                                </clipPath>
                                </defs>
                            </svg>
                            <b class="text-result">Rating updated</b>
                        </div>
                        <div class="log__tab mt-2">

                            <b>From: </b> ${ last_status }★ → ${ new_status }★ <br>
                            ${ note.note ? `<b>Comment:</b> ${ note.note }` : ``}

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

                if(note.type == 'docs'){

                    noteElement.innerHTML = `
                        <div class="al-action d-flex align-items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#EEF8FF"/>
                                <g clip-path="url(#clip0_13625_3884)">
                                <path d="M16.7201 11.525L12.1251 16.12C11.5622 16.6829 10.7987 16.9991 10.0026 16.9991C9.20655 16.9991 8.44307 16.6829 7.88014 16.12C7.31722 15.557 7.00098 14.7936 7.00098 13.9975C7.00098 13.2014 7.31722 12.4379 7.88014 11.875L12.4751 7.27996C12.8504 6.90468 13.3594 6.69385 13.8901 6.69385C14.4209 6.69385 14.9299 6.90468 15.3051 7.27996C15.6804 7.65524 15.8913 8.16423 15.8913 8.69496C15.8913 9.22569 15.6804 9.73468 15.3051 10.11L10.7051 14.705C10.5175 14.8926 10.263 14.998 9.99764 14.998C9.73228 14.998 9.47779 14.8926 9.29014 14.705C9.1025 14.5173 8.99709 14.2628 8.99709 13.9975C8.99709 13.7321 9.1025 13.4776 9.29014 13.29L13.5351 9.04996" stroke="#68C0FF" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_13625_3884">
                                <rect width="12" height="12" fill="white" transform="translate(6 6)"/>
                                </clipPath>
                                </defs>
                            </svg>
                            <b class="text-result">${ note.action }</b>
                        </div>
                        <div class="log__tab mt-2">
                            ${ (() => {
                                if (!note.note) return '';
                                let data;
                                try {
                                    data = JSON.parse(note.note);
                                } catch (e) {
                                    return '';
                                }
                                if (note.update_type == 'added') {
                                    return `
                                        ${ data.files && data.files.length > 0 ? '<b>Files:</b> <br>' + data.files.join('<br>') + '<br>' : ''}
                                        ${ data.priority ? `<b>Priority:</b> Marked as important <br>` : '' }
                                    `;
                                }
                            })() }

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

                if(note.type == 'reassigned'){

                    noteElement.innerHTML = `
                        <div class="al-action d-flex align-items-center gap-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 12C0 5.37258 5.37258 0 12 0C18.6274 0 24 5.37258 24 12C24 18.6274 18.6274 24 12 24C5.37258 24 0 18.6274 0 12Z" fill="#FCF4D6"/>
                                <path d="M14.5 16.5V15.5C14.5 14.9696 14.2893 14.4609 13.9142 14.0858C13.5391 13.7107 13.0304 13.5 12.5 13.5H8.5C7.96957 13.5 7.46086 13.7107 7.08579 14.0858C6.71071 14.4609 6.5 14.9696 6.5 15.5V16.5" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.5 11.5C11.6046 11.5 12.5 10.6046 12.5 9.5C12.5 8.39543 11.6046 7.5 10.5 7.5C9.39543 7.5 8.5 8.39543 8.5 9.5C8.5 10.6046 9.39543 11.5 10.5 11.5Z" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.5 16.4999V15.4999C17.4997 15.0568 17.3522 14.6263 17.0807 14.2761C16.8092 13.9259 16.4291 13.6757 16 13.5649" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 7.56494C14.4302 7.67509 14.8115 7.92529 15.0838 8.2761C15.3561 8.6269 15.5039 9.05836 15.5039 9.50244C15.5039 9.94653 15.3561 10.378 15.0838 10.7288C14.8115 11.0796 14.4302 11.3298 14 11.4399" stroke="#B28600" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <b class="text-result">${ note.action }</b>
                        </div>
                        <div class="log__tab mt-2">
                            <b>Previous owner: </b> ${ JSON.parse(note.note).old_owner } <br>
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
            });

            listQuotationNotes(document.getElementById('quotation_id').value);

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
