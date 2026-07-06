<?php

class AdminController
{
  private AdminModel $model;
  private Renderer $renderer;
  private Request $request;

  public function __construct(AdminModel $model, Renderer $renderer, Request $request)
  {
    $this->model = $model;
    $this->renderer = $renderer;
    $this->request = $request;
  }

  public function usuarios()
  {
    Auth::puedeAccederAdmin();
    $usuarios = $this->model->obtenerUsuarios();
    $this->renderer->render("verAdminUsuarios.mustache", ["usuarios" => $usuarios]);
  }

  public function estadisticas()
  {
    Auth::puedeAccederAdmin();
    $estadisticas = $this->model->obtenerEstadisticas($this->request);
    $this->renderer->render("verAdminEstadisticas.mustache", ["estadisticas" => $estadisticas]);
  }

  public function estadisticasPdf()
  {
    Auth::puedeAccederAdmin();
    $estadisticas = $this->model->obtenerEstadisticas($this->request);
    $html = $this->renderer->render("verAdminEstadisticas.mustache", ["estadisticas" => $estadisticas], false);
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output("estadisticas.pdf", \Mpdf\Output\Destination::INLINE);
  }
}
