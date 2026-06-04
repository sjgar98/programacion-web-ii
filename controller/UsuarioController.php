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
        $anios = [];
        for ($i = 2026; $i >= 1930; $i--) {
            $anios[] = ['anio' => $i];
        }

        $this->renderer->render("registro.mustache", [
            "anios" => $anios,
            "ocultar_header" => true,
            "estilos_especificos" => array('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', 'https://www.w3schools.com/w3css/4/w3.css')
        ]);
    }

    public function procesar()
    {
        $nombre = $this->request->post('nombre');
        $apellido = $this->request->post('apellido');
        $anio_nacimiento = $this->request->post('anio_nacimiento');
        $sexo = $this->request->post('sexo');
        $pais = $this->request->post('pais');
        $ciudad = $this->request->post('ciudad');
        $email = $this->request->post('email');
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $password_repeat = $this->request->post('password_repeat');
        $nombreFoto = $this->imageService->procesar_imagen($_FILES['avatar']);
        $latitud = $this->request->post('latitud');
        $longitud = $this->request->post('longitud');

        if (empty($username) || empty($email) || empty($password) || empty($apellido)) {
            throw new InvalidArgumentException("Faltan campos obligatorios");
        }

        if($password != $password_repeat){
            throw new InvalidArgumentException("Las contraseñas no coinciden");
        }

        if ($nombreFoto === null) {
            $nombreFoto = "default.webp";
        }

        $token = bin2hex(random_bytes(16));
        $this->model->crearNuevoUsuario($nombre, $apellido,$anio_nacimiento,$sexo, $pais, $ciudad, $email, $username, $password, $nombreFoto, $token,$latitud,$longitud);

        $this->renderer->render("registroExitoso.mustache", [
            "token" => $token
        ]);
    }

    public function validar(){
        $token = $this->request->get('token');

        if(empty($token)){
            throw new InvalidArgumentException("Token de validación no suministrado");
        }

        $usuario_id = $this->model->buscarUsuarioPorToken($token);

        if ($usuario_id === null) {
            throw new InvalidArgumentException("El enlace de validación es inválido o ya expiró.");
        }

        $this->model->activarUsuario($usuario_id);

        Redirect::toIndex();
    }


}
