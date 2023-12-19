// Define la variable columnsVisibility
var columnsVisibility = JSON.parse(localStorage.getItem('columnsVisibility')) || [0, 1, 2, 3, 4, 5, 6, 7, 8];

var invoiceList = $('#invoice-list').DataTable({
    "dom": "<'inv-list-top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'l<'dt-action-buttons align-self-center'B>><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f<'toolbar align-self-center'>>>>" +
        "<'table-responsive'tr>" +
        "<'inv-list-bottom-section d-sm-flex justify-content-sm-between text-center'<'inv-list-pages-count  mb-sm-0 mb-3'i><'inv-list-pagination'p>>",
        "ordering": false,
    buttons: [
        {
            text: 'Add New',
            className: 'btn btn-primary',
            action: function(e, dt, node, config ) {
                window.open(baseurl+'/quotations-onlineregister', '_blank');
            }
        }
    ],
    "oLanguage": {
        "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
        "sInfo": "Showing page _PAGE_ of _PAGES_",
        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        "sSearchPlaceholder": "Search...",
        "sLengthMenu": "Results :  _MENU_",
    },
    "stripeClasses": [],
    "lengthMenu": [7, 10, 20, 50],
    "pageLength": 10,


});


document.addEventListener('DOMContentLoaded', function () {
    // Inicializar TomSelect
    document.querySelectorAll('.user-select').forEach(function (select) {
        var cotizacionId = select.dataset.cotizacionId;

        var tomSelect = new TomSelect(select, {
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            load: function (query, callback) {
                if (!query.length) return callback();
                fetch('/searchuserstoassign?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => callback(data));
            },
            preload: true,
            items: [select.value],
            onChange: function (values) {
                // Capturar el ID del usuario seleccionado
                var userId = values[0];

                alert('Asignar la cotización #' + cotizacionId + ' al usuario #' + userId);

                // Realizar una solicitud AJAX para asignar el usuario
                fetch("/quotations/" + cotizacionId + "/assignuser", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ user_id: userId })
                })
                .then(response => response.json())
                .then(data => {
                    // Puedes manejar la respuesta si es necesario
                    console.log(data);
                })
                .catch(error => {
                    // Puedes manejar el error si es necesario
                    console.error(error);
                });
            }
        });
    });
});

