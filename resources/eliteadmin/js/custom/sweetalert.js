$(document).ready(function () {
    const Swal = require('sweetalert2');

    let optionsAlert = {
        title: 'Sistema',
        html:'Información para el usuario.',
        customClass: {
            confirmButton: 'btn btn-info mx-1',
            cancelButton: 'btn btn-danger mx-1'
        },
        buttonsStyling: false
    };

    window.swalAlertConfirm = function (message, title) {
        let selectOptions = {
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'confirmar',
                cancelButtonText: 'cancelar',
                html: message,
            },
            currentOptions = $.extend(true, optionsAlert, selectOptions);

        return Swal.fire(currentOptions);
    }

    window.swalAlertInfo = function (message, title) {
        let selectOptions = {
                title: title,
                icon: 'info',
                showCancelButton: false,
                confirmButtonText: 'Ok',
                html: message,
            },
            currentOptions = $.extend(true, optionsAlert, selectOptions);

        return Swal.fire(currentOptions);
    }

    window.swalAlertSuccessQR = function (message, title, colorClass) {
        let siactivebuttom = true;
        let selectOptions = {
                title: title,
                icon: 'success',
                showCancelButton: false,
                confirmButtonText: 'Siguiente QR',
                html: message,
                /* timer: 2500 */
                customClass: {
                    container: 'custom-swal-qrsucces',
                    confirmButton: 'btn btn-success mx-1',
                    icon: colorClass
                },
            },
            currentOptions = $.extend(true, optionsAlert, selectOptions);

        return Swal.fire(currentOptions);
    }
    window.swalAlertWarningQR = function (message, title) {
        let selectOptions = {
                title: title,
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'Scanear Nuevamente',
                html: message,
                /* timer: 3500 */
                customClass: {
                    confirmButton: 'btn btn-warning mx-1',
                },
            },
            currentOptions = $.extend(true, optionsAlert, selectOptions);

        return Swal.fire(currentOptions);
    }
});
