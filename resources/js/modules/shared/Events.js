import Alerts from "./Alerts";

class Events {

    loading() {
        $(".loading").removeClass("d-none");
    }

    loaded() {
        $(".loading").addClass("d-none");
    }

    post(url, data, doneCallback = null, failCallback = null, autoHideLoading = true) {
        let _this = this;
        $.ajax({
            url: url,
            method: "post",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            dataType: "json",
            beforeSend: function () {
                _this.loading();
            },
        }).done(function (r) {
            if (doneCallback != null) {
                doneCallback(r);
            }
            if (autoHideLoading == true) {
                _this.loaded();
            }

        })
            .fail(function (xhr, textStatus, errorThrown) {
                _this.loaded();
                if (failCallback !== null) {
                    failCallback(textStatus);
                }
            });
    }

    api(url, method = 'post', data = {}, headers = {}, doneCallback = function () { }) {
        const _this = this;
        const alerts = new Alerts();
        $.ajax({
            url: url,
            method: method,
            headers: headers,
            data: data,
            dataType: "json",
            beforeSend: function () {
                _this.loading();
            },
        }).done(function (r) {
            doneCallback(r);
            _this.loaded();
        }).fail(function (xhr, textStatus, errorThrown) {
            const message = xhr.responseJSON.message || 'Error desconocido, recargue la página e intente nuevamente.';
            alerts.toast(message, 'error');
            // toastr.error(message);
            _this.loaded();
        });
    }

}

export default Events;