# Backlog inicial priorizado
## Sistema de Gestión de Gastos Empresariales

# 1. Resumen ejecutivo del backlog

La aplicación es un sistema web on-premise para una sola empresa, orientado a capturar gastos, asociar CFDI XML 4.0, enrutar aprobaciones en dos niveles, controlar presupuesto mensual por área y dejar trazabilidad auditable por al menos 5 años.

El criterio de priorización usado fue:
1. construir primero cimientos que estabilizan todo lo demás,
2. después asegurar identidad, sesión y control de acceso,
3. luego cargar estructura organizacional y catálogos mínimos,
4. enseguida habilitar el primer caso de uso núcleo verificable,
5. y dejar para después lo que depende de dato operativo confiable, como presupuestos, reportes y auditoría reforzada.

La estrategia propuesta no construye “módulos completos”, sino capacidades pequeñas y comprobables: arranque técnico, login, sesión, catálogos mínimos, gasto borrador, líneas, totales, envío, aprobación, XML, presupuesto y reportes.

La lógica general del orden propuesto es:
- primero estabilidad técnica,
- luego seguridad y contexto de usuario,
- después datos maestros mínimos,
- luego flujo núcleo de gasto,
- y al final componentes más sensibles a ambigüedad o dependencia del dato consolidado.

# 2. Supuestos de trabajo

1. **Se usará sesión PHP como mecanismo primario de autenticación del MVP**, no JWT.
2. **El MVP usará dos niveles de aprobación reales: jefe de área y CXP**.
3. **El primer flujo de captura debe permitir múltiples líneas por gasto**.
4. **La obligación “toda línea debe tener documento” se aplicará como regla de envío a aprobación, no como requisito para crear borrador**.
5. **En la primera iteración de documentos se implementará XML primero y se diferirán PDF/IMG/VALE**.
6. **Los reportes exportables CSV/PDF no son parte del primer backlog operativo inicial**.
7. **La bitácora mínima inicial será técnica y de eventos clave**, pero la consulta administrativa de auditoría se deja después de estabilizar el flujo núcleo.
8. **No se prioriza OpenAPI al inicio como contrato exhaustivo**, solo el estándar de respuesta API y consistencia de endpoints.

# 3. Huecos, ambigüedades o contradicciones detectadas

## Funcionales

| Hallazgo | Impacto | Supuesto mínimo seguro |
|---|---|---|
| El diseño establece aprobación en dos instancias reales: jefe y CXP. El plan, en su resumen inicial, simplifica parcialmente el flujo. | Afecta el orden del backlog de flujo. | Diseñar el backlog desde el inicio para dos niveles, pero implementar primero jefe y luego CXP como slices separados. |
| El diseño habla de “reporte exportable CSV y/o PDF”, pero no define obligatoriedad del formato ni prioridad. | Puede inflar temprano el backlog de reportes. | Dejar exportación fuera del MVP operativo inicial. |
| El flujo CXP no está totalmente aterrizado en el plan como proceso operativo detallado. | Riesgo moderado para fases medias. | Priorizar bandeja y decisión básica; no modelar aún notificaciones complejas. |

## Técnicos

| Hallazgo | Impacto | Supuesto mínimo seguro |
|---|---|---|
| El backend propuesto incluye soporte para JWT, pero el diseño privilegia sesión PHP. | Puede generar retrabajo en auth. | Elegir sesión PHP para el MVP. |
| No está completamente definido el contrato OpenAPI final. | No bloquea el desarrollo, pero sí el formalismo de API. | Definir primero estándar de respuesta y endpoints mínimos; OpenAPI completo después. |
| El plan advierte que si no se define pronto el estándar de respuestas API habrá deuda técnica. | Afecta fase 1. | Crear un item específico de contrato de respuesta JSON/error estándar en fundación técnica. |

## De datos

| Hallazgo | Impacto | Supuesto mínimo seguro |
|---|---|---|
| El diseño conceptual propone usuario con rol directo; el diccionario formaliza `roles` + `user_roles`. | Contradicción de modelo de roles. | Tomar el diccionario como fuente estructural: `user_roles`. |
| El diseño conceptual modela gasto con más campos en encabezado; el diccionario operativo simplifica `expenses` y traslada detalle a `expense_lines`. | Afecta el alcance de captura. | Tomar el diccionario como fuente implementable y dejar el encabezado mínimo. |
| El diseño conceptual habla de CFDI/adjuntos por gasto; el diccionario formaliza documento por línea y archivo físico por documento. | Afecta el diseño de documentos. | Priorizar modelo por línea. |
| El diccionario menciona “RFC cifrado”, pero el esquema visible de `vendors` no detalla cifrado. | Ambigüedad de seguridad de datos. | No bloquear el MVP; registrar RFC y dejar cifrado como refinamiento pendiente. |

## De flujo

| Hallazgo | Impacto | Supuesto mínimo seguro |
|---|---|---|
| El plan sugiere crear gasto borrador antes de documentos/XML, pero el flujo funcional coloca carga XML como parte temprana del ciclo. | Puede generar duda sobre obligación de XML al crear. | Permitir borrador sin XML; exigir XML para envío cuando la línea lo requiera. |
| No hay catálogo explícito de motivos de rechazo. | No bloquea aprobar/rechazar. | Usar comentario libre obligatorio en rechazo. |
| No están completamente definidas reglas de edición por estado fuera de borrador/rechazado. | Afecta slices de endurecimiento. | Aplicar regla mínima: editable solo en Borrador y Rechazado por su creador. |

## De seguridad

| Hallazgo | Impacto | Supuesto mínimo seguro |
|---|---|---|
| CSRF se exige en diseño, pero el frontend está planteado como páginas desacopladas con AJAX. | Requiere definición temprana del mecanismo. | Tratar CSRF como slice base de seguridad, no como refinamiento tardío. |
| RBAC se exige backend-only, pero no se detalla matriz de permisos finos. | Puede frenar autorización compleja. | Arrancar con autorización por rol y ownership/área; permisos finos después. |

## De operación

| Hallazgo | Impacto | Supuesto mínimo seguro |
|---|---|---|
| La retención de 5 años y respaldos diarios están definidos, pero la operación de backup queda en infraestructura, no en la aplicación. | No debe convertirse en backlog de software temprano. | Documentar dependencias operativas y excluirlas del backlog de implementación del sistema. |
| No está detallado el manejo exacto de archivos distintos al XML. | Riesgo para fases de documentos. | Limitar alcance inicial a XML. |

# 4. Backlog inicial priorizado

| ID | Nombre corto del item | Tipo de item | Objetivo del item | Descripción funcional/técnica breve | Valor que aporta | Dependencias | Alcance incluido | Alcance excluido | Riesgos | Criterios de aceptación de alto nivel | Pruebas manuales sugeridas | Prioridad | Tamaño | Fase sugerida | Orden |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| BL-001 | Estándar de arranque backend | foundation | Tener backend arrancable y consistente | Front controller, carga de entorno, bootstrap, router base y endpoint health | Base estable para todo | Ninguna | Arranque app, health endpoint, carga config | DB, auth, negocio | Mala base técnica | Backend responde health y error controlado | Abrir health; probar ruta inexistente | Crítica | S | F1 | 1 |
| BL-002 | Respuesta JSON estándar | foundation | Congelar formato base de respuestas | Estructura homogénea para éxito/error y manejo básico de excepciones | Evita deuda técnica temprana | BL-001 | Response wrapper, error técnico básico | Validación de negocio completa | Retrabajo si se omite | Todas las respuestas base siguen un formato definido | Probar success/error desde health y ruta inválida | Crítica | XS | F1 | 2 |
| BL-003 | Conexión DB y repositorio base | foundation | Habilitar persistencia mínima | Conexión PDO, query builder o repositorio base, config por entorno | Desbloquea datos y auth | BL-001 | Conexión, clase base, manejo de error DB | Migraciones de negocio | Mala abstracción temprana | Se puede conectar a DB y ejecutar consulta simple | Validar conexión y consulta de prueba | Crítica | S | F1 | 3 |
| BL-004 | Bootstrap frontend mínimo | foundation | Tener frontend navegable | index, app/init, router simple, layout base, config API AJAX | Desbloquea login y pantallas | Ninguna | Estructura mínima según frontend propuesto | Pantallas de negocio | Mala navegación temprana | Front abre, resuelve rutas base y apunta a API | Entrar a index, navegar a login y ruta dummy | Crítica | S | F1 | 4 |
| BL-005 | Manejo técnico de errores FE/BE | refinamiento | Establecer experiencia mínima de fallo | Captura de errores AJAX y mensajes técnicos controlados | Reduce caos en pruebas | BL-002, BL-004 | Error handler frontend/backend básico | Mensajes funcionales avanzados | Mensajes inconsistentes | Fallos controlados sin romper interfaz | Forzar error API y verificar mensaje/render | Alta | XS | F1 | 5 |
| BL-006 | Migraciones seguridad mínima | seguridad | Crear base de usuarios y roles | `users`, `roles`, `user_roles`, `areas` y seed mínimo | Desbloquea auth y RBAC | BL-003 | Tablas y seed roles base | CRUD admin de usuarios | Modelo de rol ambiguo | Tablas creadas y seed de roles/área usable | Revisar tablas y datos semilla | Crítica | S | F2 | 1 |
| BL-007 | Servicio login | seguridad | Autenticar usuario | Login con email/contraseña, bcrypt, sesión PHP | Primer acceso real | BL-006, BL-002 | Endpoint login y validación credenciales | Logout, perfil, UI final | Ambigüedad sesión/JWT | Usuario válido inicia sesión; inválido no | Login correcto e incorrecto | Crítica | S | F2 | 2 |
| BL-008 | Pantalla login | full slice | Cerrar primer slice usable | Formulario login + consumo API + persistencia mínima de sesión frontend | Primer flujo extremo a extremo | BL-004, BL-007 | UI login y manejo respuesta | Recuperación de contraseña | Integración FE/BE | Usuario accede a pantalla protegida tras login | Login y redirect | Crítica | S | F2 | 3 |
| BL-009 | Middleware autenticación | seguridad | Proteger rutas | Validación de sesión activa en backend | Base de seguridad | BL-007 | Middleware auth + rutas protegidas | Permisos finos | Accesos indebidos | Rutas protegidas rechazan no autenticados | Consumir endpoint con y sin sesión | Crítica | XS | F2 | 4 |
| BL-010 | Endpoint sesión actual | seguridad | Cargar contexto del usuario | Endpoint perfil/sesión con usuario, área y roles | Desbloquea UI condicional | BL-007, BL-009 | Datos mínimos de sesión | Menú completo por rol | Contexto incompleto | Endpoint devuelve usuario autenticado | Consultar sesión actual tras login | Alta | XS | F2 | 5 |
| BL-011 | Middleware autorización RBAC mínima | seguridad | Restringir por rol | Validación por roles base: Usuario, Jefe, CXP, Admin | Seguridad funcional | BL-009, BL-010 | Role middleware básico | Matriz fina por permiso | Sobre/bloqueo de rutas | Rutas clave exigen rol correcto | Consumir endpoint con roles distintos | Alta | XS | F2 | 6 |
| BL-012 | Seed estados de gasto | catálogo | Tener estados base consistentes | Cargar Borrador, Pendiente, Aprobado, Rechazado | Desbloquea flujo núcleo | BL-003 | Seeder y consulta base | Workflow completo | IDs inconsistentes | Estados disponibles y consistentes | Revisar tabla y lectura desde API/DB | Alta | XS | F3 | 1 |
| BL-013 | CRUD mínimo áreas | catálogo | Administrar estructura organizacional base | Alta/edición/consulta/baja lógica de áreas | Desbloquea organización | BL-006 | Backend y UI simple de áreas | Centros de costo | Sobrealcance admin | Áreas pueden crearse y listarse | Crear/editar/listar área | Alta | S | F3 | 2 |
| BL-014 | CRUD mínimo centros de costo | catálogo | Tener desglose contable | Administración básica de cost centers por área | Desbloquea captura real | BL-013 | Backend/UI simple asociados a área | Reportes por centro de costo | Dependencia fuerte de áreas | CC se crean y listan por área | Crear centro de costo y consultarlo | Alta | S | F3 | 3 |
| BL-015 | Asignación de jefe por área | catálogo/flujo | Configurar primera aprobación | `area_managers` y operación mínima de asignación | Desbloquea envío a aprobación | BL-006, BL-013 | Tabla, endpoint, UI simple | Sustituciones temporales | Regla de un jefe | Cada área queda con un solo jefe | Asignar jefe y validar unicidad | Alta | S | F3 | 4 |
| BL-016 | CRUD proveedores | catálogo | Cargar catálogo operativo mínimo | `vendors` backend+UI mínima | Desbloquea líneas de gasto | BL-003 | Crear/listar/editar proveedor | Import masivo | Dato duplicado | Proveedor usable en captura | Alta proveedor y consumirlo | Alta | S | F3 | 5 |
| BL-017 | CRUD categorías de gasto | catálogo | Cargar clasificación operativa | `expense_categories` y relación fiscal mínima | Desbloquea líneas de gasto | BL-003 | CRUD categoría | Lógica fiscal avanzada | Campo fiscal ambiguo | Categorías disponibles en captura | Crear/listar categoría | Alta | S | F3 | 6 |
| BL-018 | CRUD monedas | catálogo | Soportar moneda del CFDI | `currencies` backend+UI mínima | Desbloquea parser/consistencia futura | BL-003 | CRUD moneda | Conversión de tipo de cambio | Baja prioridad funcional | Monedas disponibles | Crear/listar moneda | Media | XS | F3 | 7 |
| BL-019 | Migraciones gastos núcleo | backend | Crear modelo operativo mínimo | `expenses`, `expense_lines`, `expense_statuses`, `expense_status_history` | Desbloquea gasto borrador | BL-003, BL-012 | Tablas núcleo y relaciones básicas | approvals, documentos | Modelo híbrido encabezado/detalle | Tablas creadas con FK válidas | Verificar migraciones | Crítica | S | F4 | 1 |
| BL-020 | Crear gasto borrador | full slice | Registrar encabezado inicial | Endpoint + UI para crear gasto en Borrador | Primer caso de uso negocio | BL-019, BL-010 | Alta de gasto borrador mínimo | Líneas, XML, envío | Encabezado demasiado pobre | Gasto se crea en estado Borrador ligado al usuario/área | Crear gasto y revisar listado/DB | Crítica | S | F4 | 2 |
| BL-021 | Agregar línea de gasto | full slice | Capturar detalle real | Alta/edición/eliminación de `expense_lines` | Hace útil el gasto | BL-020, BL-016, BL-017, BL-014 | CRUD líneas | Documento, aprobación | Reglas de obligatoriedad | Línea se agrega solo a gasto editable | Agregar/editar/eliminar línea | Crítica | S | F4 | 3 |
| BL-022 | Recalcular total del gasto | backend | Mantener consistencia monetaria | Regla total encabezado = suma de líneas | Integridad del dato | BL-021 | Recalculo automático backend | Presupuesto | Totales incorrectos | Total coincide con suma de líneas tras cada cambio | Agregar/quitar línea y revisar total | Crítica | XS | F4 | 4 |
| BL-023 | Listado de gastos propios | full slice | Consultar trabajo del capturista | Endpoint y pantalla de lista del usuario | Navegabilidad operativa | BL-020 | Lista básica con estado y total | Filtros complejos | Mala UX temprana | Usuario ve solo sus gastos | Listar con usuario A/B | Alta | S | F4 | 5 |
| BL-024 | Detalle de gasto | full slice | Ver el gasto completo | Endpoint y pantalla detalle de encabezado+línas | Base para envío y revisión | BL-021, BL-023 | Detalle de gasto propio | Vista de jefe/CXP | Ownership | Detalle muestra estado, totales y líneas | Abrir detalle desde listado | Alta | S | F4 | 6 |
| BL-025 | Política de edición por estado | flujo | Congelar regla mínima | Solo Borrador/Rechazado editable por creador | Protege flujo | BL-020, BL-021, BL-024 | Reglas backend sobre update/delete | Excepciones admin | Ambigüedad futura | Gasto Pendiente/Aprobado no editable | Intentar editar por estado | Alta | XS | F5 | 1 |
| BL-026 | Migraciones aprobaciones | flujo | Preparar workflow formal | `expense_approvals` y soporte de historial | Desbloquea submit y aprobación | BL-019, BL-015 | Tablas y relaciones | CXP completo | Modelo insuficiente | Tablas de approval disponibles | Revisar migración y FK | Alta | XS | F5 | 2 |
| BL-027 | Validación previa al envío | flujo | Impedir envío incompleto | Verificación de campos obligatorios y que tenga línea | Calidad operativa | BL-021, BL-024, BL-025 | Validaciones backend mínimas | Validación XML profunda | Regla doc ambigua | Solo gastos completos pasan a submit | Intentar enviar gasto incompleto | Alta | S | F5 | 3 |
| BL-028 | Enviar a aprobación jefe | flujo/full slice | Pasar de Borrador a Pendiente | Acción submit, cambio de estado, historial | Cierra flujo del capturista | BL-026, BL-027 | Endpoint + UI + registro estado | Aprobación del jefe | Falsos positivos de validación | Gasto cambia a Pendiente y deja de ser editable | Enviar gasto válido/inválido | Crítica | S | F5 | 4 |
| BL-029 | Bandeja del jefe | full slice | Dar visibilidad a pendientes de su área | Lista de gastos pendientes por área | Desbloquea revisión | BL-028, BL-011 | Endpoint y pantalla jefe | CXP, filtros avanzados | Visibilidad por área | Jefe ve solo pendientes de su área | Login como jefe y revisar bandeja | Alta | S | F5 | 5 |
| BL-030 | Aprobar gasto por jefe | flujo/full slice | Ejecutar primera aprobación | Acción approve nivel 1 y paso a cola CXP | Completa primera instancia | BL-029 | Acción aprobar, registro approval e historial | CXP final | Estados mal encadenados | Gasto deja de estar en bandeja jefe y queda listo para CXP | Aprobar gasto y revisar estado | Alta | S | F5 | 6 |
| BL-031 | Rechazar gasto por jefe | flujo/full slice | Permitir corrección operativa | Acción reject con comentario obligatorio | Cierra caso alterno esencial | BL-029 | Rechazo con comentario e historial | Catálogo de motivos | Comentarios vacíos | Gasto regresa a Rechazado con comentario | Rechazar sin comentario/con comentario | Alta | S | F5 | 7 |
| BL-032 | Corrección y reenvío de rechazado | flujo | Reabrir ciclo de capturista | Edición de rechazado y nuevo submit | Completa ciclo real | BL-031, BL-025, BL-028 | Re-edición y reenvío | Rechazo CXP | Reglas de ownership | Rechazado puede editarse y reenviarse | Rechazar, editar y reenviar | Alta | S | F6 | 1 |
| BL-033 | Migraciones documentos XML | archivos | Preparar soporte documental | `expense_line_documents` y `expense_line_document_files` | Base para CFDI | BL-019 | Tablas documentos por línea | PDF/IMG/VALE funcionales | Modelo por línea vs gasto | Estructura lista para XML | Ver migraciones | Alta | XS | F6 | 2 |
| BL-034 | Almacenamiento local seguro XML | archivos | Guardar XML físicamente | Servicio de storage local con ruta controlada | Soporte documental real | BL-033 | Guardado local y referencia | Antivirus, backup | Seguridad archivo | Archivo se guarda en ruta definida | Subir XML y validar persistencia física | Alta | S | F6 | 3 |
| BL-035 | Carga de XML por línea | full slice | Asociar CFDI a una línea | Endpoint + UI de upload XML por línea | Hace trazable el gasto | BL-021, BL-033, BL-034 | Upload y asociación 1:1 | Multiples archivos por línea | Integración FE/BE | XML queda asociado a línea editable | Subir XML válido/inválido | Alta | S | F6 | 4 |
| BL-036 | Parser XML básico CFDI | backend/archivos | Extraer datos clave | UUID, RFC emisor/receptor, fecha, subtotal, IVA, total, moneda | Reduce captura manual y valida consistencia | BL-035 | Parse seguro básico | Validación SAT externa | XXE y XML malformado | Sistema extrae datos del XML válido | Cargar XML real y revisar extracción | Alta | S | F6 | 5 |
| BL-037 | Consistencia línea vs XML | flujo/archivos | Endurecer calidad del dato | Validación básica de montos de línea contra XML | Evita errores operativos | BL-036, BL-021 | Comparación simple de importes | Validación fiscal profunda | Reglas insuficientes | Sistema advierte o bloquea inconsistencia definida | Probar XML con montos coincidentes/no coincidentes | Media | S | F6 | 6 |
| BL-038 | Exigir documento al enviar | flujo/archivos | Alinear integridad con envío | En submit, toda línea debe tener documento XML | Cierra hueco entre borrador e integridad | BL-035, BL-027 | Validación al enviar | Obligación al crear borrador | Confusión de regla | Sin XML por línea no se permite envío | Intentar enviar sin documento | Alta | XS | F6 | 7 |
| BL-039 | Bandeja CXP | full slice | Habilitar segunda instancia | Lista de gastos aprobados por jefe pendientes de CXP | Completa flujo de negocio | BL-030, BL-011 | Endpoint y pantalla CXP | Reportes consolidados | Visibilidad global | CXP ve solo gastos listos para revisión final | Login CXP y revisar bandeja | Alta | S | F7 | 1 |
| BL-040 | Aprobar gasto por CXP | flujo/full slice | Cerrar aprobación final | Acción approve nivel 2; estado Aprobado | Habilita presupuesto y reportes confiables | BL-039 | Aprobación final e historial | Pago, ERP | Estados finales erróneos | Gasto queda Aprobado y bloqueado | Aprobar desde CXP y validar bloqueo | Crítica | S | F7 | 2 |
| BL-041 | Rechazar gasto por CXP | flujo/full slice | Permitir retorno desde segunda instancia | Reject con comentario y regreso al capturista | Completa flujo real | BL-039 | Rechazo, comentario, historial | Notificaciones | Falta notificación detallada | Gasto pasa a Rechazado con comentario | Rechazar desde CXP | Alta | S | F7 | 3 |
| BL-042 | Modelo presupuesto mensual | backend/catálogo | Crear base financiera mínima | Definir tabla y migración de presupuesto por área/mes/año | Desbloquea control financiero | BL-013 | Modelo y restricciones mínimas | Bitácora detallada cambios | Definición incompleta | Presupuesto puede persistirse por área/periodo | Crear registros y validar unicidad | Alta | S | F8 | 1 |
| BL-043 | CRUD presupuestos | full slice | Administrar presupuesto mensual | Alta/edición/consulta de presupuestos por admin/CXP | Valor operativo financiero | BL-042, BL-011 | UI+API mínima | Aprobaciones presupuestales | Regla incompleta | Presupuesto se registra y edita | Crear/editar presupuesto | Alta | S | F8 | 2 |
| BL-044 | Acumulado ejercido por área/mes | backend | Calcular gasto real | Suma de gastos Aprobados por área y mes | Dato clave de control | BL-040, BL-042 | Consulta agregada | Forecast | Consultas lentas | Sistema devuelve ejercido correcto | Aprobar gastos y verificar acumulado | Alta | S | F8 | 3 |
| BL-045 | Vista presupuesto vs ejercido | full slice | Mostrar comparativo | Pantalla básica para jefe/admin/CXP | Valor visible del sistema | BL-043, BL-044 | Vista y consulta simple | Alertas complejas | UX temprana | Se ve asignado, ejercido, saldo | Consultar área con gastos aprobados | Alta | S | F8 | 4 |
| BL-046 | Alerta presupuestal en envío | flujo | Advertir sobre sobre-ejercicio | Señalización o bloqueo simple al enviar/aprobar | Endurece negocio | BL-044, BL-028 | Regla mínima configurable o advertencia | Políticas complejas | Regla no confirmada | Sistema muestra alerta definida | Enviar gasto que excede presupuesto | Media | S | F8 | 5 |
| BL-047 | Consulta operativa de gastos con filtros | reporte | Base de reportes operativos | Endpoint filtrable por área, periodo, estado, categoría, CC | Fuente para reportes | BL-040, BL-014, BL-017 | Consulta y filtros | Exportación | Queries pesadas | Consulta responde filtros mínimos | Probar filtros combinados | Alta | S | F9 | 1 |
| BL-048 | Vista de reporte operativo | full slice/reporte | Explotar información operativa | Pantalla de listado ampliado con DataTables | Valor visible a negocio | BL-047 | Tabla navegable y filtros básicos | Exportación PDF/CSV | UI sobrecargada | Usuario autorizado consulta reporte | Abrir reporte y usar filtros | Alta | S | F9 | 2 |
| BL-049 | Reporte gasto vs presupuesto | reporte | Explotar control financiero | Vista específica combinando presupuesto y ejercido | Consolida valor financiero | BL-045, BL-047 | Consulta/vista específica | Dashboard avanzado | Dependencia de presupuesto estable | Reporte muestra comparativo por área/periodo | Consultar varios periodos | Media | S | F9 | 3 |
| BL-050 | Auditoría técnica mínima | auditoría | Registrar eventos críticos sin esperar módulo completo | Insertar eventos: login, crear gasto, submit, approve, reject, cambios presupuesto | Trazabilidad temprana | BL-007, BL-020, BL-028, BL-040, BL-043 | Registro automático backend | Consulta admin | Si se hace tarde se pierde histórico | Eventos críticos quedan registrados | Ejecutar acciones y verificar registros | Alta | S | F10 | 1 |
| BL-051 | Consulta administrativa de bitácora | auditoría | Dar acceso de revisión | Vista/API de consulta con filtros básicos | Valor para admin y control | BL-050, BL-011 | Listado y filtros simples | Exportación, análisis forense | Volumen de datos | Admin consulta bitácora | Ejecutar filtros por fecha/usuario | Media | S | F10 | 2 |
| BL-052 | CSRF end-to-end | seguridad/refinamiento | Alinear frontend y backend con seguridad exigida | Token CSRF en formularios/requests mutantes | Reduce riesgo real | BL-007, BL-004 | Mecanismo integrado | WAF u otras capas | Integración AJAX delicada | Requests mutantes exigen token válido | Repetir POST con/sin token | Alta | S | F11 | 1 |
| BL-053 | Hardening XML seguro | refinamiento/seguridad | Asegurar parseo seguro | Configuración segura de libxml, control de tamaño/tipo | Reduce riesgo XXE y abuso | BL-036 | Reglas técnicas XML | Validación SAT externa | Archivos maliciosos | XML malicioso es rechazado | Probar XML mal formado y externo | Alta | XS | F11 | 2 |
| BL-054 | Índices y optimización consultas | refinamiento | Mejorar desempeño mínimo | Índices recomendados en FK y consultas críticas | Estabilidad operativa | BL-047 | Índices base y revisión query | Tuning avanzado | Sobreoptimización temprana | Consultas críticas responden mejor | Medir consultas grandes | Media | XS | F11 | 3 |
| BL-055 | OpenAPI y documentación técnica mínima | refinamiento | Consolidar contrato técnico | Actualizar contrato y lineamientos conforme a lo ya construido | Reduce ambigüedad futura | BL-047, BL-051 | Documentación de endpoints reales | Documentación exhaustiva enterprise | Puede desincronizarse | API documentada en lo construido | Revisar doc contra endpoints | Media | S | F11 | 4 |

# 5. Agrupación por fases

## Fase 1. Base técnica mínima
**Objetivo:** dejar backend y frontend arrancables, con contrato técnico mínimo estable.

**Items:** BL-001 a BL-005.

**Razón:** sin arranque, routing, respuesta estándar, conexión base y manejo técnico de errores, cualquier slice posterior generará retrabajo estructural.

## Fase 2. Seguridad y autenticación
**Objetivo:** identificar al usuario, abrir sesión y proteger rutas.

**Items:** BL-006 a BL-011.

**Razón:** todos los casos de uso dependen de saber quién es el usuario, su área y sus roles.

## Fase 3. Catálogos y estructura organizacional mínima
**Objetivo:** cargar la base organizacional y catálogos indispensables para operar el flujo.

**Items:** BL-012 a BL-018.

**Razón:** el gasto depende de área, jefe, centro de costo, proveedor y categoría.

## Fase 4. Caso de uso núcleo: gasto en borrador
**Objetivo:** crear y consultar gastos propios en estado Borrador con líneas y totales.

**Items:** BL-019 a BL-024.

**Razón:** primero se estabiliza el núcleo operativo del gasto.

## Fase 5. Flujo básico con jefe de área
**Objetivo:** aplicar reglas de edición, validación mínima y primera aprobación.

**Items:** BL-025 a BL-031.

**Razón:** una vez estable el borrador, se incorpora control de estado y decisión de negocio.

## Fase 6. Corrección y documentos XML
**Objetivo:** cerrar el ciclo de rechazo/corrección y soportar CFDI por línea.

**Items:** BL-032 a BL-038.

**Razón:** aquí ya existe gasto, líneas y submit.

## Fase 7. Segunda aprobación CXP
**Objetivo:** completar el flujo final de aprobación y dejar dato confiable.

**Items:** BL-039 a BL-041.

**Razón:** ya existe flujo básico, corrección y soporte documental.

## Fase 8. Presupuesto
**Objetivo:** montar control presupuestal mensual por área.

**Items:** BL-042 a BL-046.

**Razón:** el comparativo depende de gastos ya aprobados y del modelo presupuestal aún ambiguo.

## Fase 9. Reportes
**Objetivo:** explotar operativamente el dato confiable.

**Items:** BL-047 a BL-049.

**Razón:** reportar antes de estabilizar el dato generaría retrabajo.

## Fase 10. Auditoría
**Objetivo:** consolidar trazabilidad administrativa y técnica.

**Items:** BL-050 a BL-051.

**Razón:** aunque el registro de eventos debe iniciar antes, la consulta admin completa gana valor cuando ya hay flujos estabilizados.

## Fase 11. Hardening y refinamientos
**Objetivo:** reforzar seguridad, desempeño y documentación.

**Items:** BL-052 a BL-055.

**Razón:** dependen de decisiones ya materializadas y consolidan la calidad del sistema.

# 6. Secuencia recomendada de ejecución

## Orden real recomendado para construir
1. BL-001 Estándar de arranque backend
2. BL-002 Respuesta JSON estándar
3. BL-003 Conexión DB y repositorio base
4. BL-004 Bootstrap frontend mínimo
5. BL-005 Manejo técnico de errores FE/BE
6. BL-006 Migraciones seguridad mínima
7. BL-007 Servicio login
8. BL-008 Pantalla login
9. BL-009 Middleware autenticación
10. BL-010 Endpoint sesión actual
11. BL-011 Middleware autorización RBAC mínima
12. BL-012 Seed estados de gasto
13. BL-013 CRUD mínimo áreas
14. BL-014 CRUD mínimo centros de costo
15. BL-015 Asignación de jefe por área
16. BL-016 CRUD proveedores
17. BL-017 CRUD categorías de gasto
18. BL-019 Migraciones gastos núcleo
19. BL-020 Crear gasto borrador
20. BL-021 Agregar línea de gasto
21. BL-022 Recalcular total del gasto
22. BL-023 Listado de gastos propios
23. BL-024 Detalle de gasto
24. BL-025 Política de edición por estado
25. BL-026 Migraciones aprobaciones
26. BL-027 Validación previa al envío
27. BL-028 Enviar a aprobación jefe
28. BL-029 Bandeja del jefe
29. BL-030 Aprobar gasto por jefe
30. BL-031 Rechazar gasto por jefe
31. BL-032 Corrección y reenvío
32. BL-033 a BL-038 XML/documentos
33. BL-039 a BL-041 CXP
34. BL-042 a BL-046 Presupuesto
35. BL-047 a BL-049 Reportes
36. BL-050 a BL-055 Auditoría y hardening

## Primer item ideal
**BL-001 Estándar de arranque backend**.

## Qué items no conviene adelantar
No conviene adelantar:
- XML/parser antes de gasto borrador y líneas,
- presupuesto antes de tener aprobación final CXP,
- reportes antes de estabilizar el dato aprobado,
- consulta administrativa de auditoría antes de tener eventos relevantes,
- OpenAPI exhaustivo antes de congelar los endpoints reales.

# 7. Identificación de items demasiado grandes

## A. “Módulo de gastos”
**No conviene como item único.**

División correcta:
- Crear gasto borrador
- Agregar línea
- Editar/eliminar línea
- Recalcular total
- Listado propio
- Detalle
- Política de edición por estado

## B. “Flujo de aprobación”
**No conviene como item único.**

División correcta:
- Migraciones approvals
- Validación previa al envío
- Submit a jefe
- Bandeja jefe
- Aprobar jefe
- Rechazar jefe
- Corrección/reenvío
- Bandeja CXP
- Aprobar CXP
- Rechazar CXP

## C. “Soporte de documentos”
**No conviene como item único.**

División correcta:
- Migraciones documentos
- Servicio de storage
- Upload XML
- Parser XML
- Asociación a línea
- Validación línea vs XML
- Regla obligatoria de documento al enviar

## D. “Presupuesto”
**No conviene como item único.**

División correcta:
- Modelo presupuesto
- CRUD presupuesto
- Acumulado ejercido
- Vista comparativa
- Alerta presupuestal

## E. “Reportes”
**No conviene como item único.**

División correcta:
- Consulta operativa filtrable
- Vista operativa
- Reporte gasto vs presupuesto
- Exportación, solo si se prioriza después

# 8. Backlog listo para usarse con una IA desarrolladora

## Primeros 10 items más convenientes

| Orden | ID | Item | Por qué está al inicio | Qué desbloquea |
|---|---|---|---|---|
| 1 | BL-001 | Estándar de arranque backend | Es el cimiento más pequeño y menos ambiguo | Todo el backend posterior |
| 2 | BL-002 | Respuesta JSON estándar | Evita deuda técnica desde el primer endpoint | Integración consistente FE/BE |
| 3 | BL-003 | Conexión DB y repositorio base | Sin persistencia no hay auth ni catálogos | Migraciones, login y datos |
| 4 | BL-004 | Bootstrap frontend mínimo | Permite probar navegación y consumo API | Login y pantallas futuras |
| 5 | BL-005 | Manejo técnico de errores FE/BE | Hace testeable el sistema desde temprano | Pruebas manuales más limpias |
| 6 | BL-006 | Migraciones seguridad mínima | Materializa usuarios, roles y áreas | Login y RBAC |
| 7 | BL-007 | Servicio login | Primer flujo real de acceso | Middleware y sesión |
| 8 | BL-008 | Pantalla login | Primer slice vertical completo | Acceso usable al sistema |
| 9 | BL-009 | Middleware autenticación | Protege el sistema antes del negocio | Endpoints privados |
| 10 | BL-010 | Endpoint sesión actual | Da contexto de usuario, área y roles | UI por rol y ownership |

## Primer incremento real de desarrollo
El primer incremento real debería ser:

**BL-001 + BL-002 como paquete controlado de fundación técnica mínima**

porque juntos dejan:
- backend arrancable,
- endpoint health verificable,
- y formato de respuesta/error congelado.

