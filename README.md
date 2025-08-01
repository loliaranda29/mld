# Mi Luján Digital - Plataforma Mejorada

Este repositorio contiene el código fuente de la nueva versión de la plataforma **Mi Luján Digital**, incluyendo frontend y backend.

## 📁 Estructura del proyecto

```
.
├── frontend/       # Aplicación web (React + Vite + Tailwind)
├── backend/        # API backend (Node.js + Express + SQL Server)
├── .gitignore
└── README.md
```

## 🚀 Cómo iniciar el proyecto

### Frontend
```bash
cd frontend
npm install
npm run dev
```

### Backend
```bash
cd backend
npm install
npm run dev
```

## 🧪 Variables de entorno

Creá archivos `.env` tanto en `frontend/` como en `backend/` con los valores necesarios. No los subas al repo.

## 📌 Rama principal
- `main`: versión estable
- `dev`: integración de funcionalidades
- `feature/*`: desarrollo de módulos específicos

---

## Objetivo General
Desarrollar una nueva versión moderna, responsiva, modular y escalable de la plataforma Mi Luján Digital, con tecnologías actuales y mejoras significativas en la experiencia de usuario, funcionalidades, integraciones y eficiencia administrativa.

## Stack Tecnológico
- **Frontend**: React + TailwindCSS
- **Backend**: Node.js + Express
- **Base de datos**: SQL Server
- **Integraciones**: APIs externas (RENAPER, Mi Argentina, ARCA, sistema municipal, WhatsApp, etc.), Blockchain (QuarkID), firma digital, certificados verificables.

## Funcionalidades Clave
- Login con identidad verificada (RENAPER, ARCA, Mi Argentina).
- Trámites por pasos con formularios condicionales y configurables.
- Secciones con lógica condicional y pistas enriquecidas.
- Etapas de trámites con responsables múltiples y posibilidad de volver a cualquier etapa.
- Selección personalizada de etapa a la que se quiere volver.
- Configuración avanzada de etapas: pagos, responsables, condiciones, documentos previos.
- Sistema de notificaciones por mail y WhatsApp.
- Bandejas de entrada por rol (ciudadano, funcionario, administrador).
- Dashboard ciudadano con acceso a sus trámites, turnos, mensajes, wallet, perfil.
- Perfil editable y verificable del ciudadano, con asociación de padrón y servicios.
- Apoderados y perfiles especiales (empresa, profesional).
- Permisos automáticos y certificados verificables.
- Trámites internos para áreas municipales.
- Subtrámites y trámites globales (ej: loteos con subtrámites).
- Checklist de funcionario por etapa.
- Vista administrativa para edición de trámites, filtros por área, catalogación.
- Parametrización visual y simplificada de trámites.
- Reapertura de trámites cerrados o rechazados.
- Solicitud de reversión de etapa en caso de error.
- Plataforma PWA instalable y responsive.

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
