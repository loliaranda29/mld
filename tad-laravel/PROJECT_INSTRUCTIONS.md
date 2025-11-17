# Mi Luján Digital – Instrucciones para la IA
- Stack: Laravel + Blade + Eloquent + Bootstrap + Alpine.js.
- Mantener diseño visual actual (igual a plataforma original).
- Estructura modular: rutas, controladores, modelos y vistas por módulo.
- Perfiles: Ciudadano, Funcionario, Súper Admin.
- Editor de Trámites: tabs [General][Formulario][Etapas][Documento][Configuración].
- Form Builder: Blade + Alpine.js, persistencia JSON.
- Layout de funcionario: `layouts.app-funcionario`.
- No cambiar rutas ni nombres ya definidos sin avisar; proponer diffs mínimos.

## Reglas Anti-Alucinación (OBLIGATORIAS)
- NO inventes rutas, clases, modelos, migraciones, vistas ni configuraciones que no existan en el repo.
- Si no encontrás evidencia en archivos del repo, respondé: “No tengo evidencia en el código del proyecto para afirmar esto.”
- Citá SIEMPRE archivos y, si es posible, líneas: `app/Http/Controllers/SolicitudesController.php:L45-L72`.
- Cuando falte contexto, proponé exactamente QUÉ archivo(s) abrir: “Necesito ver `database/migrations/2024_..._create_solicitudes_table.php` y `resources/views/solicitud_nueva.blade.php`.”
- Preferí *diffs mínimos* y compatibles: no cambies nombres de rutas, controladores, modelos ni layouts ya usados.
- Si la tarea implica suposiciones, separá una sección **“Suposiciones (revisar)”** con bullets claros.
- Evitá respuestas genéricas. Todo debe estar anclado a archivos del repo o indicar explícitamente que falta evidencia.
- Formato de salida por defecto:
  1) **Causa/Diagnóstico** (con referencias a archivos/líneas)  
  2) **Diff mínimo propuesto** (solo lo necesario)  
  3) **Impacto** (qué puede romper o qué tests correr)  
  4) **Siguientes pasos** (archivos que revisar si aún falla)
