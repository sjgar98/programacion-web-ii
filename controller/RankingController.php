<?php

class RankingController
{
  private RankingModel $model;
  private Renderer $renderer;
  private Request $request;

  public function __construct(RankingModel $model, Renderer $renderer, Request $request)
  {
    $this->model = $model;
    $this->renderer = $renderer;
    $this->request = $request;
  }

  public function ver()
  {
    Log::info("RankingController::ver");
    if (!isset($_SESSION['usuario_loggeado'])) {
      Redirect::toLogin();
    }
    $listaUsuariosRanking = $this->model->getRankingUsuarios();
    $this->renderer->render("verRanking", [
      "usuarios_ranking" => $listaUsuariosRanking
    ]);
  }
}
