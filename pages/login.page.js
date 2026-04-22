window.LoginPage = (function () {
    function render() {
        return [
            "<div class='card-body'>",
            "<h2 class='h5'>Login (dummy)</h2>",
            "<p class='mb-2'>Pantalla visual de navegacion. No implementa autenticacion real.</p>",
            "<div class='tech-muted'>Fuera de alcance: sesion, login real, guards y RBAC.</div>",
            "</div>"
        ].join("");
    }

    return {
        render: render
    };
})();
