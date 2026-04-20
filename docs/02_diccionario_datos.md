# Diccionario de Datos  
## Sistema de Gestión de Gastos Empresariales

---

## 1. Convenciones y Consideraciones

### Motor de Base de Datos
- MySQL / MariaDB

### Convenciones de nombres
- **Tablas:** snake_case plural (`expenses`, `expense_lines`)
- **Columnas:** snake_case
- **PK:** `id`
- **FK:** `nombre_tabla_id`

### Tipos de datos estándar
- `BIGINT`: identificadores
- `VARCHAR(n)`: textos cortos
- `TEXT`: textos largos
- `DECIMAL(12,2)`: montos
- `DATETIME`: fechas
- `TINYINT(1)`: booleanos

### Auditoría
- `created_at`
- `updated_at`

### Seguridad
- Contraseñas con hash seguro
- RFC cifrado

---

## 2. Organización y Seguridad

### Tabla: `users`

**Descripción:** Usuarios del sistema

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador único del usuario | PK, AI |
| name | VARCHAR(255) | Nombre completo | NOT NULL |
| email | VARCHAR(255) | Correo electrónico | NOT NULL, UNIQUE |
| password | VARCHAR(255) | Contraseña en hash | NOT NULL |
| area_id | BIGINT | Área a la que pertenece | FK, NOT NULL |
| active | TINYINT(1) | Usuario activo | DEFAULT 1 |
| created_at | DATETIME | Fecha de creación | NOT NULL |
| updated_at | DATETIME | Fecha de actualización |  |

### Tabla: `roles`

**Descripción:** Roles del sistema

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador único | PK, AI |
| name | VARCHAR(100) | Nombre del rol (Admin, CXP, etc.) | NOT NULL, UNIQUE |

### Tabla: `user_roles`

**Descripción:** Relación usuario-rol

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| user_id | BIGINT | Usuario asignado | FK, NOT NULL |
| role_id | BIGINT | Rol asignado | FK, NOT NULL |

### Tabla: `areas`

**Descripción:** Áreas organizacionales (nivel padre dentro de la estructura)

**Relaciones**
- 1 área tiene muchos `cost_centers`

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| name | VARCHAR(150) | Nombre del área | NOT NULL |

### Tabla: `cost_centers`

**Descripción:** Centros de costo asociados a un área

**Relaciones**
- Pertenece a `areas`

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| area_id | BIGINT | Área padre | FK, NOT NULL |
| code | VARCHAR(50) | Código | NOT NULL |
| name | VARCHAR(150) | Nombre | NOT NULL |

> Nota: el PDF incluye un fragmento repetido de “Centros de costo” con columnas `id`, `code` y `name`; aquí se consolidó en una sola definición.

### Tabla: `area_managers`

**Descripción:** Jefe de área

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| area_id | BIGINT | Área | FK, UNIQUE |
| user_id | BIGINT | Usuario jefe | FK, NOT NULL |

---

## 3. Catálogos Operativos

### Tabla: `vendors`

**Descripción:** Proveedores reutilizables

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| rfc | VARCHAR(20) | RFC del proveedor | NOT NULL, INDEX |
| name | VARCHAR(255) | Nombre o razón social | NOT NULL |

### Tabla: `expense_categories`

**Descripción:** Categorías de gasto

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| name | VARCHAR(150) | Nombre categoría | NOT NULL |
| fiscal_concept_id | BIGINT | Relación fiscal | FK, NOT NULL |

### Tabla: `fiscal_deductibility_concepts`

**Descripción:** Conceptos fiscales

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| name | VARCHAR(150) | Nombre del concepto | NOT NULL |
| deductible | TINYINT(1) | Indica si es deducible | NOT NULL |

### Tabla: `currencies`

**Descripción:** Monedas

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| code | VARCHAR(10) | Código ISO | NOT NULL, UNIQUE |
| name | VARCHAR(50) | Nombre | NOT NULL |

---

## 4. Tablas Operativas

### Tabla: `expenses`

**Descripción:** Encabezado de comprobación

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| user_id | BIGINT | Usuario creador | FK, NOT NULL |
| area_id | BIGINT | Área | FK, NOT NULL |
| status_id | BIGINT | Estado actual | FK, NOT NULL |
| total | DECIMAL(12,2) | Total calculado | NOT NULL |
| created_at | DATETIME | Fecha creación | NOT NULL |
| updated_at | DATETIME | Fecha actualización |  |

### Tabla: `expense_lines`

**Descripción:** Detalle del gasto

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| expense_id | BIGINT | Gasto padre | FK, NOT NULL |
| vendor_id | BIGINT | Proveedor | FK, NOT NULL |
| category_id | BIGINT | Categoría | FK, NOT NULL |
| subtotal | DECIMAL(12,2) | Importe sin IVA | NOT NULL |
| iva | DECIMAL(12,2) | IVA | NOT NULL |
| total | DECIMAL(12,2) | Total línea | NOT NULL |

### Tabla: `expense_statuses`

**Descripción:** Estados del gasto

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK |
| name | VARCHAR(50) | Nombre estado | NOT NULL |

### Tabla: `expense_status_history`

**Descripción:** Historial de estados

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| expense_id | BIGINT | Gasto | FK |
| status_id | BIGINT | Estado | FK |
| changed_at | DATETIME | Fecha cambio | NOT NULL |

### Tabla: `expense_approvals`

**Descripción:** Aprobaciones del flujo

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK, AI |
| expense_id | BIGINT | Gasto | FK |
| level | INT | Nivel (1 jefe, 2 CXP) | NOT NULL |
| approved_by | BIGINT | Usuario | FK |
| status | VARCHAR(50) | Estado aprobación | NOT NULL |
| comments | TEXT | Comentarios |  |
| created_at | DATETIME | Fecha | NOT NULL |

---

## 5. Soporte de Archivos

### Tabla: `expense_line_documents`

**Descripción:** Documento por línea (1:1 obligatorio)

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK |
| expense_line_id | BIGINT | Línea asociada | FK, UNIQUE |
| type | VARCHAR(50) | Tipo documento (XML, PDF, IMG, VALE) | NOT NULL |

### Tabla: `expense_line_document_files`

**Descripción:** Archivo físico del documento

| Nombre | Tipo | Descripción | Atributos |
|---|---|---|---|
| id | BIGINT | Identificador | PK |
| document_id | BIGINT | Documento | FK |
| file_path | VARCHAR(255) | Ruta almacenamiento | NOT NULL |
| file_type | VARCHAR(50) | Tipo MIME | NOT NULL |

---

## 6. Reglas de Integridad

- El total del gasto = suma de líneas
- Toda línea debe tener documento
- Flujo de aprobación obligatorio de 2 niveles
- Un solo jefe por área

---

## 7. Índices Recomendados

- Índices en todas las FK
- `vendors(rfc)`
- `expenses(user_id, status_id)`
- `expense_lines(expense_id)`

---

**Fin del documento**
