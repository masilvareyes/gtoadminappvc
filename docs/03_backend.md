# Estructura Backend

```text
backend/                                              ← raíz del backend API
├── app/                                              ← código principal de la aplicación
│   ├── bootstrap/                                    ← arranque e inicialización del sistema
│   │   ├── app.php                                   ← inicializa contenedor, middlewares y rutas
│   │   ├── env.php                                   ← carga variables de entorno
│   │   └── providers.php                             ← registro de servicios globales
│   │
│   ├── core/                                         ← clases base del framework interno
│   │   ├── Http/
│   │   │   ├── Request.php                           ← encapsulación de request HTTP
│   │   │   ├── Response.php                          ← construcción de respuestas HTTP
│   │   │   └── Controller.php                        ← clase base para controladores
│   │   ├── Routing/
│   │   │   ├── Router.php                            ← gestor de rutas
│   │   │   └── Route.php                             ← definición de rutas
│   │   ├── Database/
│   │   │   ├── Connection.php                        ← conexión PDO
│   │   │   ├── QueryBuilder.php                      ← construcción de queries
│   │   │   └── Transaction.php                       ← manejo de transacciones
│   │   ├── Security/
│   │   │   ├── Hash.php                              ← hashing de contraseñas
│   │   │   ├── Jwt.php                               ← generación y validación de tokens
│   │   │   └── Csrf.php                              ← protección CSRF
│   │   ├── Exceptions/
│   │   │   ├── AppException.php                      ← excepción base
│   │   │   ├── ValidationException.php               ← errores de validación
│   │   │   └── UnauthorizedException.php             ← errores de autorización
│   │   └── Support/
│   │       ├── Validator.php                         ← validación de datos
│   │       └── Helpers.php                           ← funciones auxiliares globales
│   │
│   ├── middlewares/                                  ← middlewares HTTP
│   │   ├── AuthMiddleware.php                        ← valida autenticación
│   │   ├── RoleMiddleware.php                        ← valida roles (RBAC)
│   │   ├── CsrfMiddleware.php                        ← protección CSRF
│   │   ├── LoggingMiddleware.php                     ← logging de requests
│   │   └── RateLimitMiddleware.php                   ← limitación de solicitudes
│   │
│   ├── modules/                                      ← módulos organizados por feature
│   │   ├── auth/                                     ← módulo de autenticación
│   │   │   ├── Controllers/
│   │   │   │   └── AuthController.php                ← login, logout, refresh
│   │   │   ├── Services/
│   │   │   │   └── AuthService.php                   ← lógica de autenticación
│   │   │   ├── Repositories/
│   │   │   │   └── UserAuthRepository.php            ← acceso a usuarios para auth
│   │   │   ├── DTOs/
│   │   │   │   └── LoginRequest.php                  ← request DTO login
│   │   │   └── Routes/
│   │   │       └── auth.routes.php                   ← rutas del módulo
│   │   │
│   │   ├── users/                                    ← gestión de usuarios
│   │   │   ├── Controllers/UserController.php
│   │   │   ├── Services/UserService.php
│   │   │   ├── Repositories/UserRepository.php
│   │   │   ├── Entities/User.php
│   │   │   ├── DTOs/
│   │   │   └── Routes/users.routes.php
│   │   │
│   │   ├── roles/                                    ← gestión de roles
│   │   │   ├── Controllers/RoleController.php
│   │   │   ├── Services/RoleService.php
│   │   │   ├── Repositories/RoleRepository.php
│   │   │   └── Routes/roles.routes.php
│   │   │
│   │   ├── areas/                                    ← áreas organizacionales
│   │   │   ├── Controllers/AreaController.php
│   │   │   ├── Services/AreaService.php
│   │   │   ├── Repositories/AreaRepository.php
│   │   │   ├── Entities/Area.php
│   │   │   └── Routes/areas.routes.php
│   │   │
│   │   ├── cost-centers/                             ← centros de costo
│   │   │   ├── Controllers/CostCenterController.php
│   │   │   ├── Services/CostCenterService.php
│   │   │   ├── Repositories/CostCenterRepository.php
│   │   │   ├── Entities/CostCenter.php
│   │   │   └── Routes/cost_centers.routes.php
│   │   │
│   │   ├── budgets/                                  ← presupuestos
│   │   │   ├── Controllers/BudgetController.php
│   │   │   ├── Services/BudgetService.php
│   │   │   ├── Repositories/BudgetRepository.php
│   │   │   └── Routes/budgets.routes.php
│   │   │
│   │   ├── expenses/                                 ← gastos (core del negocio)
│   │   │   ├── Controllers/ExpenseController.php
│   │   │   ├── Controllers/ExpenseActionController.php ← acciones (submit, approve, reject)
│   │   │   ├── Services/ExpenseService.php
│   │   │   ├── Services/ExpenseWorkflowService.php   ← flujo de aprobación
│   │   │   ├── Repositories/ExpenseRepository.php
│   │   │   ├── Entities/Expense.php
│   │   │   ├── DTOs/
│   │   │   └── Routes/expenses.routes.php
│   │   │
│   │   ├── expense-lines/                            ← líneas de gasto
│   │   │   ├── Controllers/ExpenseLineController.php
│   │   │   ├── Services/ExpenseLineService.php
│   │   │   ├── Repositories/ExpenseLineRepository.php
│   │   │   ├── Entities/ExpenseLine.php
│   │   │   └── Routes/expense_lines.routes.php
│   │   │
│   │   ├── documents/                                ← documentos y comprobantes
│   │   │   ├── Controllers/DocumentController.php
│   │   │   ├── Services/DocumentService.php
│   │   │   ├── Services/XmlParserService.php         ← extracción de datos CFDI
│   │   │   ├── Repositories/DocumentRepository.php
│   │   │   └── Routes/documents.routes.php
│   │   │
│   │   ├── catalogs/                                 ← catálogos operativos/fiscales
│   │   │   ├── Controllers/CatalogController.php
│   │   │   ├── Services/CatalogService.php
│   │   │   ├── Repositories/
│   │   │   └── Routes/catalogs.routes.php
│   │   │
│   │   ├── approvals/                                ← flujo de aprobación
│   │   │   ├── Controllers/ApprovalController.php
│   │   │   ├── Services/ApprovalService.php
│   │   │   ├── Repositories/ApprovalRepository.php
│   │   │   └── Routes/approvals.routes.php
│   │   │
│   │   ├── reports/                                  ← reportes
│   │   │   ├── Controllers/ReportController.php
│   │   │   ├── Services/ReportService.php
│   │   │   └── Routes/reports.routes.php
│   │   │
│   │   └── audit/                                    ← auditoría
│   │       ├── Controllers/AuditController.php
│   │       ├── Services/AuditService.php
│   │       ├── Repositories/AuditRepository.php
│   │       └── Routes/audit.routes.php
│   │
│   ├── routes/                                       ← registro central de rutas
│   │   ├── api.php                                   ← carga todas las rutas de módulos
│   │   └── health.php                                ← endpoint de salud del sistema
│   │
│   ├── contracts/                                    ← contrato de API
│   │   ├── openapi.yaml                              ← definición OpenAPI/Swagger
│   │   ├── schemas/                                  ← esquemas de request/response
│   │   └── examples/                                 ← ejemplos de consumo API
│   │
│   ├── database/                                     ← capa de persistencia
│   │   ├── migrations/                               ← scripts de migración
│   │   ├── seeders/                                  ← datos iniciales
│   │   └── factories/                                ← generación de datos para tests
│   │
│   ├── storage/                                      ← almacenamiento local
│   │   ├── documents/                                ← XML, PDF, imágenes
│   │   ├── logs/                                     ← logs del sistema
│   │   └── temp/                                     ← archivos temporales
│   │
│   └── utils/                                        ← utilidades transversales
│       ├── DateHelper.php                            ← manejo de fechas
│       ├── FileHelper.php                            ← manejo de archivos
│       └── XmlHelper.php                             ← utilidades XML
│
├── public/                                           ← punto de entrada público
│   ├── index.php                                     ← front controller
│   └── .htaccess                                     ← reglas de routing
│
├── tests/                                            ← pruebas automatizadas
│   ├── Unit/                                         ← pruebas unitarias
│   ├── Integration/                                  ← pruebas de integración
│   └── Feature/                                      ← pruebas por módulo
│
├── docs/                                             ← documentación técnica
│   ├── architecture.md                               ← descripción de arquitectura
│   ├── api-guidelines.md                             ← lineamientos de API
│   └── security.md                                   ← políticas de seguridad
│
├── .env                                              ← variables de entorno
├── .env.example                                      ← ejemplo de configuración
├── .gitignore                                        ← exclusiones de git
├── composer.json                                     ← dependencias PHP
├── composer.lock                                     ← lock de dependencias
└── README.md                                         ← documentación general del proyecto
```
