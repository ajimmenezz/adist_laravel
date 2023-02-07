class Buttons {
    initClic(elem, callback = (btn) => {}) {
        $(elem).off('click').on('click', function() {
            callback($(this));
        });
    }
}

export default Buttons;