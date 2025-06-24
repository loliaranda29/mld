import React from 'react';
import { Link } from 'react-router-dom';
import PerfilSwitcher from '../PerfilSwitcher';

function Header() {
  const usuario = JSON.parse(localStorage.getItem('usuario'));

  return (
    <header className="flex justify-between items-center px-6 py-4 bg-white shadow w-full">
      <h1 className="text-xl font-semibold text-gray-700">Panel de gestión</h1>
      <div className="flex items-center gap-4">
        {usuario?.rol === 'ciudadano' && (
          <Link
            to="/inicio"
            className="px-3 py-1 bg-[#248B89] text-white rounded-md hover:bg-[#1f706e]"
          >
            Ir al Panel
          </Link>
        )}
        <PerfilSwitcher />
      </div>
    </header>
  );
}

export default Header;
