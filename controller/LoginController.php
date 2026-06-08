<?php

class LoginController
{
    private AuthService $authService;
    private Renderer $renderer;
    private Redirect $redirect;
    private Request $request;

    public function __construct(AuthService $authService, Renderer $renderer, Redirect $redirect, Request $request)
    {
        $this->authService = $authService;
        $this->renderer = $renderer;
        $this->redirect = $redirect;
        $this->request = $request;
    }

    public function procesarLogin()
    {


        $username = $this->request->post('username');
        $password = $this->request->post('pass');


        if ($this->authService->validarLogin($username, $password)) {
            //$_SESSION['usuario'] = $username;
            $this->renderer->render("home", []);
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
