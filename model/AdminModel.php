<?php

class AdminModel
{
  private Database $database;
  private ChartService $chartService;

  public function __construct(Database $database, ChartService $chartService)
  {
    $this->database = $database;
    $this->chartService = $chartService;
  }

  public function obtenerUsuarios(): array
  {
    $query =
      "SELECT u.id, u.username, u.email, r.nombre AS rol
       FROM usuarios u
       LEFT JOIN roles r ON u.rol_id = r.id";
    return $this->database->query($query);
  }

  public function obtenerEstadisticas(Request $request): stdClass
  {
    $totalUsuarios = $this->database->query("SELECT COUNT(*) AS total FROM usuarios", [], true)[0]->total;
    $totalPartidas = $this->database->query("SELECT COUNT(*) AS total FROM partidas", [], true)[0]->total;
    $totalPreguntas = $this->database->query("SELECT COUNT(*) AS total FROM preguntas", [], true)[0]->total;
    $usuariosConPorcentaje = $this->database->query(
      "SELECT
        u.*,
        COUNT(pr.respuesta_id) AS total_respondidas,
        COALESCE(SUM(r.es_correcta), 0) AS total_correctas,
        ROUND(COALESCE(AVG(r.es_correcta), 0) * 100, 2) AS porcentaje_correctas
      FROM usuarios u
      LEFT JOIN preguntas_resueltas pr ON u.id = pr.jugador_id
      LEFT JOIN respuestas r ON pr.respuesta_id = r.id
      GROUP BY u.id;",
      [],
      true
    );
    $usuariosPorPais = $this->database->query("SELECT pais, COUNT(*) AS cantidad FROM usuarios GROUP BY pais", [], true);
    $usuariosPorSexo = $this->database->query("SELECT sexo, COUNT(*) AS cantidad FROM usuarios GROUP BY sexo", [], true);
    $cantidadUsuariosEdadMenor = $this->database->query("SELECT COUNT(*) AS cantidad FROM usuarios WHERE YEAR(CURDATE()) - anio_nacimiento < 18", [], true)[0]->cantidad;
    $cantidadUsuariosEdadJubilado = $this->database->query("SELECT COUNT(*) AS cantidad FROM usuarios WHERE YEAR(CURDATE()) - anio_nacimiento >= 65", [], true)[0]->cantidad;
    $cantidadUsuariosEdadAdulto = $totalUsuarios - $cantidadUsuariosEdadMenor - $cantidadUsuariosEdadJubilado;

    $usuariosPorEdadChart = $this->chartService->generarGraficoTorta(
      "Usuarios por Edad",
      "Cantidad",
      [$cantidadUsuariosEdadMenor, $cantidadUsuariosEdadAdulto, $cantidadUsuariosEdadJubilado],
      "Rango de Edad",
      ["Menor de 18", "Adulto (18-64)", "Jubilado (65+)"],
    );
    $usuariosPorPaisChart = $this->chartService->generarGraficoTorta(
      "Usuarios por País",
      "Cantidad",
      array_column($usuariosPorPais, 'cantidad'),
      "Pais",
      array_column($usuariosPorPais, 'pais'),
    );
    $usuariosPorSexoChart = $this->chartService->generarGraficoTorta(
      "Usuarios por Sexo",
      "Cantidad",
      array_column($usuariosPorSexo, 'cantidad'),
      "Sexo",
      array_column($usuariosPorSexo, 'sexo'),
    );

    return (object)[
      "totalUsuarios" => $totalUsuarios,
      "totalPartidas" => $totalPartidas,
      "totalPreguntas" => $totalPreguntas,
      "usuariosConPorcentaje" => $usuariosConPorcentaje,
      "usuariosPorEdadChart" => $usuariosPorEdadChart,
      "usuariosPorPaisChart" => $usuariosPorPaisChart,
      "usuariosPorSexoChart" => $usuariosPorSexoChart
    ];
  }
}
