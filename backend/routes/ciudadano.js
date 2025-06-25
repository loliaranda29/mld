import express from "express";
import Tramite from "../models/Tramite.js";

const router = express.Router();

// Simulación de usuario autenticado (luego reemplazaremos por JWT)
const CIUDADANO_ID = "123456";

router.get("/tramites", async (req, res) => {
  try {
    const tramites = await Tramite.find({ ciudadanoId: CIUDADANO_ID });
    res.json(tramites);
  } catch (error) {
    res.status(500).json({ message: "Error al obtener los trámites del ciudadano" });
  }
});

export default router;
