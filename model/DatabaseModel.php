<?php

class DatabaseModel
{
  private Database $database;
  private string $migrationsDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database';

  public function __construct(Database $database)
  {
    $this->database = $database;
  }

  public function migrarDatabase(): void
  {
    # Generar la tabla de migraciones si no existe
    $this->database->begin_transaction();
    try {
      $this->database->execute("CREATE TABLE IF NOT EXISTS migraciones (
      id INT AUTO_INCREMENT PRIMARY KEY,
      migracion VARCHAR(255),
      creada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");
      $this->database->commit_transaction();
    } catch (Exception $exception) {
      $this->database->rollback_transaction();
      Log::error("SQL : $exception");
      die("<div>$exception</div>");
    }

    # Buscar todos los archivos .sql en /database
    $migracionesArchivos =
      array_filter(
        array_diff(scandir($this->migrationsDir), array('..', '.')),
        function ($nombreArchivo) {
          return str_ends_with($nombreArchivo, ".sql");
        }
      );

    # Buscar todas las migraciones ya realizadas, y mappear a objetos
    $migraciones = array_map(
      function ($migracion) {
        return (object)$migracion;
      },
      $this->database->query("SELECT * FROM migraciones;", [], true)
    );
    foreach ($migracionesArchivos as $migracionArchivo) {
      # Buscar si el archivo de migración actual ya fue ejecutado previamente
      $migracion = array_find($migraciones, function ($migracion) use ($migracionArchivo) {
        return $migracion->migracion == $migracionArchivo;
      });
      if ($migracion) {
        echo "<div>La migración " . $migracion->migracion . " ya existe</div>";
      } else {
        $migracionQuery = file_get_contents($this->migrationsDir . DIRECTORY_SEPARATOR . $migracionArchivo);
        $this->database->begin_transaction();
        try {
          $this->database->multi_query($migracionQuery);
          $this->database->execute("INSERT INTO migraciones (migracion) VALUES (\"$migracionArchivo\");");
          $this->database->commit_transaction();
          Log::info("SQL : Ejecutada migracion $migracionArchivo");
          echo "<div>Ejecutada migracion $migracionArchivo</div>";
        } catch (Exception $exception) {
          $this->database->rollback_transaction();
          Log::error("SQL : $exception");
          die("<div>$exception</div>");
        }
      }
    }
  }
}
