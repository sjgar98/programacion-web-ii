CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

INSERT INTO roles (nombre) VALUES
  ("admin"),
  ("editor"),
  ("jugador");

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  sexo VARCHAR(50) NOT NULL,
  pais VARCHAR(50) NOT NULL,
  ciudad VARCHAR(50) NOT NULL,
  username VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  avatar VARCHAR(255) NOT NULL,
  rol_id INT NOT NULL,
  FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  color VARCHAR(7) NOT NULL
);

INSERT INTO categorias (nombre, color) VALUES
  ("Artes", "#FF2330"),
  ("Ciencia", "#02E26D"),
  ("Deportes", "#FF9000"),
  ("Entretenimiento", "#FE53B6"),
  ("Geografía", "#0085EE"),
  ("Historia", "#FDE026");

CREATE TABLE preguntas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_edicion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  enunciado VARCHAR(255),
  categoria_id INT NOT NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

CREATE TABLE respuestas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pregunta_id INT NOT NULL,
  texto VARCHAR(255) NOT NULL,
  es_correcta TINYINT NOT NULL DEFAULT 0,
  FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE CASCADE
);

CREATE TABLE partidas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jugador_id INT NOT NULL,
  puntaje INT NOT NULL DEFAULT 0,
  completada TINYINT NOT NULL DEFAULT 0,
  FOREIGN KEY (jugador_id) REFERENCES usuarios(id)
);

CREATE TABLE preguntas_resueltas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  partida_id INT NOT NULL,
  jugador_id INT NOT NULL,
  pregunta_id INT NOT NULL,
  respuesta_id INT NOT NULL,
  FOREIGN KEY (partida_id) REFERENCES partidas(id) ON DELETE CASCADE,
  FOREIGN KEY (jugador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE CASCADE,
  FOREIGN KEY (respuesta_id) REFERENCES respuestas(id) ON DELETE CASCADE
);

CREATE TABLE reportes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  jugador_id INT NOT NULL,
  pregunta_id INT NOT NULL,
  texto VARCHAR(255) NOT NULL,
  resuelto TINYINT NOT NULL DEFAULT 0,
  FOREIGN KEY (jugador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  FOREIGN KEY (pregunta_id) REFERENCES preguntas(id) ON DELETE CASCADE
);
