<?php

class Random
{

    private Database $database;
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    private int $random;

    private function obtenerMaximoPreguntas(): int
    {

        $sql = "SELECT COUNT(*) AS total_filas FROM preguntas";
        Log::info("SQL : $sql");
        return $this->database->query($sql)[0];
    }
    public function random()
    {
        $this->random = rand(1, 25);
    }

    public function getRandom()
    {
        return $this->random;
    }
}
