import express from "express";
import Tramite from "../models/Tramite.js";

const router = express.Router();

// Obtener todos los trámites
router.get("/", async (req, res) => {
  try {
    const tramites = await Tramite.find();
    res.json(tramites);
  } catch (error) {
    res.status(500).json({ message: "Error al obtener trámites" });
  }
});

export default router;

