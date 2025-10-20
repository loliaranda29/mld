<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            if (!Schema::hasColumn('tramites', 'acepta_solicitudes'))  { $table->boolean('acepta_solicitudes')->default(false); }
            if (!Schema::hasColumn('tramites', 'acepta_pruebas'))      { $table->boolean('acepta_pruebas')->default(false); }
            if (!Schema::hasColumn('tramites', 'modulo_citas'))        { $table->boolean('modulo_citas')->default(false); }
            if (!Schema::hasColumn('tramites', 'modulo_inspectores'))  { $table->boolean('modulo_inspectores')->default(false); }
        });
    }

    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            if (Schema::hasColumn('tramites', 'acepta_solicitudes'))  { $table->dropColumn('acepta_solicitudes'); }
            if (Schema::hasColumn('tramites', 'acepta_pruebas'))      { $table->dropColumn('acepta_pruebas'); }
            if (Schema::hasColumn('tramites', 'modulo_citas'))        { $table->dropColumn('modulo_citas'); }
            if (Schema::hasColumn('tramites', 'modulo_inspectores'))  { $table->dropColumn('modulo_inspectores'); }
        });
    }
};
