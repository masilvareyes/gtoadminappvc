window.ErrorHelper = (function () {
    function normalizeAjaxError(jqXHR, textStatus) {
        var payload = jqXHR.responseJSON;
        if (payload && payload.ok === false && payload.error) {
            return {
                title: "Error tecnico backend",
                message: "[" + payload.error.code + "] " + payload.error.message
            };
        }

        if (textStatus === "timeout") {
            return {
                title: "Timeout de red",
                message: "La solicitud excedio el tiempo de espera."
            };
        }

        if (textStatus === "error" && jqXHR.status === 0) {
            return {
                title: "Error de red",
                message: "No se pudo conectar con el backend."
            };
        }

        if (jqXHR.status === 404) {
            return {
                title: "Error HTTP 404",
                message: "Endpoint no encontrado en backend."
            };
        }

        if (jqXHR.status === 500) {
            return {
                title: "Error HTTP 500",
                message: "Fallo interno reportado por backend."
            };
        }

        return {
            title: "Error tecnico",
            message: "Error no controlado al consumir la API."
        };
    }

    function showGlobalError(info) {
        var box = document.getElementById("global-tech-error");
        if (!box) {
            return;
        }
        box.classList.remove("d-none");
        box.textContent = info.title + ": " + info.message;
    }

    function clearGlobalError() {
        var box = document.getElementById("global-tech-error");
        if (!box) {
            return;
        }
        box.classList.add("d-none");
        box.textContent = "";
    }

    return {
        normalizeAjaxError: normalizeAjaxError,
        showGlobalError: showGlobalError,
        clearGlobalError: clearGlobalError
    };
})();
