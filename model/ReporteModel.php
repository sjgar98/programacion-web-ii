<?php

class ReporteModel
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function listarReportesPendientes()
    {
        $sql = "SELECT * from reportes
                INNER JOIN preguntas ON reportes.pregunta_id = preguntas.id
                WHERE resuelto = 0";
        Log::info("SQL: $sql");
        return $this->database->query($sql);
    }

    public function cambiarEstadoReporte($id)
    {
        $sql = "UPDATE reportes
        SET resuelto = 1
        WHERE id = ?";
        Log::info("SQL: $sql: $id");
        return $this->database->execute($sql, [$id]);
    }

}
