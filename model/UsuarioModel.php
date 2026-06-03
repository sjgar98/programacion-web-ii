<?php

class UsuarioModel
{
    private Database $database;

    public function __construct(Database $database){
        $this->database = $database;
    }

    public function crearNuevoUsuario($nombre,$anio_nacimiento,$sexo,$pais,$ciudad,$email,$username,$password,$nombreFoto,$token,$latitud,$longitud)
    {
        $contrasenia_hash = password_hash($password, PASSWORD_DEFAULT);

        $apellido_ficticio = $nombre;
        $rol_jugador = 3;

        $sql = "INSERT INTO usuarios (nombre, apellido, sexo, pais, ciudad, username, password_hash, email, avatar, rol_id,token_validacion,latitud, longitud) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
        Log::info("SQL: $sql");
        return $this->database->execute($sql,[$nombre,
            $apellido_ficticio,
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
            $longitud]);
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

}
