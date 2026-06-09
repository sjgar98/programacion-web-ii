<?php

class MysqliDatabase implements Database
{
  private \mysqli $conexion;

  public function __construct(string $hostname, string $username, string $password, string $database)
  {
    $this->conexion = new mysqli($hostname, $username, $password, $database);
    $this->conexion->set_charset("utf8");
  }

  public function query(string $sql, array $params = [], bool $asObjects = false): array
  {
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    return $asObjects ? array_map(function ($value) {
      return (object)$value;
    }, $results)  : $results;
  }

  public function multi_query(string $sql): void
  {
    $this->conexion->multi_query($sql);
    while ($this->conexion->more_results()) {
      $this->conexion->next_result();
    }
  }

  public function execute(string $sql, array $params = []): string | int
  {
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute($params);
    return $this->conexion->affected_rows;
  }

  public function begin_transaction(): void
  {
    $this->conexion->begin_transaction();
  }

  public function commit_transaction(): void
  {
    $this->conexion->commit();
  }

  public function rollback_transaction(): void
  {
    $this->conexion->rollback();
  }

  public function __destruct()
  {
    $this->conexion->close();
  }
}
