<?php

class PartidaModel
{
    private Database $database;
    private $maximoPreguntas;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->buscarMaximoPreguntas();
    }

    private function calcularDificultadPregunta(int $jugadorId, int $preguntaId): string
    {
        $stats = $this->obtenerEstadisticasPregunta($jugadorId, $preguntaId);

        $apariciones = $stats['total_apariciones'] ?? 0;
        $aciertos = $stats['total_aciertos'] ?? 0;

        $tasaAcierto = ($apariciones > 0) ? ($aciertos / $apariciones) : 0.5;

        if ($tasaAcierto < 0.3) {
            return 'dificil';
        } elseif ($tasaAcierto > 0.7) {
            return 'facil';
        }

        return 'media';
    }

    public function buscarPreguntaAleatoria(string $dificultadDeseada = 'media')
    {
        if ($this->maximoPreguntas <= 0) return null;

        $preguntaEncontrada = null;
        $intentos = 0;
        $maxIntentos = 10;
        $jugadorId = 1;

        do {
            $numAleatorio = rand(0, $this->maximoPreguntas - 1);
            $sql = "SELECT * FROM preguntas LIMIT 1 OFFSET ?";
            $resultados = $this->database->query($sql, [$numAleatorio], true);

            if (!empty($resultados)) {
                $preguntaFiltrada = $resultados[0];
                $dificultadActual = $this->calcularDificultadPregunta($jugadorId, $preguntaFiltrada->id);

                if ($dificultadActual === $dificultadDeseada) {
                    $preguntaEncontrada = $preguntaFiltrada;
                }
            }
            $intentos++;
        } while ($preguntaEncontrada === null && $intentos < $maxIntentos);

        if ($preguntaEncontrada === null && !empty($resultados)) {
            $preguntaEncontrada = $resultados[0];
            Log::info("No se encontró dificultad exacta: " . $preguntaEncontrada->id);
        }

        if ($preguntaEncontrada) {
            Log::info("Pregunta final obtenida: " . $preguntaEncontrada->enunciado);
            return $preguntaEncontrada;
        }

        return null;
    }

    public function obtenerRespuestasPorPregunta($idPregunta)
    {
        $sql = "SELECT * FROM respuestas WHERE pregunta_id = ?";
        return $this->database->query($sql, [$idPregunta], true);
    }

    public function establecerPartida()
    {
        if (empty($_SESSION['id_partida'])) {
            $sql = "INSERT INTO partidas (jugador_id, puntaje, completada) VALUES (?, 0, 0)";
            $this->database->execute($sql, [1]);

            $sqlId = "SELECT LAST_INSERT_ID() as id";
            $resultadoId = $this->database->query($sqlId);
            $_SESSION['id_partida'] = (int)$resultadoId[0]['id'];
        }
        if (empty($_SESSION['pregunta'])) {
            $pregunta = $this->buscarPreguntaAleatoria('media');
            $_SESSION['pregunta'] = $pregunta;
            $_SESSION['respuesta'] = $this->obtenerRespuestasPorPregunta($pregunta->id);
            $_SESSION['tiempo_limite'] = time() + 60;
        } elseif (!isset($_SESSION['tiempo_limite'])) {
            $_SESSION['tiempo_limite'] = time() + 60;
        }
    }

    public function reestabelecerPartida($respondioBien, $idRespuestaElegida)
    {
        if (empty($_SESSION['pregunta']) || empty($_SESSION['id_partida'])) {
            return;
        }

        $idPregunta = $_SESSION['pregunta']->id;
        $idPartida = $_SESSION['id_partida'];
        $idJugador = 1;


        if ($idRespuestaElegida !== null) {
            $sqlPreguntas_Resueltas = "INSERT INTO preguntas_resueltas (partida_id, jugador_id, pregunta_id, respuesta_id) VALUES (?, ?, ?, ?)";
            $this->database->execute($sqlPreguntas_Resueltas, [$idPartida, $idJugador, $idPregunta, $idRespuestaElegida]);
        }

        if ($respondioBien) {
            $sqlPuntaje = "UPDATE partidas SET puntaje = puntaje + 10 WHERE id = ?";
            $this->database->execute($sqlPuntaje, [$idPartida]);
        } else {
            $sqlCierre = "UPDATE partidas SET completada = 1 WHERE id = ?";
            $this->database->execute($sqlCierre, [$idPartida]);
            unset($_SESSION['id_partida']);
        }

        unset($_SESSION['pregunta']);
        unset($_SESSION['respuesta']);

        unset($_SESSION['tiempo_limite']);
    }

    public function buscarMaximoPreguntas()
    {
        $sql = "SELECT COUNT(*) as total FROM preguntas";
        $resultado = $this->database->query($sql);
        $this->maximoPreguntas = (int)$resultado[0]['total'];
    }

    public function obtenerRespuestaPorId(int $id_respuesta)
    {
        $sql = "SELECT es_correcta FROM respuestas WHERE id = ?";
        $resultado = $this->database->query($sql, [$id_respuesta]);
        return $resultado[0] ?? null;
    }

    public function obtenerEstadisticasPregunta($jugador_id, $pregunta_id)
    {
        $sql = "SELECT 
                COUNT(*) as total_apariciones,
                SUM(CASE WHEN r.es_correcta = 1 THEN 1 ELSE 0 END) as total_aciertos
            FROM preguntas_resueltas pr
            JOIN respuestas r ON pr.respuesta_id = r.id
            WHERE pr.jugador_id = ? AND pr.pregunta_id = ?";

        $resultado = $this->database->query($sql, [$jugador_id, $pregunta_id]);
        return !empty($resultado) ? $resultado[0] : null;
    }

    public function getAllElementos()
    {
        $sql = "SELECT * FROM usuarios";
        return $this->database->query($sql);
    }
}
