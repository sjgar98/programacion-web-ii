<?php
class Pregunta
{
    private ?int $id;
    private ?\DateTime $fecha_creacion;
    private ?\DateTime $fecha_edicion;
    private string $enunciado;
    private ?int $categoria_id;

    public function __construct(int $id, ?\DateTime $fecha_creacion = null, ?\DateTime $fecha_edicion = null, string $enunciado, int $categoria_id)
    {
        $this->id = $id;
        $this->fecha_creacion = $fecha_creacion;
        $this->fecha_edicion = $fecha_edicion;
        $this->enunciado = $enunciado;
        $this->categoria_id = $categoria_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    public function getFechaEdicion()
    {
        return $this->fecha_edicion;
    }

    public function getEnunciado()
    {
        return $this->enunciado;
    }

    public function getCategoria()
    {
        return $this->categoria_id;
    }
}
