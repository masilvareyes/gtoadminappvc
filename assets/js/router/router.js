window.Router = (function () {
    function currentPath() {
        var hash = window.location.hash || "#/";
        return hash.replace(/^#/, "");
    }

    function renderNotFound() {
        return [
            "<div class='card-body'>",
            "<h2 class='h5'>Ruta frontend no encontrada</h2>",
            "<p class='mb-0'>Navega usando las rutas base: <code>#/</code> o <code>#/login</code>.</p>",
            "</div>"
        ].join("");
    }

    function navigate() {
        var path = currentPath();
        console.log(path);
        var route = window.AppRoutes.find(function (item) {
            return item.path === path;
        });

        var view = document.getElementById("app-view");
        if (!view) {
            return;
        }

        var html = route && route.page && route.page.render
            ? route.page.render()
            : renderNotFound();
        view.innerHTML = html;

        if (route && route.page && route.page.bindEvents) {
            route.page.bindEvents();
        }
    }

    return {
        navigate: navigate
    };
})();
