document.addEventListener('DOMContentLoaded', function () {
    var stepperWizardDefault = document.querySelector('.stepper-form-one');
    var stepperDefault = new Stepper(stepperWizardDefault, {
        animation: true
    });
    var stepperNextButtonDefault = stepperWizardDefault.querySelectorAll('.btn-nxt');
    var stepperPrevButtonDefault = stepperWizardDefault.querySelectorAll('.btn-prev');

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
    
    


});
