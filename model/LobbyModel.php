<?php

class LobbyModel
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function obtenerDatosUsuario($id)
    {
        $obtenerDatosSql = "SELECT id, nombre, apellido FROM usuarios WHERE id = ?";
        Log::info("SQL: $obtenerDatosSql");
        return $this->database->query($obtenerDatosSql,[$id]);
    }

    public function obtenerHistorialPartidas($usuario_id)
    {
        $obtenerHistorialSql = 
            "SELECT *, DATE_FORMAT(fecha, '%d/%m/%y %H:%i') as fecha_formatted
            FROM partidas
            WHERE jugador_id = ?
            ORDER BY fecha DESC
            LIMIT 10;";
        Log::info("SQL: $obtenerHistorialSql");
        return $this->database->query($obtenerHistorialSql,[$usuario_id]);
    }

    public function obtenerPuntajeRanking($usuario_id)
    {
        $obtenerPuntaje = "SELECT SUM(puntaje) AS puntaje_total FROM partidas WHERE jugador_id = ?";
        Log::info("SQL: $obtenerPuntaje");
        return $this->database->query($obtenerPuntaje,[$usuario_id]);
    }

}
