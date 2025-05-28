import express from 'express'
import Tramite from '../models/Tramite.js'

const router = express.Router()

// Guardar trámite
router.post('/', async (req, res) => {
  try {
    const nuevoTramite = new Tramite(req.body)
    const guardado = await nuevoTramite.save()
    res.status(201).json(guardado)
  } catch (err) {
    console.error(err)
    res.status(500).json({ error: 'Error al guardar trámite' })
  }
})

// Obtener trámite por ID
router.get('/:id', async (req, res) => {
  try {
    const tramite = await Tramite.findById(req.params.id)
    res.json(tramite)
  } catch (err) {
    res.status(404).json({ error: 'Trámite no encontrado' })
  }
})

export default router
