class Modals {
    constructor(id) {
        this.myModal = new bootstrap.Modal("#" + id);
        this.myModal2 = document.getElementById(id);
        this.id = id;
    }

    show() {
        this.myModal.show();
    }

    hide() {
        this.myModal.hide();
    }

    onhide(callback = () => {}) {
        this.myModal2.addEventListener('hide.bs.modal', function(event) {
            callback();
        });
    }

    showSuccess(message, time, callback = () => {}) {
        let id = this.id;

        $("#" + id + " .modal_success_message").empty().append(message);
        $("#" + id + " .alert-success").removeClass("d-none");
        window.setTimeout(function() {
            $("#" + id + " .alert-success").addClass("d-none");
            $("#" + id + " .modal_success_message").empty();
            callback();
        }, time);
    }

    showError(message, time, callback = () => {}) {
        let id = this.id;

        $("#" + id + " .modal_error_message").empty().append(message);
        $("#" + id + " .alert-danger").removeClass("d-none");

        window.setTimeout(function() {
            $("#" + id + " .alert-danger").addClass("d-none");
            $("#" + id + " .modal_error_message").empty();
            callback();
        }, time);
    }
}

export default Modals;