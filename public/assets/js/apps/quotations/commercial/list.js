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


    const slider = document.querySelector('.drag-scroll');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        // Verifica si el elemento clicado es un <select>, <a>, u otro interactivo
        if (e.target.tagName === 'SELECT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') {
            return; // No hacer nada si es un elemento interactivo
        }

        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
        e.preventDefault();  // Evita el comportamiento predeterminado
    });

    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });

    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();  // Evita el desplazamiento vertical

        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; // Ajusta la velocidad si es necesario
        slider.scrollLeft = scrollLeft - walk;
    });

    const tableContainer = document.querySelector('.drag-scroll');
    const stickyColumn = document.querySelectorAll('.sticky-column');
    tableContainer.addEventListener('scroll', () => {
        if (tableContainer.scrollLeft > 0) {
            stickyColumn.forEach(column => {
                column.classList.add('has-shadow');
            });
        } else {
            stickyColumn.forEach(column => {
                column.classList.remove('has-shadow');
            });
        }
    });


    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', (event) => {
            const svgIcon = document.getElementById('icon' + event.target.id.replace('checkbox', ''));
            svgIcon.classList.toggle('checked', event.target.checked);
        });
    });



});

