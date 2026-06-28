<?php

class EditorController
{
    private PreguntasModel $preguntasModel;
    private ReporteModel $reporteModel;
    private Renderer $renderer;
    private Request $request;

    public function __construct(PreguntasModel $preguntasModel,ReporteModel $reporteModel,Renderer $renderer,Request $request)
    {
        $this->preguntasModel = $preguntasModel;
        $this->reporteModel = $reporteModel;
        $this->renderer = $renderer;
        $this->request = $request;
    }

    public function ver(){
        $this->validarAcceso();
        $this->renderer->render("editorPantalla");
    }

    public function listarPreguntas()
    {
        $this->validarAcceso();

        $preguntas = $this->preguntasModel->getAllElementos();

        $this->renderer->render("editorListarPreguntas", ["preguntas" => $preguntas]);

    }

    public function listarReportes()
    {
        $this->validarAcceso();

        $reportes = $this->reporteModel->listarReportesPendientes();

        $this->renderer->render("editorListarReportes", ["reportes" => $reportes]);
    }


    public function eliminarPregunta()
    {
        $this->validarAcceso();

        $preguntaId = $_GET["pregunta_id"] ?? null;
        if($preguntaId){
            $this->preguntasModel->darDeBajaPregunta($preguntaId);
        }
        Redirect::to("/editor/listarPreguntas");
    }

    public function crearNuevaPregunta()
    {
        $this->validarAcceso();
        $nuevaPregunta = $this->preguntasModel->getCategorias();
        $this->renderer->render("editorCrearNuevaPregunta",["categorias" => $nuevaPregunta]);
    }

    public function guardarNuevaPregunta()
    {
        $this->validarAcceso();

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
        $this->validarAcceso();

        $preguntaId = $_GET["pregunta_id"] ?? null;

        if(!$preguntaId){
            Redirect::to("/editor/listarPreguntas");
        }

        $pregunta = $this->preguntasModel->getPreguntaId($preguntaId);
        $categorias = $this->preguntasModel->getCategorias();
        $this->renderer->render("editorModificarPregunta",[
            "pregunta" => $pregunta,
            "categorias" => $categorias]);
    }

    public function actualizarPregunta()
    {
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

    public function aceptarReporte()
    {
        $this->validarAcceso();

        $preguntaId = $_GET["pregunta_id"];
        $reporteId = $_GET["reporte_id"];
        $this->preguntasModel->darDeBajaPregunta($preguntaId);
        $this->reporteModel->cambiarEstadoReporte($reporteId);
        Redirect::to("/editor/listarReportes");

    }

    public function ignorarReporte()
    {
        $this->validarAcceso();

        $reporteId = $_GET["reporte_id"];
        $this->reporteModel->cambiarEstadoReporte($reporteId);
        Redirect::to("/editor/listarReportes");
    }

    private function validarAcceso()
    {
        if(!isset($_SESSION["usuario_loggeado"]) || empty($_SESSION["usuario_loggeado"])){
            Redirect::toLogin();
        }

        if($_SESSION["usuario_loggeado"]->rol_id != 2){
            Redirect::toLobby();
        }
    }



}