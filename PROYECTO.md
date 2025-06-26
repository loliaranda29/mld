
# Mi Luján Digital - Proyecto de Modernización de Trámites

## Objetivo General
Desarrollar una nueva versión moderna, responsiva, modular y escalable de la plataforma Mi Luján Digital, con tecnologías actuales y mejoras significativas en la experiencia de usuario, funcionalidades, integraciones y eficiencia administrativa.

## Stack Tecnológico
- **Frontend**: React + TailwindCSS
- **Backend**: Node.js + Express
- **Base de datos**: SQL Server
- **Integraciones**: APIs externas (RENAPER, Mi Argentina, ARCA, sistema municipal), Blockchain (QuarkID), firma digital, certificados verificables.

## Funcionalidades Clave
- Login con identidad verificada (RENAPER, ARCA, Mi Argentina).
- Trámites por pasos con formularios condicionales y configurables.
- Secciones con lógica condicional y pistas enriquecidas.
- Etapas de trámites con responsables múltiples y posibilidad de volver a cualquier etapa específica.
- Configuración avanzada de etapas: pagos (fijos o variables), responsables, condiciones, documentos previos.
- Bandejas de entrada por rol (ciudadano, funcionario, administrador), con filtros por etapa, padrón, titular y orden de actualización.
- Dashboard ciudadano con acceso a sus trámites, turnos, mensajes, wallet, perfil.
- Perfil editable y verificable del ciudadano, con asociación automática a padrón y servicios municipales.
- Apoderados y perfiles especiales (empresa, profesional), con validación municipal.
- Permisos automáticos previos a etapas o certificados finales.
- Trámites internos para áreas municipales.
- Subtrámites y agrupación en trámites globales (ej: loteos).
- Checklist de funcionario por etapa.
- Vista administrativa para edición de trámites, filtros por área, catalogación.
- Parametrización visual y simplificada de trámites.
- Reapertura o reversión de trámites cerrados o rechazados.
- Plataforma PWA instalable y responsive (notificaciones desde app).
- Recordatorios automatizados al ciudadano (vencimiento de impuestos, permisos, etc.).
- Módulo de capacitaciones con exámenes configurables y certificados emitibles.

## Identidad y UX
- Nombre del sistema de identidad digital: **ILUDI** (Identidad Lujanina Digital)
- Asistente virtual: **LUJI**
- Interfaz moderna, clara, ciudadana.
- Multidispositivo.

## Estado del Proyecto
- Autenticación simulada y dashboard ciudadano implementado.
- Editor de trámites y etapas con múltiples responsables funcional.
- Estructura de carpetas organizada.
- Service Worker y manifest.json configurado (PWA funcional en proceso).
- Proyecto en repositorio GitHub: [github.com/loliaranda29/mld](https://github.com/loliaranda29/mld)

## Pendientes Prioritarios
- Conexión real con SQL Server.
- Backend funcional con endpoints REST.
- Seguridad: JWT y middleware de protección de rutas.
- Implementar roles y permisos.
- Configurador de formularios visual.
- Validaciones y pruebas.
- App móvil (PWA completa o React Native en segunda etapa).
- Subida al servidor municipal.

## Documentos relacionados
- `Seguimiento.md`: progreso del día a día
- `CONTRIBUTING.md`: reglas de colaboración para el equipo

Este proyecto busca transformar la relación entre el ciudadano y el municipio, facilitando, agilizando y haciendo más transparentes los trámites municipales. Cada función fue pensada con foco en la experiencia ciudadana y la eficiencia del Estado.
