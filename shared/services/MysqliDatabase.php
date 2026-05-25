<?php

class MysqliDatabase implements Database
{
  private \mysqli $conexion;

  public function __construct(string $hostname, string $username, string $password, string $database)
  {
    $this->conexion = new mysqli($hostname, $username, $password, $database);
  }

  public function query(string $sql, array $params = []): array
  {
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute($params);
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  }

  public function execute(string $sql, array $params = []): string | int
  {
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute($params);
    return $this->conexion->affected_rows;
  }

  public function __destruct()
  {
    $this->conexion->close();
  }
}
