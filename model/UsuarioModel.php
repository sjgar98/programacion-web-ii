<?php

class UsuarioModel
{
    private Database $database;

    public function __construct(Database $database){
        $this->database = $database;
    }

    public function crearNuevoUsuario($nombre,$anio_nacimiento,$sexo,$pais,$ciudad,$email,$username,$password,$nombreFoto)
    {
        $contrasenia_hash = password_hash($password, PASSWORD_DEFAULT);

        $apellido_ficticio = $nombre;
        $rol_jugador = 3;

        $sql = "INSERT INTO usuarios (nombre, apellido, sexo, pais, ciudad, username, password_hash, email, avatar, rol_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $rol_jugador]);
    }

}
