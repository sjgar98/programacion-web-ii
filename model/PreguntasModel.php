<?php

class PreguntasModel
{
    private Database $database;
    private Random $random;


    public function __construct(Database $database, Random $random)
    {
        $this->database = $database;
        $this->random = $random;
    }

    public function getAllElementos()
    {
        $sql = "SELECT * FROM preguntas";
        Log::info("SQL : $sql");
        return $this->database->query($sql);
    }

    public function getPreguntaId()
    {

        $this->random->random();
        $id = $this->random->getRandom();
        $sql = "SELECT * FROM preguntas WHERE id = ?";
        Log::info("SQL : $sql: $id");
        return $this->database->query($sql, [$id]);
    }

    public function getRespuestasPorPregunta()
    {
        $id = $this->random->getRandom();
        $sql = "SELECT * FROM respuestas WHERE pregunta_id =?";
        Log::info("SQL : $sql: $id");
        return $this->database->query($sql, [$id]);
    }
    public function crearNuevoElemento(string $nombre)
    {
        $sql = "INSERT INTO ejemplo (nombre) VALUES (?)";
        Log::info("SQL: $sql [$nombre]");
        return $this->database->execute($sql, [$nombre]);
    }
}
