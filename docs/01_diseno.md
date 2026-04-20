# Documento de Diseño  
## Sistema de Gestión de Gastos Empresariales

**Versión:** 1.0  
**Clasificación:** Confidencial — Uso Interno

---

## 1. Visión general de la aplicación

El Sistema de Gestión de Gastos Empresariales es una aplicación web corporativa desplegada en infraestructura on-premise, operada exclusivamente dentro del perímetro tecnológico de la organización. No depende de servicios en la nube ni de proveedores externos de procesamiento de datos.

Su alcance cubre una sola empresa, administrando el ciclo completo del gasto operacional: desde la generación del comprobante hasta su aprobación, consolidación contable y archivo histórico. La herramienta no está diseñada para operar en entornos multiempresa ni multi-tenant.

El objetivo central es proporcionar trazabilidad y control efectivo sobre todos los egresos corporativos. Cada gasto registrado pasa por un flujo de aprobación estructurado por área, lo que permite identificar responsables, validar montos contra presupuesto y generar evidencia auditable en cada etapa.

El ciclo completo del gasto comprende: registro o carga del comprobante fiscal, extracción de datos clave, revisión por el jefe de área, aprobación o rechazo por Cuentas por Pagar (CXP), y consolidación en reportes operativos y de auditoría.

La retención de datos es de mínimo 5 años para todos los registros de gastos, archivos XML adjuntos y bitácoras de auditoría, en cumplimiento con los requerimientos de conservación documental fiscal y corporativa.

El diseño privilegia simplicidad y mantenibilidad: código PHP sin frameworks complejos, base de datos relacional estándar, y una separación clara de capas que permite que cualquier desarrollador con conocimiento básico de la pila tecnológica pueda leer, mantener y extender el sistema.

## 2. Alcance funcional

### Incluido en el alcance

- Registro manual de gastos con captura de datos descriptivos, monto, fecha, área y centro de costo.
- Carga de archivos XML en formato CFDI 4.0 asociados a cada gasto registrado.
- Extracción automática de datos clave del XML: RFC emisor, RFC receptor, folio fiscal, fecha, importe total, IVA y concepto principal.
- Flujo de aprobación por área con intervención del jefe de área y del equipo CXP.
- Control presupuestal mensual por área con seguimiento del gasto ejercido vs. presupuesto asignado.
- Reportes operativos exportables por área, periodo, estatus y centro de costo.
- Auditoría básica mediante bitácora de eventos inmutable que registra acciones sobre gastos, cambios presupuestales y acciones administrativas.

### Fuera de alcance

- Integraciones automáticas con sistemas ERP (SAP, Oracle, Contpaq, etc.).
- Categorización automática de gastos mediante reglas o modelos de clasificación.
- Validaciones fiscales externas profundas (consulta al SAT, verificación de sellos digitales en tiempo real).

## 3. Contexto organizacional

### 3.1 Estructura

La organización se modela con las siguientes entidades estructurales:

- **Áreas organizacionales:** unidades funcionales de la empresa (Finanzas, Operaciones, TI, Recursos Humanos, etc.). Cada área es propietaria de su propio presupuesto mensual.
- **Centros de costo:** subcategorías contables opcionales vinculadas a un área. Permiten desglosar el gasto con mayor granularidad sin alterar la estructura de aprobación.
- **Jefe único por área:** cada área tiene exactamente un usuario designado como jefe, responsable de revisar y aprobar los gastos generados por su equipo.
- **Pertenencia exclusiva del usuario:** cada usuario del sistema pertenece a una sola área. No se soportan usuarios con roles simultáneos en múltiples áreas, salvo los roles transversales de CXP y Administrador.

### 3.2 Presupuesto

- **Presupuesto mensual por área:** se registra un monto aprobado de gasto para cada área en cada mes calendario. El presupuesto es gestionado por el Administrador o CXP.
- **Comparación gasto real vs. presupuesto:** el sistema calcula en tiempo real el monto ejercido (gastos en estatus Aprobado) frente al presupuesto asignado, mostrando el porcentaje de ejecución y el saldo disponible.
- **Trazabilidad de cambios presupuestales:** cualquier modificación al monto de presupuesto de un área queda registrada en la bitácora con usuario responsable, fecha, valor anterior y valor nuevo.

## 4. Roles del sistema

### Usuario (Capturista)

- **Puede hacer:** crear gastos en borrador, cargar el XML CFDI asociado, editar sus propios gastos en borrador, enviar gastos a aprobación, corregir gastos rechazados que le pertenecen.
- **Puede visualizar:** sus propios gastos en todos los estatus, el resumen de presupuesto disponible de su área.
- **Administra:** nada. No tiene acceso a configuración ni a datos de otros usuarios.

### Jefe de área

- **Puede hacer:** revisar, aprobar o rechazar gastos del área que lidera. Puede agregar comentarios en el rechazo. Puede consultar el presupuesto de su área.
- **Puede visualizar:** todos los gastos de su área (en cualquier estatus), reportes de ejecución presupuestal de su área.
- **Administra:** nada de configuración. Su acción se limita al flujo de aprobación de primera instancia.

### CXP (Cuentas por Pagar)

- **Puede hacer:** revisar y aprobar o rechazar gastos que ya fueron aprobados por el jefe de área. Es la segunda y última instancia de aprobación. También puede registrar o ajustar presupuestos mensuales por área.
- **Puede visualizar:** todos los gastos de todas las áreas, reportes consolidados de gasto por área y periodo, bitácora de cambios presupuestales.
- **Administra:** presupuestos mensuales por área.

### Administrador

- **Puede hacer:** gestionar usuarios (alta, baja, asignación de rol y área), gestionar catálogos (áreas, centros de costo, tipos de gasto), consultar la bitácora de auditoría completa, configurar parámetros generales del sistema.
- **Puede visualizar:** todo el sistema sin restricción de área.
- **Administra:** usuarios, roles, áreas, centros de costo, catálogos y configuración general.

## 5. Arquitectura general

### 5.1 Enfoque arquitectónico

El sistema sigue el patrón MVC clásico implementado en PHP nativo, sin frameworks full-stack. Esta decisión reduce la curva de aprendizaje del equipo, elimina dependencias de terceros que requieran mantenimiento activo, y garantiza portabilidad entre distintos entornos de servidor Apache/Nginx.

La separación de capas es estricta: la capa de presentación (vistas) no contiene lógica de negocio; los controladores son responsables únicamente del enrutamiento y la orquestación de llamadas; la lógica de negocio reside en servicios; y el acceso a datos se encapsula en repositorios.

### 5.2 Componentes principales

#### Frontend

- HTML5 / CSS3 para estructura y presentación.
- Bootstrap como framework de grilla y componentes UI responsivos.
- JavaScript / jQuery para interacciones dinámicas, validaciones en cliente y carga asíncrona de secciones.

#### Backend

- PHP como lenguaje del servidor, sin frameworks adicionales.
- **Controladores:** reciben las solicitudes HTTP, validan parámetros de entrada y delegan a los servicios correspondientes.
- **Servicios:** contienen la lógica de negocio (flujo de aprobación, cálculo presupuestal, extracción XML, etc.).
- **Modelos / Repositorios:** abstraen el acceso a la base de datos mediante consultas parametrizadas. No hay ORM.

#### Persistencia

- MySQL / MariaDB como motor de base de datos relacional. Esquema normalizado con llaves foráneas, índices en columnas de búsqueda frecuente y restricciones de integridad definidas en el motor.
- Almacenamiento local de XML en el sistema de archivos del servidor, organizado por año/mes/área, con ruta almacenada en base de datos para recuperación.

#### Infraestructura

- Servidor on-premise con sistema operativo Linux (preferentemente CentOS/RHEL o Ubuntu LTS), Apache o Nginx, y PHP 8.x.
- Respaldo periódico de base de datos y directorio de archivos XML mediante scripts programados (cron). La estrategia de respaldo y retención de 5 años es responsabilidad del equipo de infraestructura.

## 6. Módulos funcionales

### Módulo de Autenticación

- Login con usuario y contraseña, hash seguro (bcrypt).
- Gestión de sesión PHP con tiempo de expiración configurable.
- Cierre de sesión explícito e invalidación de sesión.
- Protección de rutas por rol (middleware de autorización).

### Módulo de Administración

- CRUD de usuarios: alta, baja lógica, asignación de área y rol.
- CRUD de áreas y centros de costo.
- Gestión de catálogos: tipos de gasto, proveedores frecuentes.
- Configuración de parámetros del sistema (nombre de empresa, ejercicio fiscal activo).

### Módulo de Captura de Gastos

- Formulario de registro manual con campos: fecha, descripción, proveedor, tipo de gasto, monto, IVA, área, centro de costo.
- Carga de archivo XML CFDI 4.0 con extracción automática de: RFC emisor, RFC receptor, folio fiscal UUID, fecha timbrado, subtotal, IVA, total.
- Validación de formato XML y estructura básica del nodo Comprobante antes de guardar.
- Almacenamiento del archivo físico con ruta de referencia en base de datos.
- Gestión de gastos propios en estatus Borrador: edición y eliminación permitidas.

### Módulo de Flujo de Autorización

- Envío de gasto a revisión: cambia estatus de Borrador a Pendiente.
- Panel del jefe de área: lista de gastos pendientes de su área con detalle y XML adjunto.
- Aprobación de primera instancia por jefe de área: avanza el gasto a revisión CXP.
- Rechazo por jefe de área: regresa el gasto al capturista con comentario obligatorio.
- Panel CXP: lista de gastos aprobados por jefes, pendientes de aprobación final.
- Aprobación final CXP: gasto queda en estatus Aprobado y se refleja en presupuesto ejercido.
- Rechazo CXP: regresa al capturista con comentario. El jefe de área es notificado en su panel.

### Módulo de Reportes

- Reporte de gastos por área y periodo con filtros por estatus, tipo de gasto y centro de costo.
- Reporte de ejecución presupuestal: gasto real vs. presupuesto por área y mes.
- Reporte de gastos rechazados con historial de comentarios.
- Exportación a formato CSV y/o PDF según requerimiento operativo.

### Módulo de Auditoría

- Bitácora inmutable de eventos: login/logout, creación y modificación de gastos, cambios de estatus en el flujo, modificaciones presupuestales, cambios en usuarios y catálogos.
- Registro de: usuario que ejecutó la acción, fecha/hora, entidad afectada, valores anteriores y nuevos.
- Consulta de bitácora disponible para Administrador con filtros por tipo de evento, usuario y rango de fechas.

## 7. Flujo completo del gasto

El flujo de vida de un gasto sigue los pasos a continuación de forma secuencial y sin saltos intermedios:

1. **Creación en borrador:** el capturista registra el gasto manualmente o mediante carga XML. El gasto queda en estatus Borrador. Puede ser editado o eliminado libremente por su creador.
2. **Carga XML:** el capturista adjunta el archivo CFDI 4.0. El sistema extrae los datos clave y los pre-llena en el formulario. El archivo se almacena localmente con referencia en base de datos.
3. **Validación previa al envío:** el sistema verifica que los campos obligatorios estén completos y que el XML tenga estructura válida. Sin estas condiciones, el envío es bloqueado.
4. **Envío a aprobación:** el capturista envía el gasto. El estatus cambia a Pendiente y el gasto aparece en el panel del jefe de área correspondiente.
5. **Revisión por jefe de área:** el jefe revisa el gasto, consulta el XML adjunto y verifica la pertinencia del egreso. Puede aprobar o rechazar.
6. **Aprobación por jefe de área:** el gasto avanza al panel de CXP para aprobación final. El jefe no puede revertir esta acción sin intervención de CXP.
7. **Rechazo por jefe de área o CXP:** el gasto regresa al capturista con estatus Rechazado y un comentario obligatorio explicando el motivo.
8. **Corrección y reenvío:** el capturista corrige el gasto rechazado (puede modificar datos y reemplazar el XML) y lo reenvía. El flujo reinicia desde el paso 4.
9. **Aprobación final CXP:** CXP aprueba el gasto. El estatus cambia a Aprobado, el monto se acumula al gasto ejercido del área en el mes correspondiente y el gasto queda bloqueado para edición.
10. **Consolidación en reportes:** los gastos en estatus Aprobado se reflejan automáticamente en los reportes operativos y en el comparativo presupuestal.

### Estados definidos del gasto

- **Borrador:** creado, no enviado. Editable por el capturista.
- **Pendiente:** enviado a flujo de aprobación. No editable por el capturista.
- **Aprobado:** aprobado por CXP. Bloqueado para modificación.
- **Rechazado:** rechazado en cualquier instancia. Editable por el capturista para corrección y reenvío.

## 8. Modelo de datos conceptual

### Entidades principales

- **Usuario:** id, nombre, correo, hash_password, id_rol, id_area, activo, fecha_creacion.
- **Rol:** id, nombre (Usuario, Jefe, CXP, Administrador), permisos codificados por módulo.
- **Área:** id, nombre, descripcion, activo.
- **Centro de costo:** id, nombre, id_area, activo.
- **Presupuesto:** id, id_area, mes, anio, monto_asignado, fecha_registro, id_usuario_registro.
- **Gasto:** id, id_usuario_creador, id_area, id_centro_costo, fecha_gasto, descripcion, id_tipo_gasto, proveedor, subtotal, iva, total, estatus, fecha_creacion, fecha_ultimo_cambio.
- **Adjunto:** id, id_gasto, tipo (XML / complemento), ruta_archivo, fecha_carga.
- **CFDI:** id, id_gasto, uuid_fiscal, rfc_emisor, nombre_emisor, rfc_receptor, fecha_timbrado, subtotal, iva, total, moneda, metodo_pago, uso_cfdi.
- **Bitácora:** id, id_usuario, accion, entidad, id_entidad, valor_anterior (JSON), valor_nuevo (JSON), ip_origen, fecha_hora.
- **Catálogos:** tablas de tipos de gasto y proveedores frecuentes con estructura id, nombre, activo.

### Relaciones clave

- Un Usuario pertenece a un Área y a un Rol.
- Un Gasto pertenece a un Área y opcionalmente a un Centro de Costo.
- Un Gasto tiene cero o un CFDI asociado.
- Un Gasto puede tener uno o más Adjuntos.
- Un Presupuesto corresponde a un Área en un mes/año específico (llave compuesta).
- La Bitácora referencia cualquier entidad del sistema sin llave foránea formal (entidad + id como clave lógica).

## 9. Consideraciones técnicas y de seguridad

- **RBAC (Control de Acceso Basado en Roles):** cada solicitud HTTP es verificada contra el rol de la sesión activa antes de ejecutar cualquier acción. No existe lógica de permisos en el frontend.
- **Validación backend obligatoria:** toda validación de datos de entrada se realiza en el servidor. Las validaciones en JavaScript son complementarias, nunca definitivas.
- **Hash seguro de contraseñas:** almacenamiento exclusivo con bcrypt (`password_hash` / `password_verify` de PHP). Nunca texto plano ni MD5/SHA1.
- **Protección CSRF:** token único por sesión incluido en todos los formularios POST. El servidor rechaza solicitudes sin token válido.
- **Protección XSS:** escape de salida obligatorio en todas las variables renderizadas en vistas con `htmlspecialchars`.
- **Prevención de SQL Injection:** uso exclusivo de consultas parametrizadas (PDO con placeholders). Prohibición de concatenación de variables en consultas SQL.
- **Manejo seguro de XML:** parseo con libxml desactivando la carga de entidades externas (`LIBXML_NONET`, `LIBXML_NOENT`) para prevenir ataques XXE.
- **Bitácora inmutable:** los registros de auditoría no tienen operación de actualización ni eliminación expuesta en la aplicación.
- **Retención de 5 años:** gastos, archivos XML y registros de bitácora no se eliminan lógicamente antes de cumplir el periodo de retención.
- **Respaldos periódicos:** frecuencia mínima diaria para datos transaccionales.

## 10. Lineamientos de calidad y mantenibilidad

- **Separación estricta por capas:** ninguna vista contiene SQL, ningún controlador contiene lógica de negocio, ningún repositorio contiene reglas de dominio.
- **Servicios de negocio centralizados:** toda la lógica del flujo de aprobación, cálculo de presupuesto y procesamiento XML reside en clases de servicio reutilizables e independientes del protocolo HTTP.
- **Uso mínimo de librerías externas:** solo Bootstrap y jQuery en frontend; sin dependencias externas en backend. Las librerías incluidas deben ser versionadas y almacenadas localmente.
- **Convenciones claras y documentadas:** snake_case para base de datos, PascalCase para clases PHP, camelCase para funciones JS.
- **Preparación para crecimiento controlado:** los módulos están diseñados para agregar nuevas áreas, roles adicionales o flujos extendidos sin refactorización mayor.

## 11. Conclusión

El diseño establece una arquitectura sólida, predecible y alineada con las necesidades reales de la organización. La elección de PHP nativo con patrón MVC, base de datos relacional y despliegue on-premise garantiza independencia tecnológica completa.

El control financiero se logra mediante un flujo de aprobación de dos instancias con estados definidos, comparativo presupuestal en tiempo real y exportación de reportes operativos. Cada gasto registrado tiene un responsable identificado, un historial de cambios trazable y un soporte documental en formato CFDI 4.0.

La trazabilidad es completa: desde la creación del gasto hasta su aprobación final, pasando por cada rechazo y corrección, toda acción queda registrada en la bitácora de auditoría con usuario, fecha, IP y valores antes/después.

La mantenibilidad del sistema está garantizada por la separación estricta de capas, la ausencia de frameworks complejos y la adopción de convenciones explícitas.

En conjunto, este sistema provee una solución empresarial completa, técnicamente robusta y operable por un equipo de desarrollo de tamaño reducido, sin sacrificar control, trazabilidad ni seguridad.
