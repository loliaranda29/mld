import mongoose from 'mongoose'

const CampoSchema = new mongoose.Schema({
  id: String,
  tipo: String,
  etiqueta: String,
  obligatorio: Boolean,
  pista: String,
  opciones: [String],
  condiciones: [
    {
      si: String,
      mostrar: [String]
    }
  ]
})

const SeccionSchema = new mongoose.Schema({
  id: Number,
  titulo: String,
  campos: [CampoSchema]
})

const ApiConfigSchema = new mongoose.Schema({
  url: String,
  method: { type: String, enum: ['GET', 'POST', 'PUT', 'DELETE'], default: 'POST' },
  headers: { type: Map, of: String },
  bodyMapping: { type: Map, of: String }
})

const TramiteSchema = new mongoose.Schema({
  nombre: String,
  formulario: [SeccionSchema],
  api: ApiConfigSchema
})

export default mongoose.model('Tramite', TramiteSchema)
