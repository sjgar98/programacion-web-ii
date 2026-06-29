<?php

class ReportesController
{
    private Renderer $renderer;
    private Request $request;
    private ReportesModel $reportes_model;
    private PartidaModel $partidaModel;

    public function __construct(Renderer $renderer, Request $request, ReportesModel $reportesModel, PartidaModel $partidaModel)
    {
        $this->partidaModel = $partidaModel;
        $this->reportes_model = $reportesModel;
        $this->renderer = $renderer;
        $this->request  = $request;
    }
    public function ver()
    {

        Log::info("ReportesController::ver");
        //Auth::getUsuarioLoggeado();
        Auth::puedeAccederEditor();
        $this->renderer->render('reportes.mustache', ['reportes' => $this->reportes_model->obtenerRegistrosReportes()]);
    }

    public function registrarReporte()
    {
        $id_PreguntaReportada = $_POST['pregunta_id'] ?? null;
        $textoReporte =  $_POST['texto'] ?? null;
        $id_jugador = $_SESSION['usuario_loggeado']->id;


        $this->renderer->render('resumenPartida.mustache', [
            'puntaje' => $this->partidaModel->obtenerDatosPartida($_SESSION['usuario_loggeado']->id)['puntaje'] ?? null,
            'preguntas' => $this->partidaModel->obtenerPreguntasDeLaPartida($_SESSION['usuario_loggeado']->id) ?? null,
            'fecha' => $this->partidaModel->obtenerDatosPartida($_SESSION['usuario_loggeado']->id)['fecha'] ?? null,
            'mensaje_reporte' => $this->reportes_model->registrarReporte($id_PreguntaReportada, $textoReporte, $id_jugador) ?? null,
        ]);
    }

    public function descartar()
    {
        $id_reporte = $_POST['id_reporte'] ?? null;

        $this->reportes_model->borrarReporte($id_reporte);

        $this->renderer->render('reportes.mustache', ['reportes' => $this->reportes_model->obtenerRegistrosReportes()]);
    }
}
