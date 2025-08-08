<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BandejaController extends Controller
{
    public function index()
    {
        // Más adelante podés cargar solicitudes reales desde DB
        return view('pages.profile.funcionario.bandeja');
    }
    public function show($id)
    {
        $documentos = [
            'DNI Frontal',
            'DNI Reverso',
            'Denuncia correspondiente',
            'Archivo - documentosAdicionales - 1',
            'Boleto de pago',
            'Comprobante de pago',
        ];

        return view('pages.profile.funcionario.bandeja_show', [
            'active' => 'bandeja',
            'id' => $id,
            'documentos' => $documentos,
        ]);
    }


}
