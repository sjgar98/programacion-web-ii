<?php

class ReportesModel
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function registrarReporte($id_Pregunta, $texto, $id_Jugador)
    {
        $sql = "INSERT INTO reportes (jugador_id,pregunta_id,texto) VALUES(?,?,?)";
        $resultado = $this->database->execute($sql, [$id_Jugador, $id_Pregunta, $texto]);
        return !empty($resultado) ? true : false;
    }

    public function obtenerRegistrosReportes()
    {

        $sql = "SELECT r.id, u.username AS nombre_jugador, p.enunciado AS enunciado_pregunta, r.texto,  r.fecha 
        FROM reportes r
        JOIN usuarios u ON r.jugador_id = u.id
        JOIN preguntas p ON r.pregunta_id = p.id
        WHERE r.resuelto = 0
       ORDER BY r.fecha DESC";
        $resultado = $this->database->query($sql);
        Log::info("Reportes " .  $resultado[0]);
        return !empty($resultado) ? $resultado : null;
    }

    public function borrarReporte($id_reporte)
    {

        $sql = "DELETE FROM reportes WHERE id = ?";
        $this->database->execute($sql, [$id_reporte]);
    }
}
