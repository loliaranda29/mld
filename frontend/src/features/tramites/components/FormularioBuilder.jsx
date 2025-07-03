import { useState } from 'react';
import { PlusCircle } from 'lucide-react';

const tiposCampo = [
  'texto',
  'número',
  'fecha',
  'hora',
  'archivo',
  'select',
  'código',
  'api'
];

function FormularioBuilder({ secciones = [], setSecciones }) {
  const agregarSeccion = () => {
    setSecciones([
      ...secciones,
      {
        id: Date.now(),
        titulo: '',
        campos: []
      }
    ]);
  };

  const eliminarSeccion = (id) => {
    setSecciones(secciones.filter((s) => s.id !== id));
  };

  const agregarCampo = (seccionId) => {
    const actualizado = secciones.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: [
              ...s.campos,
              {
                id: Date.now(),
                tipo: 'texto',
                etiqueta: '',
                obligatorio: false,
                pista: ''
              }
            ]
          }
        : s
    );
    setSecciones(actualizado);
  };

  const eliminarCampo = (seccionId, campoId) => {
    const actualizado = secciones.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: s.campos.filter((c) => c.id !== campoId)
          }
        : s
    );
    setSecciones(actualizado);
  };

  const actualizarCampo = (seccionId, campoId, campoActualizado) => {
    const actualizado = secciones.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: s.campos.map((c) =>
              c.id === campoId ? { ...c, ...campoActualizado } : c
            )
          }
        : s
    );
    setSecciones(actualizado);
  };

  return (
    <div className="space-y-6">
      {secciones.map((seccion) => (
        <div key={seccion.id} className="border rounded-md p-4 bg-white shadow-sm">
          <div className="flex justify-between mb-2">
            <input
              type="text"
              className="w-full text-lg font-semibold px-3 py-2 border rounded"
              placeholder="Título de la sección"
              value={seccion.titulo}
              onChange={(e) =>
                setSecciones(secciones.map((s) =>
                  s.id === seccion.id ? { ...s, titulo: e.target.value } : s
                ))
              }
            />
            <button
              onClick={() => eliminarSeccion(seccion.id)}
              className="text-red-600 ml-3 text-sm"
            >
              Eliminar sección
            </button>
          </div>

            {seccion.campos.map((campo) => (
              <div key={campo.id} className="border p-3 mb-4 rounded bg-gray-50">
                <div className="flex justify-between items-center mb-1">
                  <strong>📌 Campo</strong>
                  <button
                    onClick={() => eliminarCampo(seccion.id, campo.id)}
                    className="text-sm text-red-600"
                  >
                    Eliminar
                  </button>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                  <div>
                    <label className="text-sm font-medium">Etiqueta</label>
                    <input
                      type="text"
                      className="w-full mt-1 px-3 py-1 border rounded text-sm"
                      value={campo.etiqueta}
                      onChange={(e) =>
                        actualizarCampo(seccion.id, campo.id, {
                          etiqueta: e.target.value
                        })
                      }
                    />
                  </div>

                  <div>
                    <label className="text-sm font-medium">Tipo de campo</label>
                    <select
                      className="w-full mt-1 px-3 py-1 border rounded text-sm"
                      value={campo.tipo}
                      onChange={(e) =>
                        actualizarCampo(seccion.id, campo.id, {
                          tipo: e.target.value
                        })
                      }
                    >
                      {tiposCampo.map((tipo) => (
                        <option key={tipo} value={tipo}>
                          {tipo.charAt(0).toUpperCase() + tipo.slice(1)}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                <div className="flex items-center gap-2 mb-2">
                  <input
                    type="checkbox"
                    checked={campo.obligatorio}
                    onChange={(e) =>
                      actualizarCampo(seccion.id, campo.id, {
                        obligatorio: e.target.checked
                      })
                    }
                  />
                  <label className="text-sm">Obligatorio</label>
                </div>

                <div>
                  <label className="text-sm font-medium">Pista (opcional)</label>
                  <input
                    type="text"
                    className="w-full mt-1 px-3 py-1 border rounded text-sm"
                    value={campo.pista || ''}
                    onChange={(e) =>
                      actualizarCampo(seccion.id, campo.id, {
                        pista: e.target.value
                      })
                    }
                  />
                </div>

                {campo.tipo === 'select' && (
                  <div className="mt-2">
                    <label className="text-sm font-medium">Opciones</label>
                    <input
                      type="text"
                      className="w-full mt-1 px-3 py-1 border rounded text-sm"
                      value={campo.opciones?.join(',') || ''}
                      onChange={(e) =>
                        actualizarCampo(seccion.id, campo.id, {
                          opciones: e.target.value.split(',')
                        })
                      }
                    />
                  </div>
                )}

                {(campo.tipo === 'código' || campo.tipo === 'api') && (
                  <div className="mt-2">
                    <label className="text-sm font-medium">
                      {campo.tipo === 'api' ? 'URL/API' : 'Código JS'}
                    </label>
                    <textarea
                      className="w-full mt-1 px-3 py-2 border rounded text-sm font-mono"
                      rows={3}
                      value={campo.config || ''}
                      onChange={(e) =>
                        actualizarCampo(seccion.id, campo.id, {
                          config: e.target.value
                        })
                      }
                    />
                  </div>
                )}

                {/* Vista previa */}
                <div className="mt-4">
                  <label className="text-sm font-medium text-gray-700 block mb-1">
                    Vista previa
                  </label>
                  {renderPreview(campo)}
                </div>
              </div>
            ))}


          <button
            onClick={() => agregarCampo(seccion.id)}
            className="text-[#248B89] text-sm font-medium hover:underline mt-2"
          >
            + Agregar campo
          </button>
        </div>
      ))}

      <button
        onClick={agregarSeccion}
        className="bg-[#248B89] text-white px-5 py-2 rounded-md font-semibold hover:bg-[#1f706e]"
      >
        <PlusCircle size={18} className="inline mr-1" />
        Agregar Sección
      </button>
    </div>
  );
}

export default FormularioBuilder;
