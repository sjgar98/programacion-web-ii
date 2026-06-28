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
        $usuarioObjeto = Auth::getUsuarioLoggeado();
        $id = $usuarioObjeto->id;

        $datosUsuarioArray = $this->model->obtenerDatosUsuario($id);
        $historialPartidas = $this->model->obtenerHistorialPartidas($id);
        $puntajeRankingArray = $this->model->obtenerPuntajeRanking($id);

        $usuario = !empty($datosUsuarioArray) ? $datosUsuarioArray[0] : null;
        $puntajeMax = !empty($puntajeRankingArray) ? ($puntajeRankingArray[0]['puntaje_max'] ?? 0) : 0;

        $this->renderer->render("lobby.mustache", [
            "usuario" => $usuario,
            "puntaje_max" => $puntajeMax,
            "partidas" => $historialPartidas
        ]);
    }
}
