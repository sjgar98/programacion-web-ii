<?php

class AuthService
{
    private UsuarioModel $model;

    public function __construct(UsuarioModel $model)
    {
        $this->model = $model;
    }

    public function validarLogin(string $username, string $password): bool
    {
        $usuario = $this->model->buscarPorUsuario($username);

        if (!$usuario) {
            return false;
        }

        return password_verify($password, $usuario['password_hash']);
    }

    public function comprarPasswords(string $pass, string $repeat_pass): bool
    {
        if ($pass === $repeat_pass) {
            return true;
        }
        return false;
    }
}
