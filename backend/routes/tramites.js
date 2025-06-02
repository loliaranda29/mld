import express from 'express'
import Tramite from '../models/Tramite.js'
import axios from 'axios'

const router = express.Router()

// POST /tramites/:id/ejecutar
router.post('/:id/ejecutar', async (req, res) => {
  try {
    const tramite = await Tramite.findById(req.params.id)
    if (!tramite) return res.status(404).json({ error: 'Trámite no encontrado' })

    const { api } = tramite
    const { bodyMapping = {}, headers = {}, method = 'POST', url } = api

    if (!url) return res.status(400).json({ error: 'No se configuró URL de API externa' })

    // Armar body con datos del form
    const body = {}
    for (const [key, campoId] of Object.entries(bodyMapping)) {
      body[key] = req.body[campoId]
    }

    const config = {
      method,
      url,
      headers,
      data: body
    }

    const respuesta = await axios(config)
    res.json({ resultado: respuesta.data })
  } catch (err) {
    console.error(err)
    res.status(500).json({ error: 'Error al ejecutar la API externa' })
  }
})


export default router
