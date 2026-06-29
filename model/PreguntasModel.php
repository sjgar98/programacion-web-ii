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
        $sql = "SELECT * FROM preguntas
                WHERE activa = 1";
        Log::info("SQL : $sql");
        return $this->database->query($sql);
    }

    public function getPreguntaId()
    {

        $this->random->random();
        $id = $this->random->getRandom();
        $sql = "SELECT * FROM preguntas WHERE id = ? AND activa = 1";
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

    public function agregarPregunta($enunciado, $categoria_id)
    {
        $sql = "INSERT INTO preguntas(enunciado, categoria_id) VALUES (?,?)";
        Log::info("SQL: $sql");
        return $this->database->execute($sql,[$enunciado, $categoria_id]);
    }

    public function modificarPregunta($id,$enunciado, $categoria_id)
    {
        $sql = "UPDATE preguntas 
        SET enunciado = ?, categoria_id = ?
        WHERE id = ?";
        Log::info("SQL : $sql: $id");
        return $this->database->execute($sql, [$enunciado, $categoria_id,$id]);
    }

    public function eliminarRespuestasPorPregunta($pregunta_id)
    {
        $sql = "DELETE FROM respuestas WHERE pregunta_id = ?";
        Log::info("SQL: $sql : $pregunta_id");
        return $this->database->execute($sql,[$pregunta_id]);
    }

    public function darDeBajaPregunta($id)
    {
        $sql = "UPDATE preguntas
        SET activa = 0
        WHERE id = ?";
        Log::info("SQL: $sql: $id");
        return $this->database->execute($sql, [$id]);
    }

    public function agregarRespuesta($pregunta_id, $texto, $es_correcta)
    {
        $sql = "INSERT INTO respuestas(pregunta_id,texto,es_correcta) VALUES (?,?,?)";
        Log::info("SQL: $sql");
        return $this->database->execute($sql,[$pregunta_id, $texto, $es_correcta]);
    }

    public function getCategorias()
    {
        $sql = "SELECT * from categorias";
        Log::info("SQL: $sql");;
        return $this->database->query($sql);
    }

    public function getPreguntaPorId($id)
    {
        $sql = "SELECT * FROM preguntas WHERE id = ? AND activa = 1";
        Log::info("SQL: $sql con ID: $id");
        return $this->database->query($sql, [$id]);
    }

    public function getPreguntasConCategoria()
    {
        $sql = "SELECT p.id, p.enunciado, p.activa, c.nombre AS nombre_categoria 
                FROM preguntas p
                JOIN categorias c ON p.categoria_id = c.id
                WHERE p.activa = 1";
        Log::info("SQL: $sql");
        return $this->database->query($sql);
    }

}
