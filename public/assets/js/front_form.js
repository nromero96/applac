document.addEventListener('DOMContentLoaded', function () {
    
    //modal confirm-percomp-modal auto open javascript puro
    var confirm_percomp_modal = new bootstrap.Modal(document.getElementById('confirm-percomp-modal'));
    confirm_percomp_modal.show();

    var dv_options_best = document.getElementById('options_best');
    var dv_options_best_personal = document.getElementById('options_best_personal');

    //if click radio name options_best
    var options_best = document.getElementsByName('options_best');

    for (var i = 0; i < options_best.length; i++) {
        options_best[i].addEventListener('change', function() {
            if (this.value == 'personal') {
                dv_options_best_personal.classList.remove('d-none');
                dv_options_best.classList.add('d-none');
            } else {
                confirm_percomp_modal.hide();
            }
        });
    }

    var accept_terms_personal = document.getElementById('accept_terms_personal');
    var confirm_terms_personal = document.getElementById('confirm_terms_personal');

    accept_terms_personal.addEventListener('change', function() {
        if (this.checked) {
            confirm_terms_personal.removeAttribute('disabled');
        } else {
            confirm_terms_personal.setAttribute('disabled', 'disabled');
        }
    });
    
    var stepperWizardDefault = document.querySelector('.stepper-form-one');
    var stepperDefault = new Stepper(stepperWizardDefault, {
        animation: true
    });

    var stepperNextButtonDefault = stepperWizardDefault.querySelectorAll('.btn-nxt');
    var stepperPrevButtonDefault = stepperWizardDefault.querySelectorAll('.btn-prev');

    const email = document.getElementById('email');
    const confirm_email = document.getElementById('confirm_email');
    const confirm_email_error = document.getElementById('confirm_email_error');
    const create_account = document.getElementById('create_account');
    const messageuserexist = document.getElementById('messageuserexist');
    const submitBtn = document.getElementById('submitBtn');

    // Configuración del token CSRF para todas las solicitudes AJAX
    function setCsrfToken(xhr) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    }

    // Inicializa FilePond en los campos de entrada de archivo correspondientes
    const quotation_documents = document.querySelector('.quotation_documents');
    FilePond.create(quotation_documents);
    FilePond.setOptions({
        server: {
            url: baseurl + '/upload', // URL de carga
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    });


    function validateStep(step) {
        //get selected radio name mode_of_transport
        const mode_of_transport = document.querySelector('input[name="mode_of_transport"]:checked').value;
    
        const serviceType = document.getElementById('service_type');
        const service_type_error = document.getElementById('service_type_error');
            
        const cargo_type = document.querySelector('input[name="cargo_type"]:checked');
        const cargo_type_error = document.getElementById('cargo_type_error');
    
        const origin_country_id = document.getElementById('origin_country_id');
        const origin_country_id_error = document.getElementById('origin_country_id_error');
    
        const destination_country_id = document.getElementById('destination_country_id');
        const destination_country_id_error = document.getElementById('destination_country_id_error');
    

        //data contact
        const contact_name = document.getElementById('name');
        const contact_name_error = document.getElementById('name_error');

        const contact_lastname = document.getElementById('lastname');
        const contact_lastname_error = document.getElementById('lastname_error');

        const contact_email = document.getElementById('email');
        const contact_email_error = document.getElementById('email_error');

        const contact_phone = document.getElementById('phone');
        const contact_phone_error = document.getElementById('phone_error');


        // Lógica de validación para cada paso
        if (step === '#defaultStep-one') {
    
            service_type_error.textContent = '';
            cargo_type_error.textContent = '';
    
            if(mode_of_transport == 'Ground' || mode_of_transport == 'Container' || mode_of_transport == 'RoRo'){
                if(cargo_type === null){
                    //fucus radio name cargo_type
                    cargo_type_error.textContent = 'Required cargo type.';
                    return false;
                }
            }
    
            if (serviceType.value === '') {
                //focus serviceType
                serviceType.focus();
                service_type_error.textContent = 'Required service type.';
                return false;
            } else {
                // Si el paso se valida correctamente, agregamos la clase 'st-complete' al paso
                stepperWizardDefault.querySelector('[data-target="#defaultStep-one"]').classList.add('st-complete');
            }
    
        } else if (step === '#defaultStep-two') {
            
            origin_country_id_error.textContent = '';
            destination_country_id_error.textContent = '';
            
            if (origin_country_id.value === '') {
                origin_country_id.focus();
                origin_country_id_error.textContent = 'Required origin country.';
                return false;
            }
    
            if(destination_country_id.value === ''){
                destination_country_id.focus();
                destination_country_id_error.textContent = 'Required destination country.';
                return false;
            } else {
                // Si el paso se valida correctamente, agregamos la clase 'st-complete' al paso
                stepperWizardDefault.querySelector('[data-target="#defaultStep-two"]').classList.add('st-complete');
            }
    
        } else if (step === '#defaultStep-three') {

            stepperWizardDefault.querySelector('[data-target="#defaultStep-three"]').classList.add('st-complete');

        } else if (step === '#defaultStep-four') {

            if(contact_name.value === ''){
                contact_name.focus();
                contact_name_error.textContent = 'Required name.';
                return false;
            }

            if(contact_lastname.value === ''){
                contact_lastname.focus();
                contact_lastname_error.textContent = 'Required lastname.';
                return false;
            }

            if(contact_email.value === ''){
                contact_email.focus();
                contact_email_error.textContent = 'Required email.';
                return false;
            }

            if(contact_phone.value === ''){
                contact_phone.focus();
                contact_phone_error.textContent = 'Required phone.';
                return false;
            } else {
                stepperWizardDefault.querySelector('[data-target="#defaultStep-four"]').classList.add('st-complete');
            }
        }
        // Agrega más lógica de validación según sea necesario
    
        return true;
    }
    

    // Agrega eventos de clic para los botones de siguiente y anterior
    stepperNextButtonDefault.forEach(element => {
        element.addEventListener('click', function () {
            // Obtiene el ID del paso actual
            var currentStepId = stepperWizardDefault.querySelector('.bs-stepper-header .step.active').getAttribute('data-target');

            // Valida el paso actual antes de avanzar
            if (!validateStep(currentStepId)) {
                return;
            }

            // Avanza al siguiente paso
            stepperDefault.next();
        });
    });

    stepperPrevButtonDefault.forEach(element => {
        element.addEventListener('click', function () {
            const completedSteps = stepperWizardDefault.querySelectorAll('.st-complete');

            if(completedSteps.length > 0){
                var lastCompletedStep = completedSteps[completedSteps.length - 1];
                lastCompletedStep.classList.remove('st-complete');
            }



            // Retrocede al paso anterior
            stepperDefault.previous();


        });
    });


    //remove disabled in class="step st-complete" for click and return to previous step
    stepperWizardDefault.querySelectorAll('.step').forEach(element => {
        element.addEventListener('click', function () {
            if (element.classList.contains('st-complete')) {
                
                const completedSteps = stepperWizardDefault.querySelectorAll('.st-complete');

                if(completedSteps.length > 0){
                    var lastCompletedStep = completedSteps[completedSteps.length - 1];
                    lastCompletedStep.classList.remove('st-complete');
                }

                // Retroceder un paso
                stepperDefault.previous();
            }
        });
    });

    //mach email and confirm email
    email.addEventListener('keyup', function () {
        if (email.value !== confirm_email.value) {
            confirm_email_error.textContent = 'Emails do not match.';
            create_account.checked = false;
            create_account.disabled = true;
            messageuserexist.textContent = '';
            submitBtn.disabled = true;
        } else {
            confirm_email_error.textContent = '';
            create_account.disabled = false;
            submitBtn.disabled = false;
        }
    });

    confirm_email.addEventListener('keyup', function () {
        if (email.value !== confirm_email.value) {
            confirm_email_error.textContent = 'Emails do not match.';
            create_account.checked = false;
            create_account.disabled = true;
            messageuserexist.textContent = '';
            submitBtn.disabled = true;
        } else {
            confirm_email_error.textContent = '';
            create_account.disabled = false;
            submitBtn.disabled = false;
        }
    });


    //if checked create_account
    create_account.addEventListener('change', function () {
        if (create_account.checked) {
            // Verificar si el correo electrónico existe en la base de datos y mostrar un mensaje de error a través de la URL /verify-email-register
            fetch('/verify-email-register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: email.value
                })
            })
            .then(response => response.json())
            .then(data => {
                // Verificar la respuesta del servidor
                if (data.status === 'error') {
                    // Si hay un error, mostrar el mensaje de error
                    messageuserexist.textContent = data.message;
                    messageuserexist.classList.add('text-danger');
                    submitBtn.disabled = true;
                } else {
                    // Si no hay error, limpiar el mensaje de error
                    messageuserexist.textContent = data.message;
                    messageuserexist.classList.remove('text-danger');
                    messageuserexist.classList.add('text-success');
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                // Capturar y manejar errores de red u otros errores
                console.error('Error:', error);
            });
        } else {
            messageuserexist.textContent = '';
            submitBtn.disabled = false;
        }
    });
    

    document.getElementById('form_quotations').addEventListener('submit', function(event) {
        event.preventDefault();
    
        const submitBtn = document.getElementById('submitBtn');
        const loadingSpinner = document.getElementById('loadingSpinner');

        if (!validateStep('#defaultStep-four')) {
            return;
        }
    
        // Deshabilitar el botón y mostrar el mensaje de carga
        submitBtn.disabled = true;
        loadingSpinner.style.display = 'block';
    
        const formData = new FormData(document.getElementById('form_quotations'));
        const xhr = new XMLHttpRequest();
    
        xhr.open('POST', '/quotationsonlinestore');
        setCsrfToken(xhr);
    
        xhr.onload = function() {
            // Restaurar el botón y ocultar el mensaje de carga
            submitBtn.disabled = false;
            loadingSpinner.style.display = 'none';
    
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    submitBtn.disabled = true;
                    loadingSpinner.style.display = 'block';
                    window.location.href = 'https://www.latinamericancargo.com/thank-you/?ref=lac-app-form';
                } else {
                    console.error('...Error inesperado en la respuesta:', xhr.responseText);
                }
            } else if (xhr.status === 422) {
                const data = JSON.parse(xhr.responseText);
                if (data.errors) {
                    displayValidationErrors(data.errors);
                } else {
                    console.error('...Error de validación en la respuesta:', xhr.responseText);
                }
            } else {
                console.error('...Error al enviar la solicitud:', xhr.statusText);
            }
    
        };
    
        xhr.onerror = function() {
            // Restaurar el botón y ocultar el mensaje de carga en caso de error
            submitBtn.disabled = false;
            loadingSpinner.style.display = 'none';
            console.error('Error al enviar la solicitud:', xhr.statusText);
        };
    
        xhr.send(formData);
    });

    var listcargodetails_electrvehi = document.getElementById('listcargodetails');

    listcargodetails_electrvehi.addEventListener('change', function(event) {
        if (event.target.classList.contains('electric-vehicle-checkbox')) {
            var confirm_electricvehicle_modal = new bootstrap.Modal(document.getElementById('confirm-electricvehicle-modal'));
    
            // Muestra u oculta el modal según si el checkbox está marcado o no
            if (event.target.checked) {
                confirm_electricvehicle_modal.show();
                //add class 'disabled' in <a class="btn-nxt"></a> in this step
                stepperWizardDefault.querySelector('#defaultStep-three .btn-nxt').classList.add('disabled');
            } else {
                confirm_electricvehicle_modal.hide();
                //remove class 'disabled' in <a class="btn-nxt"></a> in this step
                stepperWizardDefault.querySelector('#defaultStep-three .btn-nxt').classList.remove('disabled');
            }
        }
    });


});
