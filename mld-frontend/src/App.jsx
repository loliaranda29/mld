import { Routes, Route } from 'react-router-dom'
import Login from './pages/Login'
import Dashboard from './pages/Dashboard'
import RespuestaPrevencion from './pages/RespuestaPrevencion'
import ListadoTramites from './pages/admin/tramites/ListadoTramites'
import FormularioBuilder from './pages/admin/FormularioBuilder'

function App() {
  return (
    <Routes>
      <Route path="/" element={<Login />} />
      <Route path="/dashboard" element={<Dashboard />} />
      <Route path="/respuesta-prevencion" element={<RespuestaPrevencion tramite={{
        id: 5,
        nombre: 'Cambio de titularidad',
        estado: 'En prevención',
        observaciones: 'Falta documentación del titular anterior'
      }} />} />
      
      <Route path="/admin/tramites" element={<ListadoTramites />} />
      <Route path="/admin/formulario-builder" element={<FormularioBuilder />} />
    </Routes>
  )
}

export default App
