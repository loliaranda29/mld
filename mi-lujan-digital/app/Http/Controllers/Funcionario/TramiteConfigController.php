<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Models\Tramite;
use App\Services\FolioService;

class TramiteConfigController extends Controller
{
    public function previewFolio(Tramite $tramite, FolioService $svc)
    {
        return response()->json(['ok' => true, 'preview' => $svc->preview($tramite)]);
    }

    public function generarFolio(Tramite $tramite, FolioService $svc)
    {
        return response()->json(['ok' => true, 'folio' => $svc->generate($tramite, true)]);
    }

    public function resetFolio(Tramite $tramite, FolioService $svc)
    {
        $svc->reset($tramite);
        return response()->json(['ok' => true]);
    }
}
