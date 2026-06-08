<?php

class PreguntasController
{
    private PreguntasModel $preguntasModel;
    private RespuestasModel $respuestasModel;
    private Renderer $renderer;
    private Request $request;


    public function __construct(PreguntasModel $preguntasModel, Renderer $renderer, Request $request, RespuestasModel $respuestasModel)
    {
        $this->preguntasModel = $preguntasModel;
        $this->respuestasModel = $respuestasModel;
        $this->renderer = $renderer;
        $this->request  = $request;
    }

    public function ver()
    {
        Log::info("PreguntaController::ver");
        $this->renderer->render("preguntasView", [
            'pregunta' => $this->preguntasModel->getPreguntaId(),
            'respuesta' => $this->preguntasModel->getRespuestasPorPregunta()
        ]);
    }

    public function procesarAlta()
    {
        $nombre = $this->request->post('nombre');
        Log::info("VikingoController::procesarAlta - nombre=$nombre");
        $this->preguntasModel->crearNuevoElemento($nombre);
        Redirect::toIndex();
    }
}
