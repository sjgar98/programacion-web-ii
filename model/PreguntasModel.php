<?php

class PreguntasModel
{
    private Database $database;


    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllElementos()
    {
        $sql = "SELECT * FROM preguntas
                WHERE activa = 1";
        Log::info("SQL : $sql");
        return $this->database->query($sql);
    }
    public function crearNuevoElemento(string $nombre)
    {
        $sql = "INSERT INTO ejemplo (nombre) VALUES (?)";
        Log::info("SQL: $sql [$nombre]");
        return $this->database->execute($sql, [$nombre]);
    }

    public function agregarPregunta($enunciado, $categoria_id, bool $sugerida = false)
    {
        $sql = "INSERT INTO preguntas(enunciado, categoria_id, activa, sugerencia) VALUES (?,?,0,?)";
        Log::info("SQL: $sql");
        $this->database->execute($sql, [$enunciado, $categoria_id, $sugerida ? 1 : 0]);
        return $this->database->getLastInsertId();
    }

    public function modificarPregunta($id, $enunciado, $categoria_id)
    {
        $sql = "UPDATE preguntas 
        SET enunciado = ?, categoria_id = ?
        WHERE id = ?";
        Log::info("SQL : $sql: $id");
        return $this->database->execute($sql, [$enunciado, $categoria_id, $id]);
    }

    public function eliminarRespuestasPorPregunta($pregunta_id)
    {
        $sql = "DELETE FROM respuestas WHERE pregunta_id = ?";
        Log::info("SQL: $sql : $pregunta_id");
        return $this->database->execute($sql, [$pregunta_id]);
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
        return $this->database->execute($sql, [$pregunta_id, $texto, $es_correcta]);
    }

    public function getCategorias()
    {
        $sql = "SELECT * from categorias WHERE eliminada = 0";
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

    public function getRespuestasPorPreguntaId($id)
    {
        $sql = "SELECT * FROM respuestas WHERE pregunta_id = ? ORDER BY es_correcta DESC";
        return $this->database->query($sql, [$id]);
    }

    public function getPreguntasSugeridasPorUsuario()
    {
        $sql = "SELECT p.id, p.enunciado, c.nombre AS nombre_categoria 
            FROM preguntas p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.sugerencia = 1";
        return $this->database->query($sql);
    }

    public function aceptarPreguntaSugeridaPorUsuario($id)
    {
        $sql = "UPDATE preguntas
                SET sugerencia = 0, activa = 1
                WHERE id = ?";
        Log::info("SQL: $sql: $id");
        return $this->database->execute($sql,[$id]);
    }

    public function darDeBajaPreguntaSugeridaPorUsuario($id)
    {
        $sql = "DELETE FROM preguntas WHERE id = ?";
        Log::info("SQL: $sql: $id");
        return $this->database->execute($sql,[$id]);
    }

    public function crearNuevaCategoria(string $nombre, string $color)
    {
        $sql = "INSERT INTO categorias(nombre, color) VALUES (?, ?)";
        Log::info("SQL: $sql");
        return $this->database->execute($sql, [$nombre, $color]);
    }

    public function eliminarCategoria(int $id)
    {   
        $this->database->execute("UPDATE preguntas SET activa = 0 WHERE categoria_id = ?", [$id]);
        $this->database->execute("UPDATE categorias SET eliminada = 1 WHERE id = ?", [$id]);
    }

    public function getCategoriaPorId(int $id)
    {
        $sql = "SELECT * FROM categorias WHERE id = ?";
        Log::info("SQL: $sql con ID: $id");
        return $this->database->query($sql, [$id]);
    }

    public function modificarCategoria(int $id, string $nombre, string $color)
    {
        $sql = "UPDATE categorias SET nombre = ?, color = ? WHERE id = ?";
        Log::info("SQL: $sql con ID: $id");
        return $this->database->execute($sql, [$nombre, $color, $id]);
    }

}
