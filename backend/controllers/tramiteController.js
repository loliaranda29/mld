// backend/controllers/tramiteController.js
// Controlador de trámites - Adaptado a SQL Server con mssql

import sql from 'mssql';
import { sqlConfig } from '../db/config.js';

/**
 * Obtener trámites asociados a un ciudadano por ID simulado
 * (En el futuro se reemplazará con el ID del JWT)
 */
export const obtenerTramites = async (req, res) => {
  try {
    // Simulación de ID de ciudadano
    const ciudadanoId = '123456';

    // Conexión a SQL Server
    let pool = await sql.connect(sqlConfig);

    // Consulta SQL
    const result = await pool.request()
      .input('ciudadanoId', sql.VarChar, ciudadanoId)
      .query(`
        SELECT t.id, t.nombre, t.descripcion
        FROM Tramites t
        INNER JOIN CiudadanoTramites ct ON ct.tramiteId = t.id
        WHERE ct.ciudadanoId = @ciudadanoId
      `);

    res.json(result.recordset);
  } catch (error) {
    console.error('Error al obtener trámites:', error);
    res.status(500).json({ message: 'Error al obtener los trámites del ciudadano' });
  }
};
