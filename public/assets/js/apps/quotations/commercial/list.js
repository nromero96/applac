// Define la variable columnsVisibility
var columnsVisibility = JSON.parse(localStorage.getItem('columnsVisibility')) || [0, 1, 2, 3, 4, 5, 6, 7, 8];

var invoiceList = $('#invoice-list').DataTable({
    "dom": "<'inv-list-top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'l<'dt-action-buttons align-self-center'B>><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f<'toolbar align-self-center'>>>>" +
        "<'table-responsive'tr>" +
        "<'inv-list-bottom-section d-sm-flex justify-content-sm-between text-center'<'inv-list-pages-count  mb-sm-0 mb-3'i><'inv-list-pagination'p>>",
        "ordering": false,
    buttons: [
        {
            text: 'New Quote',
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
    "lengthMenu": [20, 30, 50, 100],
    "pageLength": 20,


});


document.addEventListener('DOMContentLoaded', function () {


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

});

