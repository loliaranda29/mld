
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tramites', function (Blueprint $table) {
            if (!Schema::hasColumn('tramites', 'formulario_json'))  $table->json('formulario_json')->nullable();
            if (!Schema::hasColumn('tramites', 'etapas_json'))      $table->json('etapas_json')->nullable();
            if (!Schema::hasColumn('tramites', 'documento_json'))   $table->json('documento_json')->nullable();
            if (!Schema::hasColumn('tramites', 'config_json'))      $table->json('config_json')->nullable();
        });
    }

    public function down(): void {
        Schema::table('tramites', function (Blueprint $table) {
            // si querés, podés dropear las columnas
            // $table->dropColumn(['formulario_json','etapas_json','documento_json','config_json']);
        });
    }
};
