INSERT INTO preguntas (id, enunciado, categoria_id) VALUES
-- Categoría: Artes 
(1, '¿Quién pintó la Mona Lisa?', 1),
(2, '¿En qué museo se encuentra el Guernica de Pablo Picasso?', 1),
(3, '¿Qué artista es conocido por sus obras de latas de sopa Campbell?', 1),
(4, '¿Quién esculpió el famoso "David"?', 1),
(5, '¿Cuál de estas obras pertenece a Salvador Dalí?', 1),

-- Categoría: Ciencia 
(6, '¿Cuál es el planeta más grande de nuestro sistema solar?', 2),
(7, '¿Qué elemento químico tiene el símbolo "O"?', 2),
(8, '¿Quién formuló la teoría de la relatividad?', 2),
(9, '¿Cuál es el hueso más largo del cuerpo humano?', 2),
(10, '¿Cuál es el gas más abundante en la atmósfera terrestre?', 2),

-- Categoría: Deportes 
(11, '¿Qué equipo argentino tiene más Copas Libertadores ganadas?', 3),
(12, '¿En qué estadio hace de local Boca Juniors?', 3),
(13, '¿A qué club se lo conoce popularmente como "El Millonario"?', 3),
(14, '¿Quién es el máximo goleador histórico de la Selección Argentina?', 3),
(15, '¿Qué equipo ganó el Apertura 2001, cortando una racha de 35 años sin títulos locales?', 3),

-- Categoría: 
(16, '¿Cuál es el río más largo del mundo?', 5),
(17, '¿En qué continente se encuentra Egipto?', 5),
(18, '¿Cuál es el país más grande del mundo por superficie?', 5),
(19, '¿Cuál es la capital de Australia?', 5),
(20, '¿Qué extensa cordillera atraviesa la región occidental de América del Sur?', 5),

-- Categoría: 
(21, '¿En qué año llegó Cristóbal Colón a América?', 6),
(22, '¿Quién fue el primer presidente de los Estados Unidos?', 6),
(23, '¿En qué año comenzó la Segunda Guerra Mundial?', 6),
(24, '¿Qué civilización originaria construyó Machu Picchu?', 6),
(25, '¿Quién fue el líder principal de la Revolución Rusa de 1917?', 6);


INSERT INTO respuestas (pregunta_id, texto, es_correcta) VALUES
-- Respuestas Artes
(1, 'Leonardo da Vinci', 1), (1, 'Pablo Picasso', 0), (1, 'Vincent van Gogh', 0), (1, 'Salvador Dalí', 0),
(2, 'Museo Reina Sofía', 1), (2, 'Museo del Prado', 0), (2, 'Museo del Louvre', 0), (2, 'MoMA de Nueva York', 0),
(3, 'Andy Warhol', 1), (3, 'Jackson Pollock', 0), (3, 'Claude Monet', 0), (3, 'Frida Kahlo', 0),
(4, 'Miguel Ángel', 1), (4, 'Donatello', 0), (4, 'Gian Lorenzo Bernini', 0), (4, 'Auguste Rodin', 0),
(5, 'La persistencia de la memoria', 1), (5, 'El grito', 0), (5, 'La noche estrellada', 0), (5, 'El beso', 0),

-- Respuestas Ciencia
(6, 'Júpiter', 1), (6, 'Saturno', 0), (6, 'La Tierra', 0), (6, 'Marte', 0),
(7, 'Oxígeno', 1), (7, 'Oro', 0), (7, 'Osmio', 0), (7, 'Oganesón', 0),
(8, 'Albert Einstein', 1), (8, 'Isaac Newton', 0), (8, 'Nikola Tesla', 0), (8, 'Galileo Galilei', 0),
(9, 'Fémur', 1), (9, 'Tibia', 0), (9, 'Peroné', 0), (9, 'Húmero', 0),
(10, 'Nitrógeno', 1), (10, 'Oxígeno', 0), (10, 'Dióxido de Carbono', 0), (10, 'Helio', 0),

-- Respuestas Deportes (Fútbol Argentino)
(11, 'Independiente', 1), (11, 'Boca Juniors', 0), (11, 'River Plate', 0), (11, 'Estudiantes de La Plata', 0),
(12, 'La Bombonera', 1), (12, 'El Monumental', 0), (12, 'El Cilindro de Avellaneda', 0), (12, 'El Nuevo Gasómetro', 0),
(13, 'River Plate', 1), (13, 'San Lorenzo', 0), (13, 'Racing Club', 0), (13, 'Vélez Sarsfield', 0),
(14, 'Lionel Messi', 1), (14, 'Gabriel Batistuta', 0), (14, 'Diego Maradona', 0), (14, 'Sergio Agüero', 0),
(15, 'Racing Club', 1), (15, 'San Lorenzo', 0), (15, 'Huracán', 0), (15, 'Newell''s Old Boys', 0),

-- Respuestas Geografía
(16, 'Amazonas', 1), (16, 'Nilo', 0), (16, 'Yangtsé', 0), (16, 'Misisipi', 0),
(17, 'África', 1), (17, 'Asia', 0), (17, 'Europa', 0), (17, 'Oceanía', 0),
(18, 'Rusia', 1), (18, 'Canadá', 0), (18, 'China', 0), (18, 'Estados Unidos', 0),
(19, 'Canberra', 1), (19, 'Sídney', 0), (19, 'Melbourne', 0), (19, 'Perth', 0),
(20, 'Los Andes', 1), (20, 'Los Alpes', 0), (20, 'El Himalaya', 0), (20, 'Las Montañas Rocosas', 0),

-- Respuestas Historia
(21, '1492', 1), (21, '1512', 0), (21, '1482', 0), (21, '1502', 0),
(22, 'George Washington', 1), (22, 'Abraham Lincoln', 0), (22, 'Thomas Jefferson', 0), (22, 'John Adams', 0),
(23, '1939', 1), (23, '1914', 0), (23, '1945', 0), (23, '1936', 0),
(24, 'El Imperio Inca', 1), (24, 'El Imperio Azteca', 0), (24, 'El Imperio Maya', 0), (24, 'El Imperio Romano', 0),
(25, 'Vladimir Lenin', 1), (25, 'Iósif Stalin', 0), (25, 'León Trotski', 0), (25, 'El Zar Nicolás II', 0);