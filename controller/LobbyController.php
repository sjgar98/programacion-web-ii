<?php

class LobbyController
{
    private LobbyModel $model;
    private Renderer $renderer;
    private Request $request;

    public function __construct(LobbyModel $model, Renderer $renderer, Request $request)
    {
        $this->model = $model;
        $this->renderer = $renderer;
        $this->request = $request;
    }

    public function ver()
    {
        if (!isset($_SESSION['usuario_loggeado']) || empty($_SESSION['usuario_loggeado'])) {
            Redirect::toLogin();
        }

        $usuarioObjeto = $_SESSION['usuario_loggeado'];
        $id = $usuarioObjeto->id;

        $datosUsuarioArray = $this->model->obtenerDatosUsuario($id);
        $historialPartidas = $this->model->obtenerHistorialPartidas($id);
        $puntajeRankingArray = $this->model->obtenerPuntajeRanking($id);

        $usuario = !empty($datosUsuarioArray) ? $datosUsuarioArray[0] : null;
        $puntajeTotal = !empty($puntajeRankingArray) ? ($puntajeRankingArray[0]['puntaje_total'] ?? 0) : 0;

        $this->renderer->render("lobby.mustache", [
            "usuario" => $usuario,
            "puntaje_total" => $puntajeTotal,
            "partidas" => $historialPartidas
        ]);
    }
}
