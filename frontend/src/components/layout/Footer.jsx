import React from 'react';

const Footer = () => {
  return (
    <footer className="bg-white border-t p-4 text-center text-sm text-gray-600">
      © {new Date().getFullYear()} Municipalidad de Luján de Cuyo. Todos los derechos reservados.
    </footer>
  );
};

export default Footer;
