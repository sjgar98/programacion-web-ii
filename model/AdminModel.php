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
    $rangoFechas = $this->getRangoFechas($request->get("periodo"));

    $totalUsuarios = $this->database->query(
      "SELECT COUNT(*) AS total FROM usuarios"
        . ($rangoFechas !== null ? "\nWHERE (fecha_registro BETWEEN ? AND ?)" : ""),
      $rangoFechas ? [$rangoFechas->fechaInicio, $rangoFechas->fechaFin] : [],
      true
    )[0]->total;
    $totalPartidas = $this->database->query(
      "SELECT COUNT(*) AS total FROM partidas"
        . ($rangoFechas !== null ? "\nWHERE (fecha BETWEEN ? AND ?)" : ""),
      $rangoFechas ? [$rangoFechas->fechaInicio, $rangoFechas->fechaFin] : [],
      true
    )[0]->total;
    $totalPreguntas = $this->database->query("SELECT COUNT(*) AS total FROM preguntas", [], true)[0]->total;
    $usuariosConPorcentaje = $this->database->query(
      "SELECT
        u.*,
        COUNT(pr.respuesta_id) AS total_respondidas,
        COALESCE(SUM(r.es_correcta), 0) AS total_correctas,
        ROUND(COALESCE(AVG(r.es_correcta), 0) * 100, 2) AS porcentaje_correctas
      FROM usuarios u
      LEFT JOIN preguntas_resueltas pr ON u.id = pr.jugador_id
      LEFT JOIN respuestas r ON pr.respuesta_id = r.id"
        . ($rangoFechas !== null ? "\nWHERE (pr.fecha BETWEEN ? AND ?)\n" : "\n") .
        "GROUP BY u.id;",
      $rangoFechas ? [$rangoFechas->fechaInicio, $rangoFechas->fechaFin] : [],
      true
    );
    $usuariosPorPais = $this->database->query(
      "SELECT pais, COUNT(*) AS cantidad
      FROM usuarios"
        . ($rangoFechas !== null ? "\nWHERE (fecha_registro BETWEEN ? AND ?)" : "")
        . "\n
        GROUP BY pais",
      $rangoFechas ? [$rangoFechas->fechaInicio, $rangoFechas->fechaFin] : [],
      true
    );
    $usuariosPorSexo = $this->database->query(
      "SELECT sexo, COUNT(*) AS cantidad
      FROM usuarios"
        . ($rangoFechas !== null ? "\nWHERE (fecha_registro BETWEEN ? AND ?)" : "")
        . "\n
      GROUP BY sexo",
      $rangoFechas ? [$rangoFechas->fechaInicio, $rangoFechas->fechaFin] : [],
      true
    );
    $cantidadUsuariosEdadMenor = $this->database->query(
      "SELECT COUNT(*) AS cantidad
      FROM usuarios
      WHERE YEAR(CURDATE()) - anio_nacimiento < 18" .
        ($rangoFechas !== null ? " AND (fecha_registro BETWEEN ? AND ?)" : "")
        . "\n",
      $rangoFechas ? [$rangoFechas->fechaInicio, $rangoFechas->fechaFin] : [],
      true
    )[0]->cantidad;
    $cantidadUsuariosEdadJubilado = $this->database->query(
      "SELECT COUNT(*) AS cantidad FROM usuarios WHERE YEAR(CURDATE()) - anio_nacimiento >= 65",
      [],
      true
    )[0]->cantidad;
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

  private function getRangoFechas(string|null $periodo): stdClass|null
  {
    $hoy = new DateTime();
    switch ($periodo) {
      case "dia":
        $fechaInicio = (clone $hoy)->modify('-1 day');
        break;
      case "semana":
        $fechaInicio = (clone $hoy)->modify('-7 days');
        break;
      case "mes":
        $fechaInicio = (clone $hoy)->modify('-1 month');
        break;
      case "año":
        $fechaInicio = (clone $hoy)->modify('-1 year');
        break;
      default:
        return null;
    }
    return (object)["fechaInicio" => $fechaInicio->format('Y-m-d'), "fechaFin" => $hoy->format('Y-m-d')];
  }
}
