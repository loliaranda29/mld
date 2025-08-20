<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Crear tabla si no existe
        if (!Schema::hasTable('tramite_relaciones')) {
            Schema::create('tramite_relaciones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tramite_id');
                $table->unsignedBigInteger('relacionado_id');
                $table->string('tipo', 50)->default('vinculado'); // subtramite, prerrequisito, etc.
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->unique(['tramite_id','relacionado_id','tipo'], 'tramite_relacion_unq');
            });
        }

        // 2) Asegurar FKs SIN acción ON DELETE (NO ACTION por defecto en SQL Server)
        //    Primero limpiamos posibles FKs previos con nombres por defecto.
        $this->dropFkIfExists('tramite_relaciones', 'tramite_relaciones_tramite_id_foreign');
        $this->dropFkIfExists('tramite_relaciones', 'tramite_relaciones_relacionado_id_foreign');

        //    Ahora creamos con nombres explícitos si no existen.
        $this->createFkIfMissing(
            table: 'tramite_relaciones',
            fkName: 'tramrel_tramite_fk',
            column: 'tramite_id',
            refTable: 'tramites',
            refColumn: 'id'
        );

        $this->createFkIfMissing(
            table: 'tramite_relaciones',
            fkName: 'tramrel_relacionado_fk',
            column: 'relacionado_id',
            refTable: 'tramites',
            refColumn: 'id'
        );
    }

    public function down(): void
    {
        // Bajar FKs si existen
        $this->dropFkIfExists('tramite_relaciones', 'tramrel_tramite_fk');
        $this->dropFkIfExists('tramite_relaciones', 'tramrel_relacionado_fk');

        // Dropear tabla
        Schema::dropIfExists('tramite_relaciones');
    }

    private function dropFkIfExists(string $table, string $fkName): void
    {
        // Sólo SQL Server: chequeo en sys.foreign_keys
        $exists = DB::selectOne("
            SELECT 1 AS x
            FROM sys.foreign_keys
            WHERE name = ?
        ", [$fkName]);

        if ($exists) {
            DB::statement("ALTER TABLE {$table} DROP CONSTRAINT {$fkName}");
        }
    }

    private function createFkIfMissing(
        string $table,
        string $fkName,
        string $column,
        string $refTable,
        string $refColumn = 'id'
    ): void {
        $exists = DB::selectOne("
            SELECT 1 AS x
            FROM sys.foreign_keys
            WHERE name = ?
        ", [$fkName]);

        if (!$exists) {
            Schema::table($table, function (Blueprint $table) use ($fkName, $column, $refTable, $refColumn) {
                $table->foreign($column, $fkName)
                      ->references($refColumn)
                      ->on($refTable); // sin onDelete => NO ACTION
            });
        }
    }
};
