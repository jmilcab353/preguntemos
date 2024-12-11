# Proyecto Symfony

## Roles
- **ROLE_ADMIN** (o **ROLE_PROFESOR**)
- **ROLE_USER**

### Reglas de Acceso
- Solo se puede acceder al sistema si estás logueado (excepto rutas públicas específicas). 
- Si no estás autenticado, solo se muestra la pantalla de login.

---

## Funcionalidades del Administrador

### Gestión de Preguntas
El administrador puede crear, editar, eliminar y visualizar preguntas. Las preguntas tienen las siguientes características:
- **Campos**: `enunciado`, `respuestas` (mínimo 2, máximo 4).
- Solo puede haber una pregunta activa (viva) en un mismo periodo de tiempo. Cada pregunta tiene:
  - `fecha_inicio`
  - `fecha_fin`
- Las preguntas activas se muestran a los usuarios logueados durante el periodo correspondiente.

### Interfaz para Administradores
- **Ruta**: `#admin/preguntas`
- Contenido: Una tabla con las preguntas registradas y los siguientes botones:
  - **New**: Crea una nueva pregunta (`#admin/preguntas/new`).
  - **Show**: Muestra detalles de una pregunta, incluyendo el número de usuarios que la respondieron (`#admin/preguntas/show/{id}`).
  - **Edit**: Edita una pregunta (`#admin/preguntas/edit/{id}`). Nota: Si la pregunta ya ha sido respondida, no se puede editar.
  - **Delete**: Elimina una pregunta (`#admin/preguntas/delete/{id}`).
- Añadir **paginación** en la tabla para facilitar la navegación.

---

## Funcionalidades de los Usuarios
- Los usuarios con ROLE_USER pueden:
  - Ver la pregunta activa durante su periodo de tiempo correspondiente.
  - Responder la pregunta activa seleccionando solo una de las opciones disponibles.
  - Acceder a la ruta para responder preguntas: `#pregunta/contestar/{id}`.
- Los usuarios no tienen acceso a los botones **New**, **Edit** o **Delete**.

---

## Interfaz de Preguntas
- **Diseño**:
  - Una caja a la izquierda que muestra:
    - El `enunciado` de la pregunta.
    - Las opciones de respuesta debajo.
  - Una caja a la derecha que muestra una gráfica de las estadísticas de respuestas en tiempo real. Esto se obtiene mediante una API:
    - **Ruta API**: `#api/pregunta/estadistica/{id}`
    - **Método**: Devuelve un `JsonResponse` con los datos de las estadísticas.

---

## Base de Datos
### Tablas
1. **Usuarios** (tabla generada por `make:user`).
2. **Preguntas**.
3. **Respuestas**:
   - Relación **N:M** con las preguntas:
     - Campos: `id_usuario`, `id_pregunta`, `timestamp` (sello de tiempo), `respuesta` (1, 2, 3 o 4).
   - Entidad recomendada por Symfony:
     - Relación **1:N** entre usuario y respuesta.
     - Relación **1:N** entre pregunta y respuesta.

---

## Extra
- Crear una API pública para consultar:
  - La pregunta activa.
  - Las estadísticas de respuestas en tiempo real.
- Esta API debe ser accesible **sin autenticación**.
