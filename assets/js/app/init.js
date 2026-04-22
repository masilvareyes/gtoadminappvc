window.AppInit = (function () {
    function bindGlobalEvents() {
        window.addEventListener("hashchange", function () {
            window.ErrorHelper.clearGlobalError();
            window.Router.navigate();
        });
    }

    function bootstrap() {
        window.ApiClient.setup();
        bindGlobalEvents();
    }

    return {
        bootstrap: bootstrap
    };
})();
