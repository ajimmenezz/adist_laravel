import Swal from "sweetalert2";
import 'sweetalert2/src/sweetalert2.scss';

import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

class Alerts {
    constructor() {
        toastr.options = {
            closeButton: true,
            positionClass: 'toast-top-center',
            preventDuplicates: true,
            progressBar: true,
            showDuration: 400,
            hideDuration: 1000,
            timeOut: 7000,
            extendedTimeOut: 1000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'slideDown',
            hideMethod: 'slideUp',
            tapToDismiss: true
        };
    }

    error(message = '', timer = null, callback = function() {}) {
        Swal.fire({
            icon: "error",
            text: message,
            timer: timer,
        }).then(function() {
            callback();
        });
    }

    success(message = '', timer = null, callback = function() {}) {
        Swal.fire({
            icon: "success",
            text: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            timer: timer,
        }).then(function() {
            callback();
        });
    }

    toast(message = '', type = 'success') {
        toastr[type](message);
    }

    confirm(message = '', callback = function() {}) {
        Swal.fire({
            icon: "warning",
            text: message,
            showCancelButton: true,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
        }).then(function(result) {
            if (result.value) {
                callback();
            }
        });
    }

}

export default Alerts;