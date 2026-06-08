<?php

class PerfilController
{
  private PerfilModel $model;
  private Renderer $renderer;
  private Request $request;

  public function __construct(PerfilModel $model, Renderer $renderer, Request $request)
  {
    $this->model = $model;
    $this->renderer = $renderer;
    $this->request = $request;
  }

  public function ver()
  {
    Log::info("PerfilController::ver");
    // TODO: Query Param Management
    $userId = $_SESSION['usuario_loggeado']->id;
    $user = $this->model->getPerfilUsuario($userId);
    if ($user) {
      $this->renderer->render("verPerfilPropio", [
        "usuario" => $user,
        "json" => json_encode($user),
        "estilos_especificos" => array('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css')
      ]);
    } else {
      Redirect::toIndex();
    }
  }
}
