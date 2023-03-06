import Events from "../../shared/Events";
import Buttons from "../../shared/Buttons";

$(function () {
    const events = new Events();
    const buttons = new Buttons();

    events.loaded();

    buttons.initClic(".support-censo-item-area", function (btn) {
        const serviceId = btn.data("serviceid");
        const area = btn.data("areaid");

        window.location.href = `${serviceId}/${area}`;
    });
}); 