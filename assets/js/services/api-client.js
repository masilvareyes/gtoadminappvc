window.ApiClient = (function () {
    function hasJQuery() {
        return typeof window.jQuery !== "undefined";
    }

    function normalizeBaseUrl() {
        if (!window.ApiConfig || typeof window.ApiConfig.baseUrl !== "string") {
            return "";
        }

        return window.ApiConfig.baseUrl.replace(/\/$/, "");
    }

    function normalizePath(path) {
        if (typeof path !== "string" || path.trim() === "") {
            return "";
        }

        return path.charAt(0) === "/" ? path : "/" + path;
    }

    function setup() {
        if (!hasJQuery()) {
            window.ErrorHelper.showGlobalError({
                title: "Dependencia faltante",
                message: "jQuery no cargo; AJAX no estara disponible."
            });
            return;
        }

        window.jQuery.ajaxSetup({
            timeout: window.AppConfig.requestTimeoutMs,
            contentType: "application/json; charset=utf-8",
            dataType: "json"
        });
    }

    function buildUrl(path) {
        var baseUrl = normalizeBaseUrl();
        var normalizedPath = normalizePath(path);
        return baseUrl + normalizedPath;
    }

    function get(path) {
        if (!hasJQuery()) {
            var missingDependency = {
                responseJSON: {
                    ok: false,
                    error: {
                        code: "JQUERY_MISSING",
                        message: "No se puede ejecutar AJAX sin jQuery."
                    }
                },
                status: 0
            };

            var dependencyError = window.ErrorHelper.normalizeAjaxError(missingDependency, "error");
            window.ErrorHelper.showGlobalError(dependencyError);

            return {
                done: function () { return this; },
                fail: function (callback) {
                    if (typeof callback === "function") {
                        callback(missingDependency, "error");
                    }
                    return this;
                },
                always: function (callback) {
                    if (typeof callback === "function") {
                        callback(missingDependency);
                    }
                    return this;
                }
            };
        }

        return window.jQuery.ajax({
            method: "GET",
            url: buildUrl(path)
        }).fail(function (jqXHR, textStatus) {
            var errorInfo = window.ErrorHelper.normalizeAjaxError(jqXHR, textStatus);
            window.ErrorHelper.showGlobalError(errorInfo);
        });
    }

    return {
        setup: setup,
        buildUrl: buildUrl,
        get: get
    };
})();