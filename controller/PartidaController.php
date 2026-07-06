<?php

class PartidaController
{

    private PartidaModel $partidaModel;
    private TrampasModel $trampasModel;
    private Renderer $renderer;
    private Request $request;

    public function __construct(Renderer $renderer, Request $request, PartidaModel $partidaModel, TrampasModel $trampasModel)
    {

        $this->partidaModel = $partidaModel;
        $this->renderer = $renderer;
        $this->request  = $request;
        $this->trampasModel = $trampasModel;
    }
    public function ver()
    {

        Log::info("PartidaController::ver");
        Auth::getUsuarioLoggeado();

        $this->partidaModel->establecerPartida();

        $tiempoRestante = 60;
        $trampaJugador = $this->trampasModel->verTrampasJugador($_SESSION['usuario_loggeado']->id);
        if (isset($_SESSION['tiempo_limite'])) {
            $tiempoRestante = $_SESSION['tiempo_limite'] - time();

            if ($tiempoRestante < 0) {
                $tiempoRestante = 0;
            }
        }

        $this->renderer->render("partida", [
            'pregunta'        => $_SESSION['pregunta'] ?? null,
            'respuesta'       => $_SESSION['respuesta'] ?? null,
            'tiempo_restante' => $tiempoRestante,
            'trampa'          => is_array($trampaJugador) ? $trampaJugador : ['cantidad' => $trampaJugador]
        ]);
    }
    public function verificarRespuesta()
    {
        Auth::getUsuarioLoggeado();

        $idRespuesta = $_POST['id_respuesta'] ?? null;

        $esTimeout = false;
        if (isset($_SESSION['tiempo_limite']) && time() > ($_SESSION['tiempo_limite'] + 2)) {
            $esTimeout = true;
        }

        if ($esTimeout || empty($idRespuesta)) {
            $this->partidaModel->reestabelecerPartida(false, null, false);
            Redirect::toIndex();
        }

        $infoRespuesta = $this->partidaModel->obtenerRespuestaPorId((int)$idRespuesta);

        if (!$infoRespuesta) {
            Redirect::toIndex();
        }

        $esCorrecta = ($infoRespuesta['es_correcta'] == 1);
        $this->partidaModel->reestabelecerPartida($esCorrecta, $idRespuesta, false);

        if ($esCorrecta) {
            Redirect::to("/partida");
        } else {
            $this->renderer->render('resumenPartida.mustache', [
                'puntaje' => $this->partidaModel->obtenerDatosPartida($_SESSION['usuario_loggeado']->id)['puntaje'] ?? null,
                'preguntas' => $this->partidaModel->obtenerPreguntasDeLaPartida($_SESSION['usuario_loggeado']->id) ?? null,
                'fecha' => $this->partidaModel->obtenerDatosPartida($_SESSION['usuario_loggeado']->id)['fecha'] ?? null,
            ]);
        }
    }

    public function usarTrampa()
    {

        Auth::getUsuarioLoggeado();

        $esTimeout = false;
        if (isset($_SESSION['tiempo_limite']) && time() > ($_SESSION['tiempo_limite'] + 2)) {
            $esTimeout = true;
        }

        if ($esTimeout) {
            $this->partidaModel->reestabelecerPartida(false, null, false);
            Redirect::toIndex();
        }

        $respuestaCorrecta = $this->partidaModel->obtenerRespuestaCorrectaPorPregunta($_SESSION['pregunta']->id);
        Log::info("Respuesta del pana " . $respuestaCorrecta['id']);

        $this->partidaModel->descontarTrampaJugador($_SESSION['usuario_loggeado']->id);
        $this->partidaModel->reestabelecerPartida(true, $respuestaCorrecta['id'], true);

        Redirect::to("/partida");
    }
}
