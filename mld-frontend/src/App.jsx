import { Routes, Route } from 'react-router-dom'
import Login from './pages/Login'
import Dashboard from './pages/Dashboard'
import RespuestaPrevencion from './pages/RespuestaPrevencion'
import ListadoTramites from './pages/admin/tramites/ListadoTramites'

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
    </Routes>
  )
}

export default App
