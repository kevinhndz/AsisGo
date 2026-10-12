document.addEventListener("click", function (evento) {
    const control = evento.target.closest("[data-action]");

    if (!control) {
        return;
    }

    const accion = control.dataset.action;
    const manejadores = {
        "cerrar-faltas": function () {
            document.getElementById("modal-faltas")?.classList.remove("activo");
        }
    };

    if (control.matches("a")) {
        evento.preventDefault();
    }

    if (manejadores[accion]) {
        manejadores[accion](evento, control);
        return;
    }

    if (typeof window[accion] === "function") {
        window[accion](evento, control);
    }
});

document.addEventListener("submit", function (evento) {
    const formulario = evento.target.closest("[data-submit]");

    if (!formulario) {
        return;
    }

    evento.preventDefault();

    if (typeof window[formulario.dataset.submit] === "function") {
        window[formulario.dataset.submit](evento);
    }
});
