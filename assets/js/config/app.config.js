window.AppConfig = Object.assign({}, window.AppConfig || {}, {
    basePath: (window.APP_CONFIG && typeof window.APP_CONFIG.BASE_PATH === "string")
        ? window.APP_CONFIG.BASE_PATH.replace(/\/$/, "")
        : "",
    requestTimeoutMs: 5000
});