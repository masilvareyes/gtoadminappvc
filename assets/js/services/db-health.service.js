window.DbHealthService = (function () {
    function check() {
        return window.ApiClient.get("/health/db");
    }

    return {
        check: check
    };
})();