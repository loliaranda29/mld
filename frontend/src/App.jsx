// src/App.jsx
import React, { useEffect, useState } from 'react';
import { Routes, Route } from 'react-router-dom';

import Home from './pages/Home';
import Login from './pages/Login';
import ListadoTramites from './pages/admin/tramites/ListadoTramites';
import EditorTramite from './pages/admin/tramites/EditorTramite';
import MisTramites from './pages/ciudadano/MisTramites';
import TramiteDetalle from './pages/ciudadano/TramiteDetalle';
import CiudadanoDashboard from './pages/ciudadano/CiudadanoDashboard';
import MisMensajes from './pages/ciudadano/Mensajes';
import MisTurnos from './pages/ciudadano/Turnos';
import MiWallet from './pages/ciudadano/Wallet';
import PerfilCiudadano from './pages/ciudadano/Perfil';

export default function App() {
  const [deferredPrompt, setDeferredPrompt] = useState(null);
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      setDeferredPrompt(e);
      setVisible(true);
    });
  }, []);

  const handleInstallClick = async () => {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      const result = await deferredPrompt.userChoice;
      if (result.outcome === 'accepted') {
        console.log('App instalada');
      }
      setDeferredPrompt(null);
      setVisible(false);
    }
  };

  return (
    <>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/login" element={<Login />} />
        <Route path="/admin/tramites" element={<ListadoTramites />} />
        <Route path="/admin/tramites/editor" element={<EditorTramite />} />
        <Route path="/admin/tramites/editar/:id" element={<EditorTramite />} />
        <Route path="/mis-tramites" element={<MisTramites />} />
        <Route path="/mis-tramites/:id" element={<TramiteDetalle />} />
        <Route path="/inicio" element={<CiudadanoDashboard />} />
        <Route path="/mensajes" element={<MisMensajes />} />
        <Route path="/turnos" element={<MisTurnos />} />
        <Route path="/wallet" element={<MiWallet />} />
        <Route path="/perfil" element={<PerfilCiudadano />} />
      </Routes>

      {visible && (
        <div className="fixed bottom-4 right-4 z-50">
          <button
            onClick={handleInstallClick}
            className="px-4 py-2 bg-[#248B89] text-white rounded-md shadow-md hover:bg-[#1c6d6b]"
          >
            Instalar App
          </button>
        </div>
      )}
    </>
  );
}
