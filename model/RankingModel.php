<?php

class RankingModel
{
  private Database $database;

  public function __construct(Database $database)
  {
    $this->database = $database;
  }

  public function getRankingUsuarios(): array
  {
    $resultado = $this->database->query(
      "SELECT
        u.*,
        COALESCE(MAX(p.puntaje), 0) as puntaje_max,
        ROW_NUMBER() OVER (ORDER BY puntaje_max DESC) AS ranking
       FROM usuarios u
       LEFT JOIN partidas p
       ON u.id = p.jugador_id
       GROUP BY u.id
       ORDER BY puntaje_max DESC
       LIMIT 5;
      ",
      [],
      true
    );
    return $resultado;
  }
}
