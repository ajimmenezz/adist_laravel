class Buttons {
    initClic(elem, callback = (btn) => { }) {
        $(elem).off('click').on('click', function () {
            callback($(this));
        });
    }

    initHover(elem, hover = (btn) => { }, leave = (btn) => { }) {
        $(elem).off('mouseenter').on('mouseenter', function () {
            hover($(this));
        });

        $(elem).off('mouseleave').on('mouseleave', function () {
            leave($(this));
        });
    }
}

export default Buttons;