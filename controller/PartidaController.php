<?php

class PartidaController
{
    private PreguntasModel $preguntasModel;
    private PartidaModel $partidaModel;
    private Renderer $renderer;
    private Request $request;

    public function __construct(PreguntasModel $preguntasModel, Renderer $renderer, Request $request, PartidaModel $partidaModel)
    {
        $this->preguntasModel = $preguntasModel;
        $this->partidaModel = $partidaModel;
        $this->renderer = $renderer;
        $this->request  = $request;
    }
    public function ver()
    {
        Log::info("PartidaController::ver");
        if (empty($_SESSION['usuario_loggeado'])) {
            Redirect::toIndex();
            return;
        }

        $this->partidaModel->establecerPartida();

        $tiempoRestante = 60;
        if (isset($_SESSION['tiempo_limite'])) {
            $tiempoRestante = $_SESSION['tiempo_limite'] - time();

            if ($tiempoRestante < 0) {
                $tiempoRestante = 0;
            }
        }

        $this->renderer->render("partida", [
            'pregunta'        => $_SESSION['pregunta'] ?? null,
            'respuesta'       => $_SESSION['respuesta'] ?? null,
            'tiempo_restante' => $tiempoRestante
        ]);
    }
    public function verificarRespuesta()
    {
        if (empty($_SESSION['usuario_loggeado'])) {
            Redirect::toIndex();
            return;
        }

        $idRespuesta = $_POST['id_respuesta'] ?? null;

        $esTimeout = false;
        if (isset($_SESSION['tiempo_limite']) && time() > ($_SESSION['tiempo_limite'] + 2)) {
            $esTimeout = true;
        }

        if ($esTimeout || empty($idRespuesta)) {
            $this->partidaModel->reestabelecerPartida(false, null);
            Redirect::toIndex();
            return;
        }

        $infoRespuesta = $this->partidaModel->obtenerRespuestaPorId((int)$idRespuesta);

        if (!$infoRespuesta) {
            Redirect::toIndex();
            return;
        }

        $esCorrecta = ($infoRespuesta['es_correcta'] == 1);
        $this->partidaModel->reestabelecerPartida($esCorrecta, $idRespuesta);

        if ($esCorrecta) {
            Redirect::to("/partida/ver");
        } else {
            Redirect::toIndex();
        }
    }
}
