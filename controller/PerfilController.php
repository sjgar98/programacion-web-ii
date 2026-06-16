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
    if ($this->request->get('userId')) {
      $userId = $this->request->get('userId');
    } elseif (isset($_SESSION['usuario_loggeado'])) {
      $userId = $_SESSION['usuario_loggeado']->id;
    } else {
      Redirect::toLogin();
    }
    $user = $this->model->getPerfilUsuario($userId);
    if ($user) {
      $partidas = $this->model->getPerfilUsuarioUltimasPartidas($userId);
      $this->renderer->render("verPerfilPropio", [
        "usuario" => $user,
        "partidas" => $partidas,
        "propio" => $user->id == $_SESSION['usuario_loggeado']->id,
        "perfil_qr" => $this->model->getPerfilUsuarioQR($user->id),
        "estilos_especificos" => array('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css')
      ]);
    } else {
      Redirect::toIndex();
    }
  }
}
