<?php

class UsuarioController
{
    private UsuarioModel $model;
    private Renderer $renderer;
    private Request $request;
    private ImageService $imageService;

    public function __construct(UsuarioModel $model, Renderer $renderer, Request $request,ImageService $imageService)
    {
        $this->model = $model;
        $this->renderer = $renderer;
        $this->request = $request;
        $this->imageService = $imageService;
    }


    public function ver()
    {
        Log::info("UsuarioController::ver");
        $this->renderer->render("registro.mustache",[]);
    }

    public function procesar()
    {
        $nombre = $this->request->post('nombre');
        $anio_nacimiento = $this->request->post('anio_nacimiento');
        $sexo = $this->request->post('sexo');
        $pais = $this->request->post('pais');
        $ciudad = $this->request->post('ciudad');
        $email = $this->request->post('email');
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $password_repeat = $this->request->post('password_repeat');
        $nombreFoto = $this->imageService->procesar_imagen($_FILES['avatar']);

        if (empty($username) || empty($email) || empty($password)) {
            throw new InvalidArgumentException("Faltan campos obligatorios");
        }

        if($password != $password_repeat){
            throw new InvalidArgumentException("Las contraseñas no coinciden");
        }

        if ($nombreFoto === null) {
            $nombreFoto = "default.png";
        }

        Log::info("");
        $this->model->crearNuevoUsuario($nombre, $anio_nacimiento, $sexo,$pais,$ciudad,$email,$username,$password,$nombreFoto);
        Redirect::toIndex();

    }


}
