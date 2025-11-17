<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            // Agrega solo si no existen (evita errores en entornos donde ya estén)
            if (!Schema::hasColumn('tramites', 'general_json'))     { $table->json('general_json')->nullable(); }
            if (!Schema::hasColumn('tramites', 'formulario_json'))  { $table->json('formulario_json')->nullable(); }
            if (!Schema::hasColumn('tramites', 'etapas_json'))      { $table->json('etapas_json')->nullable(); }
            if (!Schema::hasColumn('tramites', 'documento_json'))   { $table->json('documento_json')->nullable(); }
            if (!Schema::hasColumn('tramites', 'config_json'))      { $table->json('config_json')->nullable(); }
        });
    }

    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            // Quita si existen
            if (Schema::hasColumn('tramites', 'general_json'))     { $table->dropColumn('general_json'); }
            if (Schema::hasColumn('tramites', 'formulario_json'))  { $table->dropColumn('formulario_json'); }
            if (Schema::hasColumn('tramites', 'etapas_json'))      { $table->dropColumn('etapas_json'); }
            if (Schema::hasColumn('tramites', 'documento_json'))   { $table->dropColumn('documento_json'); }
            if (Schema::hasColumn('tramites', 'config_json'))      { $table->dropColumn('config_json'); }
        });
    }
};
