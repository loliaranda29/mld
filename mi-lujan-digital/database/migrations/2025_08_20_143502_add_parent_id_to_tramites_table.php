<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Crear columna si no existe
        if (!Schema::hasColumn('tramites', 'parent_id')) {
            Schema::table('tramites', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            });
        }

        // 2) Agregar FK sin acción ON DELETE (NO ACTION por defecto en SQL Server)
        //    Le damos un nombre explícito al constraint para evitar conflictos.
        //    Si ya existiera, no lo volvemos a crear.
        $fkName = 'tramites_parent_id_fk';

        // Comprobación de existencia del FK en SQL Server
        $exists = DB::selectOne("
            SELECT 1 AS x
            FROM sys.foreign_keys
            WHERE name = ?
        ", [$fkName]);

        if (!$exists) {
            Schema::table('tramites', function (Blueprint $table) use ($fkName) {
                $table->foreign('parent_id', $fkName)
                      ->references('id')
                      ->on('tramites'); // sin onDelete => NO ACTION
            });
        }
    }

    public function down(): void
    {
        // Bajar el FK si existe y luego la columna (si existe)
        $fkName = 'tramites_parent_id_fk';

        // Quitar FK (si existe)
        try {
            Schema::table('tramites', function (Blueprint $table) use ($fkName) {
                // dropForeign acepta el nombre del constraint
                $table->dropForeign($fkName);
            });
        } catch (\Throwable $e) {
            // Ignorar si no existía
        }

        // Quitar columna si existe
        if (Schema::hasColumn('tramites', 'parent_id')) {
            Schema::table('tramites', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
    }
};
