<?php

namespace App\Services;

use App\Models\Tramite;
use App\Models\FolioSequence;
use Illuminate\Support\Facades\DB;

class FolioService
{
    /** Genera el folio completo. Si $useConsecutive=true, incrementa el consecutivo real. */
    public function generate(Tramite $tramite, bool $useConsecutive = true): string
    {
        $cfg = is_array($tramite->config_json)
            ? $tramite->config_json
            : (json_decode($tramite->config_json ?? '[]', true) ?: []);

        $prefolioStr = $this->buildPrefolio($cfg['prefolio'] ?? []);
        $folioParts  = $cfg['folio']['campos'] ?? [];
        $sep         = $cfg['folio']['separador'] ?? '-';

        $parts = [];
        foreach ($folioParts as $p) {
            $t = $p['tipo'] ?? '';
            if ($t === 'Alfanumérico') {
                $parts[] = $this->applyTokens((string)($p['valor'] ?? ''));
            } elseif ($t === 'Número consecutivo') {
                $parts[] = $useConsecutive ? $this->nextConsecutive($tramite) : '0001';
            } elseif ($t === 'Fecha') {
                $parts[] = date('Ymd');
            }
        }

        $folio = implode($sep, array_filter($parts, fn($v) => $v !== ''));
        return $prefolioStr . $folio;
    }

    /** Solo vista previa sin consumir consecutivo. */
    public function preview(Tramite $tramite): string
    {
        return $this->generate($tramite, false);
    }

    public function reset(Tramite $tramite): void
    {
        FolioSequence::where('tramite_id', $tramite->id)->delete();
    }

    /* -------------------- helpers -------------------- */
    protected function buildPrefolio(array $arr): string
    {
        $out = '';
        foreach ($arr as $p) {
            $out .= ($p['tipo'] ?? '') === 'Año' ? date('Y') : (string)($p['valor'] ?? '');
        }
        return $out;
    }

    protected function applyTokens(string $v): string
    {
        $map = [
            '{AÑO}'  => date('Y'),
            '{ANO}'  => date('Y'),
            '{MES2}' => date('m'),
            '{DIA2}' => date('d'),
            '{MES}'  => date('n'),
            '{DIA}'  => date('j'),
        ];
        return strtr($v, $map);
    }

    protected function nextConsecutive(Tramite $tramite): string
    {
        $scope = 'global'; // futuro: por año -> 'year:'.date('Y')
        $next = DB::transaction(function () use ($tramite, $scope) {
            $row = FolioSequence::lockForUpdate()
                ->where('tramite_id', $tramite->id)
                ->where('scope', $scope)
                ->first();

            if (!$row) {
                $row = FolioSequence::create([
                    'tramite_id' => $tramite->id,
                    'scope'      => $scope,
                    'current'    => 0,
                ]);
                $row = FolioSequence::lockForUpdate()->find($row->id);
            }

            $row->current = $row->current + 1;
            $row->save();

            return $row->current;
        });

        return str_pad((string)$next, 4, '0', STR_PAD_LEFT); // 0001, 0002, ...
    }
}
