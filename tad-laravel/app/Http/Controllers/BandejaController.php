<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class BandejaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $q = Solicitud::with(['tramite', 'usuario'])->latest();

        if ($search) {
            $q->where('expediente', 'like', "%{$search}%");
        }

        // TODO: Filtrar por asignaciones reales cuando implementes "etapas → responsables"
        $solicitudes = $q->paginate(20)->withQueryString();

        return view('pages.profile.funcionario.bandeja', [
            'active'      => 'bandeja',
            'solicitudes' => $solicitudes,
        ]);
    }

    public function show($id)
    {
        $solicitud = Solicitud::with(['tramite', 'usuario'])->findOrFail($id);

        $documentos = [
            'DNI frontal',
            'DNI dorso',
            'Nota Autorización',
            'Boleto de pago',
            'Comprobante de pago',
        ];

        return view('pages.profile.funcionario.bandeja_show', [
            'active'     => 'bandeja',
            'solicitud'  => $solicitud,
            'documentos' => $documentos,
        ]);
    }
}

