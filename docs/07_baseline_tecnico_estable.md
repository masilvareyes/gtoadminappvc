# 07 - Baseline Técnico Estable

## Sistema de Gestión de Gastos Empresariales

---

## 1. Objetivo

Este documento define el estado base funcional y técnico de la aplicación después de la estabilización inicial de:

- Arquitectura frontend/backend unificada
- Configuración de entorno
- Routing (Apache + Backend + Frontend)
- Conectividad API
- Conexión a base de datos

Este baseline debe mantenerse estable antes de continuar con nuevas funcionalidades.

---

## 2. Estructura del proyecto

```
/gtosVC2
├── api/
│   ├── bootstrap/
│   ├── core/
│   ├── routes/
│   └── ...
├── assets/
│   ├── js/
│   ├── css/
│   └── vendors/
├── pages/
├── docs/
├── index.php
├── frontend.php
├── .env
└── .htaccess
```

---

## 3. Configuración Apache

Alias:

```
Alias /gtosVC2 "C:/GHProjects/gtosVC2"
```

Requisitos:

- mod_rewrite habilitado
- AllowOverride All activo

---

## 4. Configuración .htaccess

```
RewriteEngine On
RewriteBase /gtosVC2/

RewriteRule ^$ frontend.php [QSA,L]
RewriteRule ^api(?:/.*)?$ index.php [QSA,L]

RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

RewriteRule ^(.+)$ frontend.php?path=$1 [QSA,L]
```

---

## 5. Configuración de entorno

Ubicación:

```
C:\GHProjects\gtosVC2\.env
```

Variables clave:

```
APP_BASE_PATH=/gtosVC2
DB_HOST=127.0.0.1
DB_NAME=gtosvc
```

---

## 6. Backend

- index.php carga bootstrap
- Uso de namespace App\Core\...
- Routing centralizado en api/routes/api.php

---

## 7. Frontend

- frontend.php carga .env
- Uso de BASE_PATH dinámico
- Router basado en hash (#/)

---

## 8. API

Endpoints validados:

- /api/health
- /api/health/db

---

## 9. Pruebas realizadas

- Health OK
- DB Health OK
- 404 controlado
- 500 controlado

---

## 10. Estado actual

✔ Sistema estable
✔ Base lista para desarrollo incremental

---

## 11. Notas

No modificar estructura base sin documentar cambios.
