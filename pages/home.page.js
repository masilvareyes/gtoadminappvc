window.HomePage = (function () {
    function render() {
        return [
            "<div class='card-body'>",
            "<h2 class='h5'>Inicio</h2>",
            "<p class='mb-3'>Vista dummy para validar router y bootstrap frontend.</p>",
            "<p class='tech-muted mb-3'>BaseURL API: " + window.ApiConfig.baseUrl + "</p>",
            "<div class='d-flex gap-2 mb-3'>",
            "<button id='btn-check-health' class='btn btn-primary btn-sm'>Probar /api/health</button>",
            "<button id='btn-check-404' class='btn btn-outline-secondary btn-sm'>Probar 404 backend</button>",
            "<button id='btn-check-500' class='btn btn-outline-danger btn-sm'>Probar 500 backend</button>",
            "<button id='btn-check-db' class='btn btn-success btn-sm'>Probar DB</button>",
            "</div>",
            "<p class='tech-muted mb-3'>Nota: el test 500 requiere GTV_ALLOW_EXCEPTION_TEST=true en .env.</p>",
            "<pre id='health-output' class='bg-light p-2 mb-0'>Sin ejecutar.</pre>",
            "</div>"
        ].join("");
    }

    function bindEvents() {
        var healthButton = document.getElementById("btn-check-health");
        var notFoundButton = document.getElementById("btn-check-404");
        var error500Button = document.getElementById("btn-check-500");
        var output = document.getElementById("health-output");
        var dbButton = document.getElementById("btn-check-db");


        if (!healthButton || !notFoundButton || !error500Button || !dbButton|| !output) {
            return;
        }



        healthButton.addEventListener("click", function () {
            window.ErrorHelper.clearGlobalError();
            window.HealthService.check()
                .done(function (response) {
                    output.textContent = JSON.stringify(response, null, 2);
                })
                .fail(function (jqXHR) {
                    output.textContent = JSON.stringify(jqXHR.responseJSON || {
                        ok: false,
                        error: {
                            code: "HEALTH_CALL_FAILED",
                            message: "No se pudo resolver /api/health con la baseURL actual.",
                            details: {
                                baseUrl: window.ApiConfig.baseUrl
                            }
                        }
                    }, null, 2);
                });
        });

        notFoundButton.addEventListener("click", function () {
            window.ErrorHelper.clearGlobalError();
            window.ApiClient.get("/no-existe").always(function (response) {
                output.textContent = JSON.stringify(response || {}, null, 2);
            });
        });

        error500Button.addEventListener("click", function () {
            window.ErrorHelper.clearGlobalError();
            window.ApiClient.get("/debug/exception").always(function (response) {
                var normalized = response || {};
                if (normalized.ok === false && normalized.error && normalized.error.code === "NOT_FOUND") {
                    normalized.error.message = normalized.error.message + " (si buscas 500 real, activa GTV_ALLOW_EXCEPTION_TEST=true)";
                }
                output.textContent = JSON.stringify(normalized, null, 2);
            });
        });
        
        dbButton.addEventListener("click", function () {
        window.ErrorHelper.clearGlobalError();

        window.DbHealthService.check()
            .done(function (response) {
                output.textContent = JSON.stringify(response, null, 2);
            })
            .fail(function (jqXHR) {
                output.textContent = JSON.stringify(jqXHR.responseJSON || {}, null, 2);
            });
        }); 
    }



    return {
        render: render,
        bindEvents: bindEvents
    };
})();
