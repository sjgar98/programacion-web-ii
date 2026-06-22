<?php

use chillerlan\QRCode\QRCode;

class PerfilModel
{
  private Database $database;

  public function __construct(Database $database)
  {
    $this->database = $database;
  }

  public function getPerfilUsuario(int $id)
  {
    $resultados = $this->database->query(
      "WITH usuarios_rankeados AS (
        SELECT
          u.*,
          COALESCE(MAX(p.puntaje), 0) as puntaje_max,
          ROW_NUMBER() OVER (ORDER BY puntaje_max DESC) AS ranking
        FROM usuarios u
        LEFT JOIN partidas p
        ON u.id = p.jugador_id
        GROUP BY u.id
        ORDER BY puntaje_max DESC
      )
      SELECT * FROM usuarios_rankeados
      WHERE id = ?;",
      [$id],
      true
    );
    return count($resultados) == 1 ? $resultados[0] : null;
  }

  public function getPerfilUsuarioQR(int $id)
  {
    $profileUrl = Utils::getBaseUrl() . "/perfil?userId=$id";
    $qrUri = (new QRCode())->render($profileUrl);
    return $qrUri;
  }

  public function getPerfilUsuarioUltimasPartidas(int $id)
  {
    $resultados = $this->database->query(
      "SELECT *, DATE_FORMAT(fecha, '%d/%m/%y %H:%i') as fecha_formatted
      FROM partidas
      WHERE jugador_id = ?
      ORDER BY fecha DESC
      LIMIT 5;",
      [$id],
      true
    );
    return $resultados;
  }
}
