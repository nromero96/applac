document.addEventListener('DOMContentLoaded', function () {

    //modal confirm-percomp-modal auto open javascript puro
    var confirm_percomp_modal = new bootstrap.Modal(document.getElementById('confirm-percomp-modal'));
    confirm_percomp_modal.show();

    var dv_options_best = document.getElementById('options_best');
    var dv_options_best_personal = document.getElementById('options_best_personal');

    // Función para cambiar la URL y enviar evento a GTM
    function changeURLAndSendEvent(stepId) {
        var stepName = '';
        switch (stepId) {
            case '#defaultStep-one':
                stepName = 'transport';
                break;
            case '#defaultStep-two':
                stepName = 'location';
                break;
            case '#defaultStep-three':
                stepName = 'cargo';
                break;
            case '#defaultStep-four':
                stepName = 'contact';
                break;
            default:
                stepName = 'unknown';
                break;
        }

        // Cambiar la URL sin recargar la página
        var newURL = window.location.origin + window.location.pathname + '#' + stepName;
        history.pushState(null, null, newURL);

        // Enviar evento a GTM
        dataLayer.push({
            'event': 'stepCompleted',
            'stepName': stepName
        });
    }


    //if click radio name options_best
    var options_best = document.getElementsByName('customer_type');

    for (var i = 0; i < options_best.length; i++) {
        options_best[i].addEventListener('change', function() {
            if (this.value == 'Individual') {
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
        /* Data mode of transport */
        const mode_of_transport = document.querySelector('input[name="mode_of_transport"]:checked').value;

        const serviceType = document.getElementById('service_type');
        const service_type_error = document.getElementById('service_type_error');

        const cargo_type = document.querySelector('input[name="cargo_type"]:checked');
        const cargo_type_error = document.getElementById('cargo_type_error');

        /* Data location */


        /* Data cargo details */
        const package_types = document.querySelectorAll('select[name="package_type[]"]');
        const package_type_error = document.getElementById('package_type_error');

        const package_qtys = document.querySelectorAll('input[name="qty[]"]');
        const package_qty_error = document.getElementById('package_qty_error');

        const package_lengths = document.querySelectorAll('input[name="length[]"]');
        const package_widths = document.querySelectorAll('input[name="width[]"]');
        const package_heights = document.querySelectorAll('input[name="height[]"]');
        const package_dimensions_error = document.getElementById('package_dimensions_error');

        const package_per_pieces = document.querySelectorAll('input[name="per_piece[]"]');
        const package_per_pieces_error = document.getElementById('package_per_piece_error');

        const package_item_total_weights = document.querySelectorAll('input[name="item_total_weight[]"]');
        const package_item_total_weights_error = document.getElementById('package_item_total_weight_error');

        const package_details_shipments = document.querySelectorAll('textarea[name="details_shipment[]"]');
        const package_details_shipments_error = document.getElementById('package_details_shipments_error');

        const package_temperatures = document.querySelectorAll('input[name="temperature[]"]');
        const package_temperatures_error = document.getElementById('package_temperatures_error');

        const package_cargo_descriptions = document.querySelectorAll('input[name="cargo_description[]"]');
        const package_cargo_descriptions_error = document.getElementById('package_cargo_description_error');

        const shipping_date = document.getElementById('shipping_date');
        const no_shipping_date = document.getElementById('no_shipping_date');
        const shipping_date_error = document.getElementById('shipping_date_error');

        const declared_value = document.getElementById('declared_value');
        const declared_value_error = document.getElementById('declared_value_error');

        /* Data contact */
        const contactName = document.getElementById('name');
        const contactNameError = document.getElementById('name_error');

        const contactLastname = document.getElementById('lastname');
        const contactLastnameError = document.getElementById('lastname_error');

        const contactCompanyName = document.getElementById('company_name');
        const contactCompanyNameError = document.getElementById('company_name_error');

        const contactEmail = document.getElementById('email');
        const contactEmailError = document.getElementById('email_error');

        const contactLocation = document.getElementById('location');
        const contactLocationError = document.getElementById('location_error');

        const contactPhone = document.getElementById('phone');
        const contactPhoneError = document.getElementById('phone_error');


        // Lógica de validación para cada paso
        if (step === '#defaultStep-one') {

            service_type_error.textContent = '';
            cargo_type_error.textContent = '';

            if(mode_of_transport == 'Ground' || mode_of_transport == 'Container' || mode_of_transport == 'RoRo'){
                if(cargo_type === null){
                    cargo_type_error.textContent = 'Required cargo type.';
                    return false;
                }
            }

            if (serviceType.value === '') {
                serviceType.focus();
                service_type_error.textContent = 'Required service type.';
                return false;
            } else {
                stepperWizardDefault.querySelector('[data-target="#defaultStep-one"]').classList.add('st-complete');
            }

        } else if (step === '#defaultStep-two') {

            // Limpiar mensajes de error
            const errorFields = [
                'origin_country_id_error',
                'destination_country_id_error',
                'origin_city_error',
                'origin_state_id_error',
                'origin_zip_code_error',
                'destination_city_error',
                'destination_state_id_error',
                'destination_zip_code_error',
                'destination_airportorport_error',
                'origin_airportorport_error'
            ];

            errorFields.forEach(field => document.getElementById(field).textContent = '');

            // Validaciones comunes
            const validations = [
                { field: 'origin_country_id', error: 'Required origin country.' },
                { field: 'destination_country_id', error: 'Required destination country.' }
            ];

            const serviceValidations = {
                'Door-to-Door': [
                    { field: 'origin_city', error: 'Required origin city.' },
                    { field: 'origin_state_id', error: 'Required origin state.' },
                    { field: 'origin_zip_code', error: 'Required origin zip code.', optional: true },
                    { field: 'destination_city', error: 'Required destination city.' },
                    { field: 'destination_state_id', error: 'Required destination state.' },
                    { field: 'destination_zip_code', error: 'Required destination zip code.', optional: true }
                ],
                'Door-to-Airport': [
                    { field: 'origin_city', error: 'Required origin city.' },
                    { field: 'origin_state_id', error: 'Required origin state.' },
                    { field: 'origin_zip_code', error: 'Required origin zip code.', optional: true },
                    { field: 'destination_airportorport', error: 'Required destination airport.' }
                ],
                'Airport-to-Door': [
                    { field: 'origin_airportorport', error: 'Required origin airport.' },
                    { field: 'destination_city', error: 'Required destination city.' },
                    { field: 'destination_state_id', error: 'Required destination state.' },
                    { field: 'destination_zip_code', error: 'Required destination zip code.', optional: true }
                ],
                'Airport-to-Airport': [
                    { field: 'origin_airportorport', error: 'Required origin airport.' },
                    { field: 'destination_airportorport', error: 'Required destination airport.' }
                ],
                'Door-to-Port': [
                    { field: 'origin_city', error: 'Required origin city.' },
                    { field: 'origin_state_id', error: 'Required origin state.' },
                    { field: 'origin_zip_code', error: 'Required origin zip code.', optional: true },
                    { field: 'destination_airportorport', error: 'Required destination port.' }
                ],
                'Port-to-Door': [
                    { field: 'origin_airportorport', error: 'Required origin port.' },
                    { field: 'destination_city', error: 'Required destination city.' },
                    { field: 'destination_state_id', error: 'Required destination state.' },
                    { field: 'destination_zip_code', error: 'Required destination zip code.', optional: true }
                ],
                'Port-to-Port': [
                    { field: 'origin_airportorport', error: 'Required origin port.' },
                    { field: 'destination_airportorport', error: 'Required destination port.' }
                ],
                'Door-to-CFS/Port': [
                    { field: 'origin_city', error: 'Required origin city.' },
                    { field: 'origin_state_id', error: 'Required origin state.' },
                    { field: 'origin_zip_code', error: 'Required origin zip code.', optional: true },
                    { field: 'destination_airportorport', error: 'Required destination CFS/Port.' }
                ],
                'CFS/Port-to-Door': [
                    { field: 'origin_airportorport', error: 'Required origin CFS/Port.' },
                    { field: 'destination_city', error: 'Required destination city.' },
                    { field: 'destination_state_id', error: 'Required destination state.' },
                    { field: 'destination_zip_code', error: 'Required destination zip code.', optional: true }
                ],
                'CFS/Port-to-CFS/Port': [
                    { field: 'origin_airportorport', error: 'Required origin CFS/Port.' },
                    { field: 'destination_airportorport', error: 'Required destination CFS/Port.' }
                ]
            };

            // Función para validar campos
            function validateFields(fields) {
                const originCountry = parseInt(document.getElementById('origin_country_id').value);
                const destinationCountry = parseInt(document.getElementById('destination_country_id').value);

                for (const { field, error, optional } of fields) {
                    const element = document.getElementById(field);

                    // Validar zip_code solo para los países 38 o 231
                    if (optional && (
                        (field === 'origin_zip_code' && (originCountry !== 38 && originCountry !== 231)) ||
                        (field === 'destination_zip_code' && (destinationCountry !== 38 && destinationCountry !== 231))
                    )) {
                        continue;
                    }

                    if (element.value === '') {
                        element.focus();
                        document.getElementById(`${field}_error`).textContent = error;
                        return false;
                    }
                }
                return true;
            }

            // Validar campos comunes
            if (!validateFields(validations)) return false;

            // Validar campos según el tipo de servicio
            const serviceFields = serviceValidations[serviceType.value];
            if (serviceFields && !validateFields(serviceFields)) return false;

            stepperWizardDefault.querySelector('[data-target="#defaultStep-two"]').classList.add('st-complete');


        } else if (step === '#defaultStep-three') {

            // Limpiar mensajes de error
            const errorFields = [
                'package_type_error',
                'package_qty_error',
                'package_dimensions_error',
                'package_per_piece_error',
                'shipping_date_error',
                'declared_value_error',
                'package_item_total_weight_error',
                'package_details_shipments_error',
                'package_temperatures_error'
            ];

            errorFields.forEach(field => document.getElementById(field).textContent = '');

            //package_type[] es un selectoption debe estar seleccionado al menos uno
            let allSelected_pt = true;
            package_types.forEach(function(package_type) {
                if (package_type.value === '' || package_type.value === null) {
                    allSelected_pt = false;
                    package_type.focus();
                }
            });

            if (!allSelected_pt) {
                package_type_error.textContent = 'Required Package/Cargo Type selected.';
                return false;
            }

            //package_qtys[] es un input debe tener un valor
            let allContent_pq = true;
            package_qtys.forEach(function(package_qty) {
                if (package_qty.value === '' || package_qty.value === null) {
                    allContent_pq = false;
                    package_qty.focus();
                }
            });

            if (!allContent_pq) {
                package_qty_error.textContent = 'Required Package/Quantity.';
                return false;
            }

            //package_lengths[], package_widths[], package_heights[] son inputs deben tener un valor
            let allContent_pd = true;
            package_lengths.forEach(function(package_length) {
                if (package_length.value === '' || package_length.value === null) {
                    allContent_pd = false;
                    package_length.focus();
                }
            });

            package_widths.forEach(function(package_width) {
                if (package_width.value === '' || package_width.value === null) {
                    allContent_pd = false;
                    package_width.focus();
                }
            });

            package_heights.forEach(function(package_height) {
                if (package_height.value === '' || package_height.value === null) {
                    allContent_pd = false;
                    package_height.focus();
                }
            });

            if (!allContent_pd) {
                package_dimensions_error.textContent = 'Required Package/Dimensions.';
                return false;
            }

            //package_per_pieces[] es un input debe tener un valor
            let allContent_pp = true;
            package_per_pieces.forEach(function(package_per_piece) {
                if (package_per_piece.value === '' || package_per_piece.value === null) {
                    allContent_pp = false;
                    package_per_piece.focus();
                }
            });

            if (!allContent_pp) {
                package_per_pieces_error.textContent = 'Required Package/Per Pieces.';
                return false;
            }

            //package_item_total_weight es un input debe tener un valor
            let allContent_pitw = true;
            package_item_total_weights.forEach(function(package_item_total_weight) {
                if (package_item_total_weight.value === '' || package_item_total_weight.value === null) {
                    allContent_pitw = false;
                    package_item_total_weight.focus();
                }
            });

            if (!allContent_pitw) {
                package_item_total_weights_error.textContent = 'Required Package/Item Total Weight.';
                return false;
            }

            // package_details_shipments es un textarea debe tener un valor
            let allContent_pds = true;

            package_details_shipments.forEach(function(package_details_shipment, index) {
                const package_type = package_types[index]; // Obtener el package_type correspondiente a esta fila

                if (package_type.value === 'Flatbed' ||
                    package_type.value === 'Double Drop' ||
                    package_type.value === 'Step Deck' ||
                    package_type.value === 'RGN/Lowboy' ||
                    package_type.value === 'Other' ||
                    package_type.value === '20\' Flat Rack' ||
                    package_type.value === '40\' Flat Rack' ||
                    package_type.value === '40\' Flat Rack High Cube' ||
                    package_type.value === '20\' Open Top' ||
                    package_type.value === '40\' Open Top' ||
                    package_type.value === '40\' Open Top High Cube') {

                    if (package_details_shipment.value === '' || package_details_shipment.value === null) {
                        allContent_pds = false;
                        package_details_shipment.focus();
                    }
                }
            });

            if (!allContent_pds) {
                package_details_shipments_error.textContent = 'Required Package/Details Shipments.';
                return false;
            }

            // package_temperatures es un input debe tener un valor
            let allContent_ptemp = true;

            package_temperatures.forEach(function(package_temperature, index) {
                const package_type = package_types[index]; // Obtener el package_type correspondiente a esta fila

                if (package_type.value === '48 / 53 Ft Reefer Trailer' ||
                    package_type.value === '20\' Reefer Standard' ||
                    package_type.value === '40\' Reefer Standard' ||
                    package_type.value === '40\' Reefer High Cube') {

                    if (package_temperature.value === '' || package_temperature.value === null) {
                        allContent_ptemp = false;
                        package_temperature.focus();
                    }
                }
            });

            if (!allContent_ptemp) {
                package_temperatures_error.textContent = 'Required Package/Temperature.';
                return false;
            }

            // cargo_description[] es un input debe tener un valor
            let allContent_pcd = true;
            package_cargo_descriptions.forEach(function(package_cargo_description, index) {
                //no hay requerimientos solo verificar si tiene valor
                if (package_cargo_description.value === '' || package_cargo_description.value === null) {
                    allContent_pcd = false;
                    package_cargo_description.focus();
                }
            });

            if (!allContent_pcd) {
                package_cargo_descriptions_error.textContent = 'Required Package/Cargo Description.';
                return false;
            }

            //shipping_date es un input debe tener un valor
            if (shipping_date.value === '' && no_shipping_date.checked === false) {
                shipping_date.focus();
                shipping_date_error.textContent = 'Required shipping date.';
                return false;
            }

            //declared_value es un input debe tener un valor
            if (declared_value.value === '') {
                declared_value.focus();
                declared_value_error.textContent = 'Required declared value.';
                return false;
            }else {
                stepperWizardDefault.querySelector('[data-target="#defaultStep-three"]').classList.add('st-complete');
            }

        } else if (step === '#defaultStep-four') {

            // Limpiar mensajes de error
            const errorFields = [
                'name_error',
                'lastname_error',
                'company_name_error',
                'email_error',
                'location_error',
                'phone_error'
            ];

            errorFields.forEach(field => document.getElementById(field).textContent = '');

            // Validar campos
            if (contactName.value === '') {
                contactName.focus();
                contactNameError.textContent = 'Required name.';
                return false;
            }

            if (contactLastname.value === '') {
                contactLastname.focus();
                contactLastnameError.textContent = 'Required lastname.';
                return false;
            }

            // contact_company_name solo es validado cuando cargo_type es diferente a 'Personal Vehicle'
            const cargo_type_value = cargo_type ? cargo_type.value : '';

            console.log('Modtransport: ' + mode_of_transport);
            if (!(mode_of_transport === 'RoRo' && cargo_type_value === 'Personal Vehicle')) {
                console.log('CargoType: ' + cargo_type_value);
                if (contactCompanyName.value === '') {
                    contactCompanyName.focus();
                    contactCompanyNameError.textContent = 'Required company name.';
                    return false;
                }
            }

            if (contactEmail.value === '') {
                contactEmail.focus();
                contactEmailError.textContent = 'Required email.';
                return false;
            }

            if (contactLocation.value === '') {
                contactLocation.focus();
                contactLocationError.textContent = 'Required location.';
                return false;
            }

            if (contactPhone.value === '') {
                contactPhone.focus();
                contactPhoneError.textContent = 'Required phone.';
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

            // Obtener el siguiente paso activo
            var nextStep = stepperWizardDefault.querySelector('.bs-stepper-header .step.active');
            if (nextStep) {
                var nextStepId = nextStep.getAttribute('data-target');
                changeURLAndSendEvent(nextStepId);
            }

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

            // Obtener el paso anterior activo
            var prevStep = stepperWizardDefault.querySelector('.bs-stepper-header .step.active');
            if (prevStep) {
                var prevStepId = prevStep.getAttribute('data-target');
                changeURLAndSendEvent(prevStepId);
            }


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


    const radioCards = document.querySelectorAll('.radio-card input[type="radio"]');
    const updateCheckedState = () => {
        radioCards.forEach(radio => {
            if (radio.checked) {
                radio.parentNode.classList.add('active');
            } else {
                radio.parentNode.classList.remove('active');
            }
        });
    };

    radioCards.forEach(radio => {
        radio.addEventListener('change', updateCheckedState);
    });

    // Inicializa el estado al cargar la página
    updateCheckedState();



    // Función para inicializar la URL al cargar la página
    function initializeURL() {
        var activeStep = stepperWizardDefault.querySelector('.bs-stepper-header .step.active');
        if (activeStep) {
            var currentStepId = activeStep.getAttribute('data-target');
            changeURLAndSendEvent(currentStepId);
        }
    }

    // Llamada inicial para configurar la URL al cargar la página
    initializeURL();


});
