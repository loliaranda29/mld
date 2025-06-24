import React from 'react';
import { Link } from 'react-router-dom';
import PerfilSwitcher from '../PerfilSwitcher';

function Header() {
  const usuario = JSON.parse(localStorage.getItem('usuario'));

  return (
    <header className="flex justify-between items-center px-6 py-4 bg-white shadow w-full">
        <PerfilSwitcher />  
    </header>
  );
}

export default Header;
