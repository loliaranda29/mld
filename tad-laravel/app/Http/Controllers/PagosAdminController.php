<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PagosAdminController extends Controller
{
    public function index()
    {
        $historialUT = [
            ['id' => 3, 'anio' => 2025, 'valor' => 210.00],
            ['id' => 2, 'anio' => 2024, 'valor' => 167.00],
            ['id' => 1, 'anio' => 2023, 'valor' => 130.00],
        ];

        return view('pages.profile.funcionario.pagos.index', [
            'active'      => 'pagos',
            'historialUT' => $historialUT,
        ]);
    }

    public function conceptos()
    {
        return view('pages.profile.funcionario.pagos.conceptos');
    }

    public function config()
    {
        // si más adelante traes historial de BD, pásalo aquí
        return view('pages.profile.funcionario.pagos.index');
    }

    // ---- Valor de la UT ----

    public function utStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'anio'  => ['required','integer','digits:4','min:2000','max:2100'],
            'valor' => ['required','numeric','min:0'],
        ]);

        // TODO: guardar en BD (por ahora simulación)
        // UTHistorial::create($data);

        return back()->with('ok', "UT {$data['anio']} registrada por $" . number_format($data['valor'], 2));
    }

    public function utUpdate(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'anio'  => ['required','integer','digits:4','min:2000','max:2100'],
            'valor' => ['required','numeric','min:0'],
        ]);

        // TODO: actualizar en BD: UTHistorial::findOrFail($id)->update($data);

        return back()->with('ok', "UT {$data['anio']} actualizada.");
    }

    public function utDestroy(int $id): RedirectResponse
    {
        // TODO: eliminar en BD: UTHistorial::findOrFail($id)->delete();

        return back()->with('ok', 'Registro de UT eliminado.');
    }
}
