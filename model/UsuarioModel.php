<?php

class UsuarioModel
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function crearNuevoUsuario($nombre, $apellido, $anio_nacimiento, $sexo, $pais, $ciudad, $email, $username, $password, $nombreFoto, $token, $latitud, $longitud)
    {
        $contrasenia_hash = password_hash($password, PASSWORD_DEFAULT);
        $rol_jugador = 3;

        $sql = "INSERT INTO usuarios (nombre, apellido, anio_nacimiento,sexo, pais, ciudad, username, password_hash, email, avatar, rol_id,token_validacion,latitud, longitud) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)";
        Log::info("SQL: $sql");
        return $this->database->execute($sql, [
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
}
