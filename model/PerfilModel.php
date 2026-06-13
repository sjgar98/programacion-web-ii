<?php

use chillerlan\QRCode\QRCode;

class PerfilModel
{
  private Database $database;

  public function __construct(Database $database)
  {
    $this->database = $database;
  }

  public function getPerfilUsuario(int $id) {
    $resultados = $this->database->query("SELECT * FROM usuarios WHERE id = ?", [$id], true);
    return count($resultados) == 1 ? $resultados[0] : null;
  }

  public function getPerfilUsuarioQR(int $id) {
    $profileUrl = Utils::getBaseUrl() . "/perfil?userId=$id";
    $qrUri = (new QRCode())->render($profileUrl);
    return $qrUri;
  }
}
