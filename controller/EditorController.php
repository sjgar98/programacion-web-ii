<?php

class EditorController
{
    private PreguntasModel $preguntasModel;
    private Renderer $renderer;
    private Request $request;

    public function __construct(PreguntasModel $preguntasModel,Renderer $renderer,Request $request)
    {
        $this->preguntasModel = $preguntasModel;
        $this->renderer = $renderer;
        $this->request = $request;
    }

    public function ver(){
        Auth::puedeAccederEditor();
        $this->renderer->render("editorPantalla");
    }

    public function listarPreguntas()
    {
        Auth::puedeAccederEditor();

        $preguntas = $this->preguntasModel->getPreguntasConCategoria();

        $this->renderer->render("editorListarPreguntas", ["preguntas" => $preguntas]);

    }

    public function eliminarPregunta()
    {
        Auth::puedeAccederEditor();

        $preguntaId = $_GET["pregunta_id"] ?? null;
        if($preguntaId){
            $this->preguntasModel->darDeBajaPregunta($preguntaId);
        }
        Redirect::to("/editor/listarPreguntas");
    }

    public function crearNuevaPregunta()
    {
        Auth::puedeAccederEditor();
        $nuevaPregunta = $this->preguntasModel->getCategorias();
        $this->renderer->render("editorCrearNuevaPregunta",["categorias" => $nuevaPregunta]);
    }

    public function guardarNuevaPregunta()
    {
        Auth::puedeAccederEditor();

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
        Redirect::to("/editor/listarPreguntas");
    }

    public function modificarPregunta()
    {
        Auth::puedeAccederEditor();

        $preguntaId = $_GET["pregunta_id"] ?? null;

        if(!$preguntaId){
            Redirect::to("/editor/listarPreguntas");
        }

        $pregunta = $this->preguntasModel->getPreguntaPorId($preguntaId);
        $categorias = $this->preguntasModel->getCategorias();
        $respuestas = $this->preguntasModel->getRespuestasPorPreguntaId($preguntaId);

        $idCategoriaActual = $pregunta[0]["categoria_id"] ?? null;

        foreach ($categorias as &$categoria) {
            if ($categoria["id"] == $idCategoriaActual) {
                $categoria["selected"] = true;
            } else {
                $categoria["selected"] = false;
            }
        }
        unset($categoria);

        $this->renderer->render("editorModificarPregunta",[
            "pregunta" => $pregunta,
            "categorias" => $categorias,
            "es_correcta" => $respuestas[0]["texto"] ?? '',
            "incorrecta_1" => $respuestas[1]["texto"] ?? '',
            "incorrecta_2" => $respuestas[2]["texto"] ?? '',
            "incorrecta_3" => $respuestas[3]["texto"] ?? ''
        ]);
    }

    public function actualizarPregunta()
    {
        Auth::puedeAccederEditor();
        $preguntaId = $_POST["id"];
        $preguntaEnunciado = $_POST["enunciado"];
        $preguntaCategoriaId = $_POST["categoria_id"];
        $preguntaEsCorrecta = $_POST["es_correcta"];
        $preguntaEsIncorrecta1 = $_POST["es_incorrecta_1"];
        $preguntaEsIncorrecta2 = $_POST["es_incorrecta_2"];
        $preguntaEsIncorrecta3 = $_POST["es_incorrecta_3"];
        $this->preguntasModel->modificarPregunta($preguntaId, $preguntaEnunciado, $preguntaCategoriaId);
        $this->preguntasModel->eliminarRespuestasPorPregunta($preguntaId);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsCorrecta, 1);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsIncorrecta1, 0);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsIncorrecta2, 0);
        $this->preguntasModel->agregarRespuesta($preguntaId,$preguntaEsIncorrecta3, 0);
        Redirect::to("/editor/listarPreguntas");

    }



}