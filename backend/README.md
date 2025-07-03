Este README documenta la estructura y propósito del backend del proyecto MLD.
📁 Estructura de carpetas
•	config/
Configuraciones globales del sistema.
•	controllers/
Contiene la lógica de negocio para cada entidad (ciudadano, trámite, etc).
•	middlewares/
Funciones intermedias como autenticación y manejo de errores.
•	models/
Definición de esquemas y modelos para SQL Server.
•	routes/
Definición de los endpoints y rutas REST.
•	services/
Integración con servicios externos como RENAPER, generación de expediente, mailer.
•	utils/
Funciones utilitarias compartidas.
•	tests/
Pruebas unitarias e integradas.
•	app.js
Inicializa la aplicación Express y define los middlewares.
•	server.js
Punto de entrada para levantar el servidor.
•	.env
Variables de entorno sensibles.
•	package.json
Dependencias y scripts del proyecto.
🔐 Seguridad
La autenticación se realiza por token (JWT) y se valida con middleware `auth.js`.
Se contempla el uso de roles y permisos para accesos diferenciados.
🧪 Pruebas
Las pruebas se ubican en `tests/`. Incluyen casos de validación para controladores y servicios.
