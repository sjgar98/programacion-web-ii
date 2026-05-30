<?php

class DatabaseController
{
  private DatabaseModel $model;

  public function __construct(DatabaseModel $model)
  {
    $this->model    = $model;
  }

  public function migrar()
  {
    if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1" && $_SERVER['REMOTE_ADDR'] != "172.19.0.1") {
      Log::warning("Acceso no autorizado desde " . $_SERVER['REMOTE_ADDR']);
      http_response_code(401);
      die();
    }
    $this->model->migrarDatabase();
  }
}
