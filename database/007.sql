--Agente 007 😎
CREATE TABLE trampas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL DEFAULT 0,
    jugador_id INT NOT NULL UNIQUE,
    FOREIGN KEY (jugador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

ALTER TABLE preguntas_resueltas
ADD COLUMN uso_trampita TINYINT NOT NULL DEFAULT 0;
