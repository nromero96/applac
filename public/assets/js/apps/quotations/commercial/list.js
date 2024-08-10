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

