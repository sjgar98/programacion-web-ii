<?php

class TrampasModel
{
    private Database $database;


    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function verTrampasJugador($jugadorId)
    {
        $sql = "SELECT * FROM trampas WHERE jugador_id = ?";
        Log::info("SQL: $sql");;
        return $this->database->query($sql, [$jugadorId]);
    }
}
