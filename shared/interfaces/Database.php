<?php

interface Database
{
  public function query(string $sql, array $params = [], bool $asObjects = false): array;
  public function multi_query(string $sql): void;
  public function execute(string $sql, array $params = []): string | int;
  public function begin_transaction(): void;
  public function commit_transaction(): void;
  public function rollback_transaction(): void;
  public function getConexion(): \mysqli;
}
