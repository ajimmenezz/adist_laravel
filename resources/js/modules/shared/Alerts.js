import Swal from "sweetalert2";
import 'sweetalert2/src/sweetalert2.scss';

class Alerts {
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
}

export default Alerts;