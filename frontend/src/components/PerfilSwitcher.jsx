import React from 'react';

import { User, Briefcase } from 'lucide-react'
import { useState } from 'react'

function PerfilSwitcher() {
  const [perfil, setPerfil] = useState('funcionario')

  const togglePerfil = () => {
    setPerfil(perfil === 'funcionario' ? 'ciudadano' : 'funcionario')
  }

  return (
    <div
      onClick={togglePerfil}
      className="flex items-center bg-gray-100 rounded-full px-3 py-1 cursor-pointer hover:bg-gray-200"
    >
      {perfil === 'funcionario' ? (
        <>
          <Briefcase className="w-5 h-5 mr-2 text-teal-700" />
          <span className="text-sm text-teal-700">Funcionario</span>
        </>
      ) : (
        <>
          <User className="w-5 h-5 mr-2 text-blue-600" />
          <span className="text-sm text-blue-600">Ciudadano</span>
        </>
      )}
    </div>
  )
}

export default PerfilSwitcher
