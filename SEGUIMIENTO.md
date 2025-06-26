## 📝 Reporte de Avance del Proyecto – Mi Luján Digital

### 📅 Fecha: Junio 2025

---

### ✅ Avances realizados

#### 🔧 Infraestructura y Setup

* Configuración inicial del proyecto frontend con Vite, React y TailwindCSS.
* Configuración del backend con Node.js y Express.
* Configuración básica de rutas protegidas y lógica de sesión simulada.
* Configuración del manifiesto, service worker y pruebas de instalación PWA.

#### 🧑‍💻 Módulos implementados

* Login simulado con guardado de usuario en localStorage.
* Panel Ciudadano con accesos directos: trámites, mensajes, wallet, turnos y perfil.
* Vista de Mis Trámites y detalle del trámite.
* Formulario para nuevo trámite con pasos, campos y lógica condicional.
* Editor de etapas con múltiples responsables, posibilidad de retroceder etapas y descripción editable.
* Vista de perfil ciudadano con información editable.
* Agregado de rutas para Mensajes, Turnos, Wallet y Perfil.
* Protección básica de rutas y simulación de rol.

#### 🖼️ Diseño y UX

* Creación de identidad visual "ILUDI" (Identidad Lujanina Digital).
* Implementación de cards para accesos rápidos.
* Diseño responsive para todas las vistas.
* Bocetos y pantallas diseñadas para trámites, bandejas y parametrización visual.

#### 🛠️ Backend

* Definición del modelo `Tramite` en MongoDB (inicialmente).
* Migración planificada a SQL Server como base de datos principal.
* API básica para guardar y recuperar trámites.

---

### 📌 Ideas y mejoras registradas

* Creación de trámites internos para uso administrativo municipal.
* Posibilidad de que un funcionario actúe como apoderado.
* Identidad Digital con blockchain (QuarkID) y certificados autoverificables.
* Configuración avanzada de etapas: posibilidad de seleccionar etapa específica a la que volver.
* Etapas con lógica de formularios dinámicos por secciones (editable por área).
* Trámite compuesto por sub-trámites (trámites globales).
* Etapa de pago configurable con monto fijo o variable, integración con tasas municipales.
* Checklist interno para revisión de trámites por parte del funcionario.
* Reapertura de trámites finalizados o con errores.
* Notificaciones también vía WhatsApp y aplicación instalable con alertas.
* Vista tipo bandeja de entrada para revisión de trámites con filtros por padrón, titular, estado y paginación.
* Diseño unificado y parametrizable por tipo de trámite y oficina.
* Vinculación automática de datos del ciudadano desde sistema municipal (al registrarse).
* Carga de foto de perfil, cambio de contraseña y mail.
* Posibilidad de solicitar perfil de empresa o profesional.
* App PWA instalable, en proceso de ajustes para detección automática.

---

### ⏩ Próximos pasos

1. Finalizar el sistema de configuración y parametrización visual del trámite.
2. Integración real con backend SQL Server y autenticación con JWT.
3. Implementar lógica de edición para trámites y lógica por tipo de trámite.
4. Avanzar con el sistema de notificaciones (in-app y externas).
5. Implementar sistema de apoderados y perfiles especiales.
6. Completar integración con sistemas municipales para autofill de datos.
7. Avanzar en funcionalidad de wallet digital y certificados verificables.
8. Finalizar instalación de la app como PWA (backend + manifest + eventos frontend).
9. Ajustar accesos y visibilidad según roles (ciudadano, funcionario, admin, etc).
10. Organizar el roadmap y tableros para trabajo colaborativo con el equipo municipal.
