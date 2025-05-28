import express from 'express'
import mongoose from 'mongoose'
import cors from 'cors'
import tramitesRouter from './routes/tramites.js'

const app = express()
app.use(cors())
app.use(express.json())

mongoose.connect('mongodb://localhost:27017/mld', {
  useNewUrlParser: true,
  useUnifiedTopology: true
})

app.use('/api/tramites', tramitesRouter)

const PORT = 4000
app.listen(PORT, () => console.log(`Servidor corriendo en puerto ${PORT}`))

