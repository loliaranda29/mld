<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use App\Models\Usuario;
use Illuminate\Support\Facades\File;

class ImportarMultiplesCsv extends Command
{
  // comando pata la terminal: php artisan import:csv-lote
  protected $signature = 'import:csv-lote {path=storage/app/csv/usuarios}';
  protected $description = 'Importa múltiples archivos CSV desde una carpeta';

  public function handle()
  {
    $path = $this->argument('path');

    if (!File::isDirectory($path)) {
      $this->error("La carpeta no existe: $path");
      return 1;
    }

    $archivos = File::files($path);
    $archivosProcesados = 0;
    $totalInsertados = 0;

    foreach ($archivos as $archivo) {
      if ($archivo->getExtension() !== 'csv') continue;

      $this->info("Procesando: " . $archivo->getFilename());

      try {
        $csv = Reader::createFromPath($archivo->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        $registros = $csv->getRecords();

        $insertadosEnEsteArchivo = 0;

        foreach ($registros as $fila) {
          Usuario::create([
            'nombre'   => $fila['nombre'] ?? '',
            'apellido' => $fila['apellido'] ?? '',
            'email'    => $fila['email'] ?? '',
            'cuil'     => $fila['cuil'] ?? '',
            // Asegurate de que los campos existen en la tabla
          ]);
          $insertadosEnEsteArchivo++;
        }

        $this->info("✔ Insertados en {$archivo->getFilename()}: $insertadosEnEsteArchivo");
        $totalInsertados += $insertadosEnEsteArchivo;
        $archivosProcesados++;
      } catch (\Exception $e) {
        $this->error("✖ Error al procesar {$archivo->getFilename()}: " . $e->getMessage());
      }
    }

    $this->info("✅ Se procesaron $archivosProcesados archivos.");
    $this->info("📥 Total de registros insertados: $totalInsertados");

    return 0;
  }
}
