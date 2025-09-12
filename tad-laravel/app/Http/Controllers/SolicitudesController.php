<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SolicitudesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // ciudadano logueado
    }

    /**
     * Listado de solicitudes del ciudadano autenticado.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $q = Solicitud::with('tramite')
            ->where('usuario_id', $user->id)
            ->latest();

        if ($search = $request->input('search')) {
            $q->where('expediente', 'like', "%{$search}%");
        }

        $solicitudes = $q->paginate(12)->withQueryString();

        return view('pages.profile.ciudadano.solicitudes', [
            'active' => 'tramites',
            'solicitudes' => $solicitudes,
        ]);
    }

    /**
     * Crea una solicitud nueva para un trámite (plantilla).
     * POST /profile/solicitudes/{tramite}
     */
    public function store(Request $request, Tramite $tramite)
    {
        $user = auth()->user();

        // Chequeo básico: que el trámite permita solicitudes (respeta tus flags)
        if (!$tramite->acepta_solicitudes || !$tramite->disponible) {
            return back()->with('error', 'Este trámite no acepta solicitudes actualmente.');
        }

        // Generar un folio/expediente simple y estable (podemos reemplazar por tu secuenciador)
        $expediente = $this->generarExpediente($tramite);

        $solicitud = null;
        DB::transaction(function () use ($tramite, $user, $expediente, &$solicitud) {
            $solicitud = Solicitud::create([
                'tramite_id' => $tramite->id,
                'usuario_id' => $user->id,
                'expediente' => $expediente,
                'estado'     => 'iniciado',
                'datos'      => $tramite->formulario_json ?? null, // snapshot inicial del form (opcional)
            ]);
        });

        return redirect()
            ->route('profile.solicitudes.show', $solicitud->id)
            ->with('success', 'Tu solicitud fue creada correctamente.');
    }

    /**
     * Detalle de una solicitud del ciudadano (valida pertenencia).
     */
    public function show($id)
    {
        $user = auth()->user();
        $solicitud = Solicitud::with('tramite')->where('usuario_id', $user->id)->findOrFail($id);

        return view('pages.profile.ciudadano.details.solicitud', [
            'active'    => 'tramites',
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Genera un código de expediente simple: TRAM-{id}-{Ymd}-{seq}
     * (Si luego querés, lo reemplazamos por tu lógica/tabla de folios).
     */
    protected function generarExpediente(Tramite $tramite): string
    {
        $prefix = 'TRAM-' . $tramite->id . '-' . now()->format('Ymd');
        $countHoy = Solicitud::where('tramite_id', $tramite->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $seq = str_pad((string)($countHoy + 1), 3, '0', STR_PAD_LEFT);
        return $prefix . '-' . $seq;
    }
}
