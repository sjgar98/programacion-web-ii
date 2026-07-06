<?php

class RespuestasModel
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllElementos()
    {
        $sql = "SELECT * FROM usuarios";
        Log::info("SQL : $sql");
        return $this->database->query($sql);
    }

    public function crearNuevoElemento(string $nombre)
    {
        $sql = "INSERT INTO ejemplo (nombre) VALUES (?)";
        Log::info("SQL: $sql [$nombre]");
        return $this->database->execute($sql, [$nombre]);
    }
}
