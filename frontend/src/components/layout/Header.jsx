import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import PerfilSwitcher from '../PerfilSwitcher';

export default function Header() {
  const [deferredPrompt, setDeferredPrompt] = useState(null);
  const [mostrarInstalacion, setMostrarInstalacion] = useState(false);

  useEffect(() => {
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      setDeferredPrompt(e);
      setMostrarInstalacion(true);
    });
  }, []);

  const handleInstalacion = async () => {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      const resultado = await deferredPrompt.userChoice;
      if (resultado.outcome === 'accepted') {
        console.log('Usuario aceptó la instalación');
      } else {
        console.log('Usuario rechazó la instalación');
      }
      setDeferredPrompt(null);
      setMostrarInstalacion(false);
    }
  };

  const usuario = JSON.parse(localStorage.getItem('usuario'));

  return (
    <header className="flex justify-between items-center px-6 py-4 bg-white shadow w-full">
  
      <div className="flex items-center gap-4">
        {mostrarInstalacion && (
          <button
            onClick={handleInstalacion}
            className="px-3 py-1 bg-[#248B89] text-white text-sm rounded hover:bg-[#1f706e]"
          >
            Instalar aplicación
          </button>
        )}
        <PerfilSwitcher />
      </div>
    </header>
  );
}
