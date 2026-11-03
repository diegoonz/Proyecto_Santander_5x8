-- BASE DE DATOS: LA SALLE BUSINESS MATCH 2027

CREATE DATABASE IF NOT EXISTS LaSalleBusinessMatch;
USE LaSalleBusinessMatch;

-- 1. TABLA CENTROS

CREATE TABLE centros (
    id_centro INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    ciudad VARCHAR(80) NOT NULL,
    descripcion VARCHAR(255),
    web VARCHAR(255)
);

-- 2. TABLA EMPRESAS

CREATE TABLE empresas (
    id_empresa INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    sector VARCHAR(80),
    descripcion VARCHAR(255),
    web VARCHAR(255)
);

-- 3. TABLA EVENTOS

CREATE TABLE eventos (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descripcion VARCHAR(255),
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    duracion_minutos INT,
    id_empresa INT,
    FOREIGN KEY (id_empresa)
        REFERENCES empresas(id_empresa)
        ON DELETE SET NULL
);

-- DATOS DE EJEMPLO: CENTROS

INSERT INTO centros (nombre, ciudad, descripcion, web)
VALUES
('La Salle Santo Ángel', 'Zaragoza',
 'Centro educativo participante de ejemplo',
 'https://www.lasallesantoangel.es'),
('La Salle Gran Vía', 'Zaragoza',
 'Centro educativo de ejemplo',
 NULL),
('Centro Educativo Ejemplo', 'Madrid',
 'Centro participante ficticio para pruebas',
 NULL);

-- DATOS DE EJEMPLO: EMPRESAS

INSERT INTO empresas (nombre, sector, descripcion, web)
VALUES
('Tech Solutions', 'Tecnología',
 'Empresa de ejemplo dedicada al desarrollo de software',
 NULL),
('EcoFuture', 'Sostenibilidad',
 'Empresa ficticia de soluciones sostenibles',
 NULL),
('Digital Marketing', 'Marketing',
 'Empresa ficticia de servicios digitales',
 NULL);

-- DATOS DE EJEMPLO: EVENTOS

INSERT INTO eventos
(titulo, descripcion, fecha, hora, duracion_minutos, id_empresa)
VALUES
('Inauguración de la feria',
 'Presentación inicial de la feria virtual',
 '2027-03-15', '09:00:00', 30, NULL),

('Ponencia sobre tecnología',
 'Charla de ejemplo sobre innovación tecnológica',
 '2027-03-15', '10:00:00', 45, 1),

('Taller de marketing digital',
 'Actividad de ejemplo sobre marketing',
 '2027-03-15', '11:00:00', 60, 3);

-- CONSULTAS DE COMPROBACIÓN

-- Ver todos los centros
SELECT * FROM centros;

-- Ver todas las empresas
SELECT * FROM empresas;

-- Ver el programa de eventos ordenado por fecha y hora
SELECT
    eventos.titulo,
    eventos.fecha,
    eventos.hora,
    eventos.duracion_minutos,
    empresas.nombre AS empresa
FROM eventos
LEFT JOIN empresas
    ON eventos.id_empresa = empresas.id_empresa
ORDER BY eventos.fecha, eventos.hora;
