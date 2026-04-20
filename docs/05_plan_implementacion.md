# Plan de implementación incremental
## Sistema de Gestión de Gastos Empresariales

---

# 1. Contexto integral del proyecto para cualquier IA

## 1.1 Objetivo del sistema
Construir una aplicación web empresarial on-premise para gestionar gastos operativos de una sola organización, cubriendo el flujo completo desde la captura del gasto hasta su aprobación y consolidación en reportes operativos.

El sistema debe priorizar:
- trazabilidad del gasto,
- mantenibilidad,
- bajo acoplamiento,
- seguridad,
- crecimiento controlado,
- y simplicidad técnica.

## 1.2 Alcance funcional confirmado
Incluye:
- registro manual de gastos,
- carga de archivos XML CFDI 4.0,
- extracción automática de datos clave del XML,
- flujo de aprobación por área,
- control presupuestal mensual por área,
- reportes operativos,
- auditoría básica mediante bitácora.

Fuera de alcance por ahora:
- integraciones automáticas con ERP,
- categorización automática de gastos,
- validaciones fiscales externas profundas.

## 1.3 Contexto organizacional
- Es una aplicación para una sola empresa.
- Cada usuario pertenece a una sola área.
- Cada área tiene un jefe único.
- Cada área está asociada a centros de costo.
- Existe control presupuestal mensual por área.
- Se requiere retención mínima de 5 años.

## 1.4 Roles del sistema
- **Usuario / Capturista:** registra, edita y envía gastos; ve sus propios registros.
- **Jefe de área:** revisa, aprueba o rechaza gastos; ve gastos y presupuesto de su área.
- **CXP:** consulta gastos aprobados y valida consistencia operativa.
- **Administrador:** gestiona usuarios, roles, áreas, presupuestos y configuración general.

## 1.5 Arquitectura objetivo
### Backend
- PHP con arquitectura MVC clásica.
- Sin frameworks pesados.
- Separación por capas: controladores, servicios, repositorios, DTOs, entidades y rutas por módulo.
- API organizada por features.

### Frontend
- HTML5, CSS3, Bootstrap, jQuery, DataTables.
- Separación entre presentación, lógica, servicios, helpers, router y contexto.
- Consumo de API mediante AJAX con jQuery.

### Persistencia
- MySQL / MariaDB.
- Almacenamiento local de documentos.

## 1.6 Convenciones y lineamientos relevantes
- Tablas en `snake_case` plural.
- Columnas en `snake_case`.
- PK estándar: `id`.
- FK estándar: `tabla_id`.
- Validación backend obligatoria.
- Hash seguro para contraseñas.
- Protección CSRF/XSS/SQL Injection.
- Manejo seguro de XML.
- RBAC.
- Bitácora inmutable.

## 1.7 Entidades ya definidas o claramente inferidas del contexto
### Organización y seguridad
- users
- roles
- user_roles
- areas
- cost_centers
- area_managers

### Catálogos operativos
- vendors
- expense_categories
- fiscal_deductibility_concepts
- currencies

### Operación de gastos
- expenses
- expense_lines
- expense_statuses
- expense_status_history
- expense_approvals

### Archivos
- expense_line_documents
- expense_line_document_files

## 1.8 Flujo funcional base del gasto
1. Creación en borrador.
2. Carga de XML CFDI.
3. Validación interna.
4. Envío a aprobación.
5. Revisión por jefe.
6. Aprobación o rechazo.
7. Corrección si aplica.
8. Consolidación en reportes.

Estados base reconocidos:
- Borrador
- Pendiente
- Aprobado
- Rechazado

## 1.9 Restricciones estratégicas para construir el sistema
- El desarrollo debe hacerse en incrementos pequeños y verificables.
- Cada slice debe integrarse naturalmente con lo ya construido.
- No se deben construir módulos gigantes sin validar.
- Debe evitarse refactorizar partes estables salvo que sea indispensable.
- Deben priorizarse primero los cimientos técnicos y después los casos de uso.
- La implementación debe mantenerse alineada con el diseño y el diccionario de datos ya definidos.

## 1.10 Ambigüedades o huecos que se deben vigilar durante la implementación
Estas definiciones no deben bloquear el avance inicial, pero sí validarse más adelante:
- No se ve todavía una definición completa de la tabla de presupuestos y su bitácora de cambios.
- No está completamente detallado el modelo de auditoría / bitácora.
- No está completamente definido el contrato OpenAPI final.
- No está detallado aún el manejo exacto de adjuntos distintos al XML.
- No está detallado si habrá carga de varias líneas por gasto desde la primera versión o si conviene iniciar con una sola línea por gasto y luego ampliar.
- No se observa todavía un catálogo explícito de motivos de rechazo ni reglas detalladas de edición después del rechazo.

---

# 2. Estrategia general de implementación

La implementación debe seguir una lógica de desbloqueo:
1. **Base técnica mínima operable**
2. **Seguridad y acceso**
3. **Catálogos y estructura organizacional**
4. **Caso de uso núcleo: crear gasto**
5. **Flujo de aprobación**
6. **Documentos y XML**
7. **Presupuesto y validaciones de negocio**
8. **Reportes y auditoría**
9. **Refinamientos operativos y endurecimiento**

Cada fase debe dividirse en slices pequeños, integrables y probables manualmente.

---

# 3. Plan de implementación por fases

## Fase 0. Preparación técnica y alineación
### Objetivo
Asegurar que la base documental, estructura de carpetas y decisiones arquitectónicas estén listas para ejecutar el desarrollo incremental sin contradicciones.

### Resultado esperado
- Estructura backend y frontend confirmada.
- Convenciones de nombres y capas congeladas.
- Lista de módulos priorizada.
- Definición del orden de construcción aceptada.

### Slices sugeridos
- 0.1 Validación final de arquitectura y estructura de carpetas.
- 0.2 Validación del alcance MVP vs fases posteriores.
- 0.3 Confirmación del orden de desarrollo incremental.

---

## Fase 1. Base técnica mínima del backend y frontend
### Objetivo
Tener una aplicación arrancable, navegable y comprobable, aunque todavía sin negocio completo.

### Resultado esperado
- Backend con arranque, enrutamiento, configuración de entorno y respuesta JSON estándar.
- Frontend con bootstrap inicial, router simple, layout base y configuración de consumo API.
- Endpoint de health operativo.
- Manejo básico de errores técnicos.

### Slices sugeridos
- 1.1 Backend bootstrap mínimo: front controller, carga de entorno, router, respuesta JSON y health check.
- 1.2 Conexión a base de datos y mecanismo base de repositorio / query builder.
- 1.3 Frontend bootstrap mínimo: index, layout base, router simple, configuración AJAX.
- 1.4 Manejo estándar de errores frontend/backend.

### Razón de prioridad
Sin esta base, todo lo demás se construiría sobre supuestos inestables.

---

## Fase 2. Seguridad base y autenticación
### Objetivo
Habilitar acceso controlado al sistema con sesiones o tokens, respetando roles y áreas.

### Resultado esperado
- Login funcional.
- Logout funcional.
- Identificación del usuario autenticado.
- Restricción básica a rutas protegidas.
- Carga del contexto mínimo de sesión: usuario, área y roles.

### Slices sugeridos
- 2.1 Migraciones y seeders mínimos para usuarios, roles, áreas y user_roles.
- 2.2 Servicio de autenticación y endpoint de login.
- 2.3 Pantalla de login integrada al backend.
- 2.4 Middleware de autenticación.
- 2.5 Middleware de rol / autorización mínima.
- 2.6 Endpoint de perfil / sesión actual.

### Razón de prioridad
Todos los casos de uso dependen de saber quién es el usuario y qué puede hacer.

---

## Fase 3. Catálogos organizacionales y operativos mínimos
### Objetivo
Construir la base de datos y administración mínima necesaria para operar el negocio real.

### Resultado esperado
- Áreas administrables.
- Centros de costo consultables.
- Jefes por área configurables.
- Proveedores, categorías, conceptos fiscales y monedas disponibles.

### Slices sugeridos
- 3.1 CRUD mínimo de áreas.
- 3.2 CRUD mínimo de centros de costo.
- 3.3 Asignación de jefe por área.
- 3.4 CRUD mínimo de proveedores.
- 3.5 CRUD mínimo de categorías de gasto.
- 3.6 CRUD mínimo de monedas.
- 3.7 Seeders de estados base del gasto.

### Razón de prioridad
La captura de gastos necesita catálogos y estructura organizacional antes de existir de forma útil.

---

## Fase 4. Caso de uso núcleo: gasto en borrador
### Objetivo
Permitir que un usuario autenticado cree y consulte gastos en estado borrador.

### Resultado esperado
- Creación de gasto borrador.
- Edición básica del encabezado.
- Alta de líneas de gasto.
- Cálculo de totales.
- Consulta de listado propio.
- Consulta de detalle del gasto.

### Slices sugeridos
- 4.1 Migraciones de expenses, expense_lines y estados relacionados.
- 4.2 Endpoint para crear gasto borrador.
- 4.3 Endpoint para agregar / editar / eliminar líneas del gasto.
- 4.4 Regla de cálculo de total del encabezado a partir de líneas.
- 4.5 Listado de gastos propios.
- 4.6 Detalle de gasto.
- 4.7 Pantalla de captura y pantalla de listado.

### Razón de prioridad
Este es el primer flujo núcleo real del negocio y debe existir antes de documentos, aprobaciones y reportes.

---

## Fase 5. Soporte de documentos y carga de XML
### Objetivo
Asociar comprobantes a las líneas de gasto y habilitar la extracción básica de datos del CFDI.

### Resultado esperado
- Carga de XML por línea de gasto.
- Registro del archivo en almacenamiento local.
- Extracción básica de datos clave.
- Vinculación del documento a la línea.
- Validaciones técnicas mínimas del archivo.

### Slices sugeridos
- 5.1 Migraciones para documentos y archivos físicos.
- 5.2 Servicio de almacenamiento local seguro.
- 5.3 Endpoint de carga de XML.
- 5.4 Parser XML básico para RFC, UUID, fecha y montos.
- 5.5 Asociación del XML a la línea de gasto.
- 5.6 Visualización del documento cargado en el detalle.

### Razón de prioridad
El XML forma parte central del caso de uso, pero conviene agregarlo después de que la captura básica ya funcione.

---

## Fase 6. Envío a aprobación y flujo del jefe de área
### Objetivo
Habilitar la transición de borrador a pendiente y el proceso de aprobación o rechazo por el jefe del área.

### Resultado esperado
- Validación previa al envío.
- Cambio de estado a pendiente.
- Registro de historial de estado.
- Bandeja de aprobación del jefe.
- Acción de aprobar o rechazar.
- Comentario de decisión.

### Slices sugeridos
- 6.1 Endpoint de envío a aprobación.
- 6.2 Validaciones mínimas previas al envío.
- 6.3 Registro de historial de estado.
- 6.4 Tabla y lógica de approvals.
- 6.5 Bandeja de gastos pendientes del jefe.
- 6.6 Acción de aprobar.
- 6.7 Acción de rechazar.
- 6.8 Regla de visibilidad por área y rol.

### Razón de prioridad
Con esto se completa el flujo principal del negocio desde captura hasta decisión.

---

## Fase 7. Reglas de negocio adicionales y corrección posterior al rechazo
### Objetivo
Endurecer el flujo real del negocio y permitir iteración operativa controlada.

### Resultado esperado
- Reglas claras de edición por estado.
- Reapertura o corrección después del rechazo.
- Validaciones de integridad obligatorias.
- Restricciones por rol y propiedad del gasto.

### Slices sugeridos
- 7.1 Política de edición por estado.
- 7.2 Corrección y reenvío de gastos rechazados.
- 7.3 Validación obligatoria de documento por línea.
- 7.4 Validación de consistencia entre línea y XML.
- 7.5 Mensajes de error funcionales más claros.

### Razón de prioridad
Una vez que el flujo principal existe, se fortalecen los bordes operativos para evitar inconsistencias.

---

## Fase 8. Control presupuestal por área
### Objetivo
Introducir el componente financiero que compara gasto real contra presupuesto mensual.

### Resultado esperado
- Modelo de presupuesto mensual por área.
- Consulta de presupuesto disponible.
- Acumulado de gasto por periodo.
- Señalización básica de gasto vs presupuesto.

### Slices sugeridos
- 8.1 Definición y validación final del modelo de presupuestos.
- 8.2 Migraciones y catálogos asociados.
- 8.3 CRUD mínimo de presupuestos.
- 8.4 Cálculo de gasto acumulado mensual por área.
- 8.5 Vista básica de presupuesto para jefe y admin.
- 8.6 Validación o alerta al capturar / enviar gasto.

### Razón de prioridad
El presupuesto es importante, pero conviene montarlo después de tener operativo el flujo principal del gasto.

---

## Fase 9. Reportes operativos
### Objetivo
Explotar la información operativa ya capturada y aprobada.

### Resultado esperado
- Reportes por usuario, área, estado, periodo y categoría.
- Filtros básicos.
- Tablas navegables.
- Exportación si se decide incorporarla en una etapa posterior pequeña.

### Slices sugeridos
- 9.1 Endpoint de consulta de gastos con filtros.
- 9.2 Vista de listado operativo ampliado.
- 9.3 Reporte por área y periodo.
- 9.4 Reporte de estatus del gasto.
- 9.5 Reporte de gasto vs presupuesto.

### Razón de prioridad
Los reportes deben construirse cuando el dato operativo ya sea confiable.

---

## Fase 10. Auditoría y trazabilidad reforzada
### Objetivo
Registrar acciones relevantes y consolidar evidencia operativa.

### Resultado esperado
- Bitácora de acciones críticas.
- Consulta básica de eventos.
- Evidencia de cambios de estado, aprobaciones y cargas de documentos.

### Slices sugeridos
- 10.1 Definición final del modelo de auditoría.
- 10.2 Registro automático de eventos clave.
- 10.3 Consulta técnica / administrativa de bitácora.
- 10.4 Reglas de inmutabilidad.

### Razón de prioridad
La auditoría gana valor cuando los flujos principales ya están estabilizados.

---

## Fase 11. Endurecimiento técnico y refinamientos
### Objetivo
Mejorar robustez, seguridad, mantenibilidad y experiencia operativa.

### Resultado esperado
- Validaciones reforzadas.
- Mejor manejo de errores.
- Optimización de consultas.
- Pruebas automatizadas mínimas.
- Documentación técnica actualizada.

### Slices sugeridos
- 11.1 Hardening de seguridad.
- 11.2 Optimización de índices y consultas.
- 11.3 Pruebas unitarias e integración para flujos críticos.
- 11.4 Ajustes de UX y consistencia visual.
- 11.5 Actualización de OpenAPI y documentación técnica.

### Razón de prioridad
Esto consolida el sistema una vez que el negocio principal ya está funcional.

---

# 4. Secuencia recomendada de slices

## Orden macro recomendado
1. Base técnica mínima.
2. Autenticación y sesión.
3. Catálogos organizacionales mínimos.
4. Gasto en borrador.
5. Líneas de gasto y totales.
6. Carga de XML por línea.
7. Envío a aprobación.
8. Aprobación / rechazo por jefe.
9. Corrección tras rechazo.
10. Presupuestos.
11. Reportes.
12. Auditoría reforzada.
13. Endurecimiento final.

## Orden fino sugerido para arrancar
1. Backend bootstrap mínimo.
2. Frontend bootstrap mínimo.
3. Login y sesión.
4. Seeders y catálogos base.
5. Crear gasto borrador.
6. Agregar líneas al gasto.
7. Calcular totales.
8. Listar y consultar gastos propios.
9. Cargar XML.
10. Enviar a aprobación.
11. Bandeja del jefe.
12. Aprobar / rechazar.

---

# 5. Principios obligatorios para cualquier IA desarrolladora que use este plan

1. Avanzar siempre por incrementos pequeños y verificables.
2. No mezclar demasiadas responsabilidades en un solo paso.
3. No modificar arquitectura ni nombres sin justificación.
4. Integrar lo nuevo con lo ya existente, sin reescribir innecesariamente.
5. Implementar manejo básico de errores desde el inicio.
6. Mantener validaciones críticas del lado backend.
7. Respetar separación de capas y carpetas.
8. Entregar siempre resumen de archivos creados o modificados.
9. No inventar reglas de negocio mayores sin declararlas como supuesto.
10. Antes de pasar a la siguiente fase, validar manualmente el slice actual.

---

# 6. Riesgos globales del plan

## Riesgos funcionales
- Falta de definición completa del presupuesto puede retrasar esa fase.
- La granularidad real de las líneas de gasto podría requerir ajuste.
- El flujo de CXP todavía no está completamente aterrizado como proceso operativo detallado.

## Riesgos técnicos
- Si se construye demasiado temprano la lógica de documentos, puede contaminar el flujo núcleo.
- Si no se define desde el inicio el estándar de respuestas API, se generará deuda técnica rápida.
- Si la autenticación queda ambigua entre sesión y JWT, puede haber retrabajo.

## Riesgos de secuencia
- Saltar directo a captura completa sin base técnica aumentará errores.
- Construir reportes antes de estabilizar el dato operativo generará retrabajo.
- Construir presupuestos demasiado pronto puede frenar el MVP funcional.

---

# 7. Recomendación de estrategia de trabajo

La mejor forma de continuar este proyecto es trabajar por **slices verticales pequeños**, donde cada incremento incluya solo lo mínimo necesario de frontend, backend, persistencia y validación para dejar una capacidad funcional terminada y comprobable.

La meta no debe ser “terminar módulos”, sino “poner en funcionamiento capacidades verificables”.

Ejemplos de slices correctos:
- login funcional,
- crear gasto borrador,
- agregar una línea al gasto,
- cargar un XML,
- enviar a aprobación,
- aprobar un gasto.

Ejemplos de slices demasiado grandes:
- “hacer todo el módulo de gastos”,
- “hacer toda la administración”,
- “hacer frontend y backend completos de reportes”.

---

# 8. Criterio para elegir siempre el siguiente incremento

Cuando se pida el siguiente paso, debe elegirse el incremento que:
1. dependa del menor número de piezas no construidas,
2. desbloquee el siguiente flujo importante,
3. pueda probarse manualmente sin ambigüedad,
4. no obligue a construir muchas pantallas o tablas a la vez,
5. y fortalezca la base antes de ampliar alcance.

---

# 9. Estado esperado para continuar después de este plan

Una vez aceptado este plan, el trabajo debe continuar siempre así:
- se identifica el siguiente incremento recomendado,
- se define su alcance exacto,
- se redacta un prompt delimitado para Cursor/Codex,
- se implementa,
- se prueba manualmente,
- se reporta resultado,
- y solo entonces se propone el siguiente incremento.

---

**Fin del plan de implementación incremental.**

