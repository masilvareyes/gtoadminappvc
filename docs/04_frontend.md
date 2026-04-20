# Estructura Frontend

```text
frontend/                                  ← raíz del frontend (totalmente desacoplado del backend)
├── index.html                             ← punto de entrada (login o redirect inicial)

├── assets/                                ← recursos estáticos globales
│   ├── images/                            ← imágenes del sistema (logos, íconos)
│   ├── fonts/                             ← tipografías personalizadas
│   └── vendors/                           ← librerías externas (Bootstrap, jQuery, DataTables)
│       ├── bootstrap/                     ← archivos de Bootstrap
│       ├── jquery/                        ← librería jQuery
│       └── datatables/                    ← librería DataTables

├── styles/                                ← estilos CSS (solo presentación)
│   ├── base/                              ← estilos base globales
│   │   ├── reset.css                      ← normalización de estilos
│   │   ├── variables.css                  ← variables CSS (colores, spacing)
│   │   └── typography.css                 ← tipografías globales
│   ├── layout/                            ← estructura visual general
│   │   ├── grid.css                       ← layout general (grid, contenedores)
│   │   ├── header.css                     ← encabezado
│   │   └── sidebar.css                    ← menú lateral
│   ├── components/                        ← estilos de componentes reutilizables
│   │   ├── buttons.css                    ← botones
│   │   ├── forms.css                      ← formularios
│   │   ├── tables.css                     ← tablas (DataTables override)
│   │   └── modals.css                     ← modales
│   └── pages/                             ← estilos específicos por pantalla
│       ├── login.css                      ← estilos login
│       ├── dashboard.css                  ← estilos dashboard
│       ├── expenses.css                   ← estilos gastos
│       └── reports.css                    ← estilos reportes

├── components/                            ← componentes reutilizables (HTML + JS desacoplado)
│   ├── navbar/                            ← barra superior
│   │   ├── navbar.html                    ← estructura HTML
│   │   └── navbar.js                      ← comportamiento JS
│   ├── sidebar/                           ← menú lateral
│   │   ├── sidebar.html                   ← estructura HTML
│   │   └── sidebar.js                     ← lógica del menú
│   ├── datatable/                         ← wrapper reutilizable de DataTables
│   │   ├── datatable.html                 ← estructura base tabla
│   │   └── datatable.js                   ← inicialización y configuración
│   ├── modal/                             ← componente modal genérico
│   │   ├── modal.html                     ← HTML modal
│   │   └── modal.js                       ← control de apertura/cierre
│   └── form-controls/                     ← inputs reutilizables
│       ├── input.js                       ← inputs base
│       ├── select.js                      ← selects dinámicos
│       └── file-upload.js                 ← carga de archivos (XML, PDF)

├── pages/                                 ← pantallas completas del sistema
│   ├── auth/                              ← autenticación
│   │   ├── login.html                     ← vista login
│   │   └── login.js                       ← lógica login
│   ├── dashboard/                         ← inicio del sistema
│   │   ├── dashboard.html                 ← vista principal
│   │   └── dashboard.js                   ← lógica dashboard
│   ├── expenses/                          ← módulo de gastos
│   │   ├── expenses-list.html             ← listado de gastos
│   │   ├── expenses-list.js               ← lógica listado
│   │   ├── expense-create.html            ← captura de gasto
│   │   ├── expense-create.js              ← lógica captura
│   │   ├── expense-detail.html            ← detalle de gasto
│   │   └── expense-detail.js              ← lógica detalle
│   ├── approvals/                         ← bandeja de aprobaciones
│   │   ├── approvals.html                 ← vista bandeja
│   │   └── approvals.js                   ← lógica aprobaciones
│   ├── reports/                           ← reportes
│   │   ├── reports.html                   ← vista reportes
│   │   └── reports.js                     ← lógica reportes
│   ├── admin/                             ← administración
│   │   ├── users.html                     ← gestión usuarios
│   │   ├── users.js                       ← lógica usuarios
│   │   ├── areas.html                     ← gestión áreas
│   │   ├── areas.js                       ← lógica áreas
│   │   ├── budgets.html                   ← presupuestos
│   │   └── budgets.js                     ← lógica presupuestos
│   └── catalogs/                          ← catálogos operativos
│       ├── vendors.html                   ← proveedores
│       ├── vendors.js                     ← lógica proveedores
│       ├── categories.html                ← categorías de gasto
│       ├── categories.js                  ← lógica categorías
│       ├── currencies.html                ← monedas
│       └── currencies.js                  ← lógica monedas

├── services/                              ← consumo de API (AJAX con jQuery)
│   ├── api-client.js                      ← configuración base AJAX (headers, baseURL)
│   ├── auth.service.js                   ← servicios autenticación
│   ├── expense.service.js                ← servicios de gastos
│   ├── approval.service.js               ← servicios de aprobaciones
│   ├── report.service.js                 ← servicios de reportes
│   ├── user.service.js                   ← servicios de usuarios
│   ├── area.service.js                   ← servicios de áreas
│   ├── catalog.service.js                ← servicios de catálogos
│   └── file.service.js                   ← servicios de carga de archivos

├── interfaces/                            ← contratos de datos (estructura esperada)
│   ├── expense.interface.js              ← estructura gasto
│   ├── expense-line.interface.js         ← estructura detalle gasto
│   ├── user.interface.js                 ← estructura usuario
│   ├── area.interface.js                 ← estructura área
│   ├── approval.interface.js             ← estructura aprobación
│   └── report.interface.js               ← estructura reportes

├── types/                                 ← tipados y enums del sistema
│   ├── expense-status.enum.js            ← estados (BORRADOR, APROBADO, etc)
│   ├── roles.enum.js                     ← roles del sistema
│   ├── approval-level.enum.js            ← niveles de aprobación
│   └── file-types.enum.js                ← tipos de archivo

├── context/                               ← estado global simple (sin frameworks)
│   ├── session.context.js                ← manejo de sesión (usuario logueado)
│   ├── auth.context.js                   ← control de autenticación
│   └── app.context.js                    ← estado global de la app

├── helpers/                               ← utilitarios reutilizables
│   ├── dom.helper.js                     ← manipulación DOM
│   ├── form.helper.js                    ← manejo de formularios
│   ├── validation.helper.js              ← validaciones frontend
│   ├── format.helper.js                  ← formatos (moneda, fecha)
│   ├── datatable.helper.js               ← configuración común DataTables
│   └── file.helper.js                    ← manejo de archivos

├── config/                                ← configuración del frontend
│   ├── app.config.js                     ← configuración general
│   ├── api.config.js                     ← endpoints de la API
│   └── env.config.js                     ← variables por entorno

├── router/                                ← control de navegación
│   ├── routes.js                         ← definición de rutas
│   └── router.js                         ← lógica de navegación

└── app/                                   ← inicialización de la aplicación
    ├── app.js                            ← bootstrap de la app frontend
    └── init.js                           ← carga inicial (eventos globales)
```
