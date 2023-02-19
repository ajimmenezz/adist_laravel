class Status_Tags {
    render(status) {
        let html = "";
        switch (status) {
            case 1:
                html = `<span class="badge rounded-pill text-bg-warning">Abierto</span>`;
                break;
            case 2:
                html = `<span class="badge rounded-pill text-bg-primary">En proceso</span>`;
                break;
            case 3:
                html = `<span class="badge rounded-pill text-bg-danger">Problema</span>`;
                break;
            case 10:
                html = `<span class="badge rounded-pill text-bg-dark">Rechazado</span>`;
                break;
        }
        return html;
    }
}

export default Status_Tags; 