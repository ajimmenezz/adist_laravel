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
                beforeSend: function() {
                    _this.loading();
                },
            }).done(function(r) {
                if (doneCallback != null) {
                    doneCallback(r);
                }
                if (autoHideLoading == true) {
                    _this.loaded();
                }

            })
            .fail(function(xhr, textStatus, errorThrown) {
                _this.loaded();
                if (failCallback !== null) {
                    failCallback(textStatus);
                }
            });
    }

}

export default Events;