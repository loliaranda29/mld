// backend/routes/tramite.js
// Rutas para la gestión de trámites

import express from 'express';
import { obtenerTramites } from '../controllers/tramite.controller.js';

const router = express.Router();

/**
 * Ruta GET /api/tramites
 * Devuelve los trámites asociados al ciudadano autenticado
 * (Por ahora se simula el ID hasta implementar JWT)
 */
router.get('/', obtenerTramites);

export default router;
