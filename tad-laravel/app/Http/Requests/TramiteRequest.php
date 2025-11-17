<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TramiteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre' => ['required','string','max:255'],
            'descripcion' => ['nullable','string'],

            // admitimos array o string json
            'formulario_json'       => ['nullable'],
            'etapas_json'           => ['nullable'],
            'documento_salida_json' => ['nullable'], // <- viene de tu documento.blade.php
            'config_json'           => ['nullable'],
        ];
    }

    /**
     * Normaliza a arrays seguros.
     */
    public function getNormalized(): array
    {
        $data = $this->validated();

        $norm = function($key) use (&$data) {
            $v = $data[$key] ?? null;
            if (is_array($v)) return $v;
            if (is_string($v) && $v !== '') {
                $d = json_decode($v, true);
                return is_array($d) ? $d : [];
            }
            return [];
        };

        return [
            'nombre'          => $data['nombre'] ?? null,
            'descripcion'     => $data['descripcion'] ?? null,
            'formulario_json' => $norm('formulario_json'),
            'etapas_json'     => $norm('etapas_json'),
            // mapeo documento_salida_json -> documento_json
            'documento_json'  => $norm('documento_salida_json'),
            'config_json'     => $norm('config_json'),
        ];
    }
}
