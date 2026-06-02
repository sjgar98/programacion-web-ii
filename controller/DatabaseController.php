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
    if (!Utils::isRequestFromNetwork("127.0.0.0/8", "192.168.0.0/16", "172.16.0.0/12")) {
      Log::warning("Acceso no autorizado desde " . $_SERVER['REMOTE_ADDR']);
      http_response_code(401);
      die();
    }
    $this->model->migrarDatabase();
  }
}
