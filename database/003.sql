ALTER TABLE partidas
ADD COLUMN oponente_id INT NULL,
ADD FOREIGN KEY (oponente_id) REFERENCES usuarios(id);