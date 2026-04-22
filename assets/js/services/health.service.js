window.HealthService = (function () {
    function check() {
        return window.ApiClient.get("/health");
    }

    return {
        check: check
    };
})();
