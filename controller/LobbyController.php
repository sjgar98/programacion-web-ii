<?php

class LobbyController
{
    private LobbyModel $model;
    private PreguntasModel $preguntasModel;
    private Renderer $renderer;
    private Request $request;

    public function __construct(LobbyModel $model,PreguntasModel $preguntasModel,Renderer $renderer, Request $request)
    {
        $this->model = $model;
        $this->preguntasModel = $preguntasModel;
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

    public function sugerirPregunta()
    {
        Auth::getUsuarioLoggeado();
        $categorias = $this->preguntasModel->getCategorias();
        $this->renderer->render("lobbySugerirPregunta.mustache", [
            "categorias" => $categorias
        ]);
    }


    public function sugerirPreguntaAlEditor()
    {
        Auth::getUsuarioLoggeado();

        $preguntaEnunciado = $_POST["enunciado"];
        $preguntaCategoriaId = $_POST["categoria_id"];
        $preguntaEsCorrecta = $_POST["es_correcta"];
        $preguntaEsIncorrecta1 = $_POST["es_incorrecta_1"];
        $preguntaEsIncorrecta2 = $_POST["es_incorrecta_2"];
        $preguntaEsIncorrecta3 = $_POST["es_incorrecta_3"];
        $preguntaId = $this->preguntasModel->agregarPregunta($preguntaEnunciado, $preguntaCategoriaId);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsCorrecta, 1);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsIncorrecta1, 0);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsIncorrecta2, 0);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsIncorrecta3, 0);
        Redirect::to("/lobby?sugerencia_enviada=true");
    }

}
