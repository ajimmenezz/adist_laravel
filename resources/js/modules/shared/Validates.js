class Validate {
    constructor() {
        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });

        const element = document.querySelector('form');
        element.addEventListener('submit', event => {
            event.preventDefault();
        });
    }

    currencyFormat(num) {
        return "$" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    mail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }

    form(form_id) {
        let valid = true;
        $(form_id + " .form-control").each(function() {
            const elem = $(this);
            const type = elem.attr("type");
            const value = elem.val().trim();

            elem.removeClass("is-invalid");
            elem.removeClass("is-valid");

            if (
                (elem.attr("required") && value == "") ||
                (type == "email" && !this.mail(value))
            ) {
                elem.addClass("is-invalid");
                valid = false;
            } else {
                elem.addClass("is-valid");
            }
        });
        return valid;
    }

    form_reset(form_id) {
        $(form_id + " .form-control").each(function() {
            const elem = $(this);
            elem.removeClass("is-invalid");
            elem.removeClass("is-valid");
        });

        $(form_id).removeClass("was-validated");
    }
}

export default Validate;