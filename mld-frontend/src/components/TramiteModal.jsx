function TramiteModal({ tramite, onClose }) {
  if (!tramite) return null

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div className="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h2 className="text-xl font-bold text-teal-700 mb-4">{tramite.nombre}</h2>
        <p className="text-sm text-gray-600 mb-2">
          <strong>Estado:</strong> {tramite.estado}
        </p>
        <p className="text-sm text-gray-600 mb-2">
          <strong>CUIL:</strong> {tramite.cuil}
        </p>
        <p className="text-sm text-gray-600 mb-4">
          <strong>Fecha:</strong> {tramite.fecha}
        </p>

        <div className="bg-gray-50 p-3 rounded text-sm text-gray-700">
          <strong>Observaciones:</strong>
          <p className="mt-1">Este trámite fue ingresado correctamente. Falta validación documental.</p>
        </div>

        <button
          onClick={onClose}
          className="absolute top-2 right-2 text-gray-400 hover:text-red-600 text-xl"
        >
          &times;
        </button>
      </div>
    </div>
  )
}

export default TramiteModal
