document.addEventListener('DOMContentLoaded', function () {
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
            stepperWizardDefault.querySelector('[data-target="#defaultStep-four"]').classList.add('st-complete');
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
        } else {
            confirm_email_error.textContent = '';
            create_account.disabled = false; 
        }
    });

    confirm_email.addEventListener('keyup', function () {
        if (email.value !== confirm_email.value) {
            confirm_email_error.textContent = 'Emails do not match.';
            create_account.checked = false;
            create_account.disabled = true;
            messageuserexist.textContent = '';
        } else {
            confirm_email_error.textContent = '';
            create_account.disabled = false;
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
                } else {
                    // Si no hay error, limpiar el mensaje de error
                    messageuserexist.textContent = data.message;
                    messageuserexist.classList.remove('text-danger');
                    messageuserexist.classList.add('text-success');
                }
            })
            .catch(error => {
                // Capturar y manejar errores de red u otros errores
                console.error('Error:', error);
            });
        } else {
            messageuserexist.textContent = '';
        }
    });
    

});
