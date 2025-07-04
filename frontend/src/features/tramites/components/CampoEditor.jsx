import React from 'react';

export default function CampoEditor({ campos, onChange }) {
  const actualizarCampo = (index, nuevoCampo) => {
    const actualizados = [...campos];
    actualizados[index] = nuevoCampo;
    onChange(actualizados);
  };

  const eliminarCampo = (index) => {
    onChange(campos.filter((_, i) => i !== index));
  };

  const agregarCampo = (tipo) => {
    onChange([
      ...campos,
      {
        id: Date.now(),
        tipo,
        etiqueta: '',
        obligatorio: false,
        opciones: [],
        archivoTipos: [],
        archivoMaximo: 150,
        archivoCantidad: 1,
        pistaTexto: '',
        pistaLink: '',
        pistaVideo: '',
        pistaImagen: '',
        condicion: '',
        codigo: '',
        api: '',
        logicaSiguiente: '',
      },
    ]);
  };

  return (
    <div className="space-y-4">
      {campos.map((campo, index) => (
        <div key={campo.id} className="border p-3 rounded bg-gray-50 space-y-2">
          <input
            value={campo.etiqueta}
            onChange={(e) => actualizarCampo(index, { ...campo, etiqueta: e.target.value })}
            placeholder="Etiqueta del campo"
            className="w-full border px-3 py-1 rounded"
          />

          <select
            value={campo.tipo}
            onChange={(e) => actualizarCampo(index, { ...campo, tipo: e.target.value })}
            className="w-full border px-3 py-1 rounded"
          >
            <option value="texto">Texto</option>
            <option value="numero">Número</option>
            <option value="select">Select</option>
            <option value="archivo">Archivo</option>
            <option value="codigo">Código</option>
            <option value="api">API</option>
          </select>

          {campo.tipo === 'select' && (
            <input
              type="text"
              value={campo.opciones.join(',')}
              onChange={(e) =>
                actualizarCampo(index, { ...campo, opciones: e.target.value.split(',') })
              }
              placeholder="Opciones separadas por coma"
              className="w-full border px-3 py-1 rounded"
            />
          )}

          {campo.tipo === 'archivo' && (
            <div className="space-y-2">
              <input
                placeholder="Tipos permitidos (jpg,pdf,mp4,...)"
                value={campo.archivoTipos.join(',')}
                onChange={(e) =>
                  actualizarCampo(index, { ...campo, archivoTipos: e.target.value.split(',') })
                }
                className="w-full border px-3 py-1 rounded"
              />
              <input
                type="number"
                placeholder="Cantidad máxima de archivos"
                value={campo.archivoCantidad}
                onChange={(e) =>
                  actualizarCampo(index, { ...campo, archivoCantidad: parseInt(e.target.value) })
                }
                className="w-full border px-3 py-1 rounded"
              />
              <input
                type="number"
                placeholder="Tamaño máximo por archivo (MB)"
                value={campo.archivoMaximo}
                onChange={(e) =>
                  actualizarCampo(index, { ...campo, archivoMaximo: parseInt(e.target.value) })
                }
                className="w-full border px-3 py-1 rounded"
              />
            </div>
          )}

          {campo.tipo === 'codigo' && (
            <textarea
              rows={3}
              placeholder="Código personalizado"
              value={campo.codigo}
              onChange={(e) => actualizarCampo(index, { ...campo, codigo: e.target.value })}
              className="w-full border px-3 py-1 rounded font-mono"
            />
          )}

          {campo.tipo === 'api' && (
            <input
              placeholder="URL del API"
              value={campo.api}
              onChange={(e) => actualizarCampo(index, { ...campo, api: e.target.value })}
              className="w-full border px-3 py-1 rounded"
            />
          )}

          <input
            type="text"
            placeholder="Condición lógica (ej: campoX === 'sí')"
            value={campo.condicion}
            onChange={(e) => actualizarCampo(index, { ...campo, condicion: e.target.value })}
            className="w-full border px-3 py-1 rounded"
          />

          <input
            type="text"
            placeholder="Texto de ayuda"
            value={campo.pistaTexto}
            onChange={(e) => actualizarCampo(index, { ...campo, pistaTexto: e.target.value })}
            className="w-full border px-3 py-1 rounded"
          />

          <input
            type="text"
            placeholder="Enlace de ayuda"
            value={campo.pistaLink}
            onChange={(e) => actualizarCampo(index, { ...campo, pistaLink: e.target.value })}
            className="w-full border px-3 py-1 rounded"
          />

          <input
            type="text"
            placeholder="Video de ayuda"
            value={campo.pistaVideo}
            onChange={(e) => actualizarCampo(index, { ...campo, pistaVideo: e.target.value })}
            className="w-full border px-3 py-1 rounded"
          />

          <input
            type="text"
            placeholder="Imagen de ayuda"
            value={campo.pistaImagen}
            onChange={(e) => actualizarCampo(index, { ...campo, pistaImagen: e.target.value })}
            className="w-full border px-3 py-1 rounded"
          />

          <button
            onClick={() => eliminarCampo(index)}
            className="text-red-600 text-sm"
          >
            Eliminar
          </button>
        </div>
      ))}

      <div className="flex gap-2">
        {['texto', 'numero', 'select', 'archivo', 'codigo', 'api'].map((tipo) => (
          <button
            key={tipo}
            onClick={() => agregarCampo(tipo)}
            className="bg-gray-200 px-3 py-1 rounded text-sm"
          >
            + {tipo}
          </button>
        ))}
      </div>
    </div>
  );
}
