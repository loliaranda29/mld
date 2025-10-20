<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class BandejaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $q = Solicitud::with(['tramite', 'usuario'])->latest();

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('expediente', 'like', "%{$search}%")
                   ->orWhereHas('tramite', function ($tq) use ($search) {
                       $tq->where('nombre', 'like', "%{$search}%");
                   })
                   ->orWhereHas('usuario', function ($uq) use ($search) {
                       $uq->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                   });
            });
        }

        $solicitudes = $q->paginate(10);

        return view('pages.profile.funcionario.bandeja', [
            'active'      => 'bandeja',
            'solicitudes' => $solicitudes,
        ]);
    }

    public function show($id)
    {
        $solicitud = Solicitud::with(['tramite', 'usuario'])->findOrFail($id);

        // Placeholder de documentos hasta vincular con el JSON real del trámite
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
