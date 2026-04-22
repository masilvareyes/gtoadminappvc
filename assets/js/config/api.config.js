window.ApiConfig = Object.assign({}, window.ApiConfig || {}, {
    baseUrl: (window.location.origin || "") + ((window.AppConfig && window.AppConfig.basePath) ? window.AppConfig.basePath : "") + "/api"
});