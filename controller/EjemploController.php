<?php

class EjemploController
{
  private EjemploModel $model;
  private Renderer $renderer;
  private Request $request;

  public function __construct(EjemploModel $model, Renderer $renderer, Request $request)
  {
    $this->model    = $model;
    $this->renderer = $renderer;
    $this->request  = $request;
  }

  public function ver()
  {
    Log::info("EjemploController::ver");
    $this->renderer->render("verEjemploView", ['ejemplos' => $this->model->getAllElementos()]);
  }

  public function procesarAlta()
  {
    $nombre = $this->request->post('nombre');
    Log::info("VikingoController::procesarAlta - nombre=$nombre");
    $this->model->crearNuevoElemento($nombre);
    Redirect::toIndex();
  }
}
