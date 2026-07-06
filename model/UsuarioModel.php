<?php

class UsuarioModel
{
    private Database $database;
    private Mailer $mailer;
    private Renderer $renderer;

    public function __construct(Database $database, Mailer $mailer, Renderer $renderer)
    {
        $this->database = $database;
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function crearNuevoUsuario(string $nombre, string $apellido, string $anio_nacimiento, string $sexo, string $pais, string $ciudad, string $email, string $username, string $password, string $nombreFoto, string $token, string $latitud, string $longitud): void
    {
        $contrasenia_hash = password_hash($password, PASSWORD_DEFAULT);
        $rol_jugador = 3;

        $sql = "INSERT INTO usuarios (nombre, apellido, anio_nacimiento, sexo, pais, ciudad, username, password_hash, email, avatar, rol_id,token_validacion,latitud, longitud) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Log::info("SQL: $sql");
        $this->database->execute($sql, [
            $nombre,
            $apellido,
            $anio_nacimiento,
            $sexo,
            $pais,
            $ciudad,
            $username,
            $contrasenia_hash,
            $email,
            $nombreFoto,
            $rol_jugador,
            $token,
            $latitud,
            $longitud
        ]);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->mailer->sendEmail(
                $email,
                "Preguntados - Registro exitoso",
                $this->renderer->render(
                    "registroExitosoEmail.mustache",
                    [
                        "token" => $token
                    ],
                    false
                )
            );
        }
        $this->crearTrampasUsuario($this->buscarUltimoUsuario());
        //no me acuerdo si esto estaba bien o mal? pero x ahora lo dejo jeje
    }

    public function buscarUsuarioPorToken($token)
    {
        $sql = "SELECT id FROM usuarios WHERE token_validacion = ?";
        $resultado = $this->database->query($sql, [$token]);

        if (!empty($resultado) && isset($resultado[0]['id'])) {
            return $resultado[0]['id'];
        }

        return null;
    }

    public function activarUsuario($id)
    {
        $sql = "UPDATE usuarios SET activo = 1, token_validacion = NULL WHERE id = ?";
        return $this->database->execute($sql, [$id]);
    }

    public function buscarUsuarioPorNombre(string $username): ?stdClass
    {
        $sql = "SELECT * FROM usuarios WHERE username = ?";
        $resultado = $this->database->query($sql, [$username], true);
        return count($resultado) > 0 ? $resultado[0] : null;
    }

    public function validarLogin(stdClass $usuario, string $password): bool
    {
        return password_verify($password, $usuario->password_hash);
    }

    public function buscarUltimoUsuario()
    {
        $sql = "SELECT id FROM usuarios ORDER BY id DESC LIMIT 1";
        $resultado = $this->database->query($sql);
        Log::info("USUARIO ID" . $resultado[0]['id']);
        if (!empty($resultado) && isset($resultado[0]['id'])) {
            return $resultado[0]['id'];
        }

        return null;
    }

    public function crearTrampasUsuario($usuario)
    {
        $sql = "INSERT INTO trampas (cantidad,jugador_id) VALUES (?,?)";
        $this->database->execute($sql, [0, $usuario]);
    }
}
