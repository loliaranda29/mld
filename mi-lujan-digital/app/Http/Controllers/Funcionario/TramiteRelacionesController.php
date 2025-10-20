<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TramiteRelacionesController extends Controller
{
    public function update(Request $request, Tramite $tramite)
    {
        $data = $request->validate([
            'parent_id'        => ['nullable', 'integer', Rule::exists('tramites', 'id')],
            'children_ids'     => ['array'],
            'children_ids.*'   => ['integer', Rule::exists('tramites', 'id')],
            'links_ids'        => ['array'],
            'links_ids.*'      => ['integer', Rule::exists('tramites', 'id')],
        ]);

        // Limpieza básica
        $parentId   = isset($data['parent_id']) ? (int)$data['parent_id'] : null;
        $children   = collect($data['children_ids'] ?? [])->map(fn($v)=>(int)$v)->unique()->values();
        $links      = collect($data['links_ids'] ?? [])->map(fn($v)=>(int)$v)->unique()->values();

        // No permitir auto-relaciones
        if ($parentId === $tramite->id) {
            return back()->withErrors(['parent_id' => 'No se puede asignar a sí mismo como padre.']);
        }
        if ($children->contains($tramite->id) || $links->contains($tramite->id)) {
            return back()->withErrors(['children_ids' => 'No se puede relacionar el trámite consigo mismo.']);
        }

        // Evitar ciclos: el padre no puede ser un descendiente
        if ($parentId) {
            $cursor = Tramite::find($parentId);
            while ($cursor) {
                if ($cursor->id === $tramite->id) {
                    return back()->withErrors(['parent_id' => 'Selección inválida: se forma un ciclo de parentesco.']);
                }
                $cursor = $cursor->parent;
            }
        }

        DB::transaction(function () use ($tramite, $parentId, $children, $links) {
            // 1) Padre
            $tramite->parent_id = $parentId ?: null;
            $tramite->save();

            // 2) Hijos (setear parent_id = $tramite->id a los elegidos, limpiar los removidos)
            $existentes = $tramite->children()->pluck('id')->all();
            $toAttach   = array_values(array_diff($children->all(), $existentes));
            $toDetach   = array_values(array_diff($existentes, $children->all()));

            if (!empty($toAttach)) {
                Tramite::whereIn('id', $toAttach)->update(['parent_id' => $tramite->id]);
            }
            if (!empty($toDetach)) {
                Tramite::whereIn('id', $toDetach)->where('parent_id', $tramite->id)->update(['parent_id' => null]);
            }

            // 3) Vínculos laterales (simétricos)
            // Enfoque simple: borrar cualquier fila donde participe este trámite y reinsertar espejado
            DB::table('tramite_vinculos')
                ->where('tramite_id', $tramite->id)
                ->orWhere('vinculo_id', $tramite->id)
                ->delete();

            if ($links->isNotEmpty()) {
                $rows = [];
                foreach ($links as $id) {
                    $rows[] = ['tramite_id' => $tramite->id, 'vinculo_id' => $id, 'created_at'=>now(), 'updated_at'=>now()];
                    $rows[] = ['tramite_id' => $id,           'vinculo_id' => $tramite->id, 'created_at'=>now(), 'updated_at'=>now()];
                }
                DB::table('tramite_vinculos')->insertOrIgnore($rows);
            }
        });

        return back()->with('success', 'Relaciones actualizadas.');
    }
}
