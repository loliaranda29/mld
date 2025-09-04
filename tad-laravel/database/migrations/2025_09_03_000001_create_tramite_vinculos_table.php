<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tramite_vinculos')) {
            Schema::create('tramite_vinculos', function (Blueprint $table) {
                $table->unsignedBigInteger('tramite_id');
                $table->unsignedBigInteger('vinculo_id');

                // Evita duplicados en la dirección (A -> B)
                $table->primary(['tramite_id', 'vinculo_id']);

                // SOLO una cascada para evitar "multiple cascade paths"
                $table->foreign('tramite_id')
                      ->references('id')->on('tramites')
                      ->cascadeOnDelete();

                // La otra, sin cascada (NO ACTION/RESTRICT)
                // Con Laravel podés usar restrictOnDelete() o onDelete('no action') para SQL Server
                $table->foreign('vinculo_id')
                      ->references('id')->on('tramites')
                      ->onDelete('no action');

                // timestamps opcionales, podés quitarlos si no los usás
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tramite_vinculos');
    }
};
