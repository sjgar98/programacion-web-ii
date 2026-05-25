<?php

interface Database
{
  public function query(string $sql, array $params = []): array;
  public function execute(string $sql, array $params): string | int;
}
