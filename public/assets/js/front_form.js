document.addEventListener('DOMContentLoaded', function () {
    var stepperWizardDefault = document.querySelector('.stepper-form-one');
    var stepperDefault = new Stepper(stepperWizardDefault, {
        animation: true
    });
    var stepperNextButtonDefault = stepperWizardDefault.querySelectorAll('.btn-nxt');
    var stepperPrevButtonDefault = stepperWizardDefault.querySelectorAll('.btn-prev');

    function validateStep(step) {

        // Lógica de validación para cada paso
        if (step === '#defaultStep-one') {
            //validar select id service_type
        
            const serviceType = document.getElementById('service_type').value;
            if (serviceType === '') {
                alert('Por favor, selecciona un tipo de servicio.');
                return false;
            }

        } else if (step === '#defaultStep-two') {
            // Validación para el segundo paso
            // Por ejemplo, verifica que se haya seleccionado un país de origen
            const originCountry = document.getElementById('origin_country_id').value;
            if (originCountry === '') {
                alert('Por favor, selecciona un país de origen.');
                return false;
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
            stepperDefault.previous();
        });
    });

    // ... (otros eventos o funciones que puedas tener)

});
