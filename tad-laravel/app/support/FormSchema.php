<?php

namespace App\Support;

use Illuminate\Support\Str;

class FormSchema
{
    /** Normaliza names (snake) y garantiza unicidad global en el formulario. */
    public static function normalize(array $schema): array
    {
        $used = [];

        foreach ($schema['sections'] ?? [] as $si => &$sec) {
            foreach ($sec['fields'] ?? [] as $fi => &$f) {
                $raw  = $f['name'] ?? ($f['label'] ?? "s{$si}_f{$fi}");
                $base = Str::slug($raw, '_');
                if ($base === '') $base = "campo";

                $name = $base; $k = 2;
                while (isset($used[$name])) { $name = "{$base}_{$k}"; $k++; }

                $f['name'] = $name;
                $used[$name] = true;
            }
        }
        unset($sec, $f);

        return $schema;
    }

    /** Devuelve los nombres repetidos si los hubiera. */
    public static function duplicatedNames(array $schema): array
    {
        $seen = []; $dups = [];
        foreach ($schema['sections'] ?? [] as $sec) {
            foreach (($sec['fields'] ?? []) as $f) {
                $n = $f['name'] ?? null;
                if (!$n) continue;
                if (isset($seen[$n])) $dups[$n] = true;
                $seen[$n] = true;
            }
        }
        return array_keys($dups);
    }
}
