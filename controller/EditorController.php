<?php

class EditorController
{
    private PreguntasModel $preguntasModel;
    private ReportesModel $reportesModel;
    private Renderer $renderer;
    private Request $request;

    public function __construct(PreguntasModel $preguntasModel, ReportesModel $reportesModel, Renderer $renderer, Request $request)
    {
        $this->preguntasModel = $preguntasModel;
        $this->reportesModel = $reportesModel;
        $this->renderer = $renderer;
        $this->request = $request;
    }

    public function ver(){
        $this->renderer->render("editorPantalla");
    }

    public function listarCategorias()
    {
        $categorias = $this->preguntasModel->getCategorias();
        $this->renderer->render("editorListarCategorias", ["categorias" => $categorias]);
    }

    public function crearNuevaCategoria()
    {
        $this->renderer->render("editorCrearNuevaCategoria");
    }

    public function guardarNuevaCategoria()
    {
        $categoriaNombre = $_POST["nombre"];
        $categoriaColor = $_POST["color"];
        $this->preguntasModel->crearNuevaCategoria($categoriaNombre, $categoriaColor);
        Redirect::to("/editor/listarCategorias");
    }

    public function eliminarCategoria()
    {
        $categoriaId = $_GET["categoria_id"] ?? null;
        if ($categoriaId) {
            $this->preguntasModel->eliminarCategoria($categoriaId);
        }
        Redirect::to("/editor/listarCategorias");
    }

    public function modificarCategoria()
    {
        $categoriaId = $_GET["categoria_id"] ?? null;

        if (!$categoriaId) {
            Redirect::to("/editor/listarCategorias");
        }

        $categoria = $this->preguntasModel->getCategoriaPorId($categoriaId);

        $this->renderer->render("editorModificarCategoria", ["categoria" => $categoria]);
    }

    public function actualizarCategoria()
    {
        $categoriaId = $_POST["id"];
        $categoriaNombre = $_POST["nombre"];
        $categoriaColor = $_POST["color"];

        $this->preguntasModel->modificarCategoria($categoriaId, $categoriaNombre, $categoriaColor);

        Redirect::to("/editor/listarCategorias");
    }

    public function listarPreguntas()
    {
        $preguntas = $this->preguntasModel->getPreguntasConCategoria();
        $this->renderer->render("editorListarPreguntas", ["preguntas" => $preguntas]);
    }

    public function eliminarPregunta()
    {
        $preguntaId = $_GET["pregunta_id"] ?? null;
        if($preguntaId){
            $this->preguntasModel->darDeBajaPregunta($preguntaId);
        }
        Redirect::to("/editor/listarPreguntas");
    }

    public function crearNuevaPregunta()
    {
        $nuevaPregunta = $this->preguntasModel->getCategorias();
        $this->renderer->render("editorCrearNuevaPregunta",["categorias" => $nuevaPregunta]);
    }

    public function guardarNuevaPregunta()
    {

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

        $preguntaId = $_GET["pregunta_id"] ?? null;

        if(!$preguntaId){
            Redirect::to("/editor/listarPreguntas");
        }

        $pregunta = $this->preguntasModel->getPreguntaPorId($preguntaId);
        $categorias = $this->preguntasModel->getCategorias();
        $respuestas = $this->preguntasModel->getRespuestasPorPreguntaId($preguntaId);

        $reporteId = $_GET["reporte_id"] ?? null;
        $reporte = $this->reportesModel->getReporteById($reporteId);

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
            "reporte_id" => $reporteId,
            "reporte" => $reporte,
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

        $reporteId = $_POST["reporte_id"] ?? null;
        if ($reporteId) {
            $this->reportesModel->resolverReporte($reporteId);
        }

        Redirect::to("/editor/listarPreguntas");
    }

    public function verPreguntasSugeridas()
    {

        $preguntasSugeridas = $this->preguntasModel->getPreguntasSugeridasPorUsuario();

        $this->renderer->render("editorPreguntasSugeridas", [
            "sugerencias" => $preguntasSugeridas,
            "total_sugerencias" => count($preguntasSugeridas)
        ]);
    }

    public function aceptarPreguntaSugerida()
    {

        $preguntaId = $_GET["pregunta_id"] ?? null;

        if($preguntaId){
            $this->preguntasModel->aceptarPreguntaSugeridaPorUsuario($preguntaId);
        }

        Redirect::to("/editor/verPreguntasSugeridas");
    }

    public function rechazarPreguntaSugerida()
    {

        $preguntaId = $_GET["pregunta_id"] ?? null;

        if($preguntaId){
            $this->preguntasModel->darDeBajaPreguntaSugeridaPorUsuario($preguntaId);
        }

        Redirect::to("/editor/verPreguntasSugeridas");
    }



}