<?php

class LoginController
{
    private UsuarioModel $model;
    private Renderer $renderer;
    private Request $request;

    public function __construct(UsuarioModel $model, Renderer $renderer, Request $request)
    {
        $this->model = $model;
        $this->renderer = $renderer;
        $this->request = $request;
    }

    public function procesarLogin()
    {
        $username = $this->request->post('username');
        $password = $this->request->post('pass');

        $usuario = $this->model->buscarUsuarioPorNombre($username);
        if ($usuario && $this->model->validarLogin($usuario, $password)) {
            $_SESSION['usuario_loggeado'] = $usuario;
            header("Location: http://preguntados.local/lobby/ver");
        } else {
            $this->renderer->render("login", ["error" => "Usuario o clave incorrectos"]);
        }
    }

    public function ver()
    {
        Log::info("LoginController::ver");
        $this->renderer->render("login", []);
    }
}
