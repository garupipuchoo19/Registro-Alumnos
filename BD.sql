-- Crear base de datos
CREATE DATABASE sistema_alumnos;

USE sistema_alumnos;

-- Tabla de carreras (catálogo)
CREATE TABLE carreras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    clave VARCHAR(10) NOT NULL UNIQUE,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de grados (catálogo)
CREATE TABLE grados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    nivel INT(20) NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de grupos
CREATE TABLE grupos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    carrera_id INT NOT NULL,
    grado_id INT NOT NULL,
    turno VARCHAR(20) NOT NULL,
    clave VARCHAR(20) UNIQUE NOT NULL,
    FOREIGN KEY (carrera_id) REFERENCES carreras(id),
    FOREIGN KEY (grado_id) REFERENCES grados(id)
);

-- Tabla de alumnos
CREATE TABLE alumnos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL,
    grupo_id INT NOT NULL,
    email VARCHAR(100),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);

-- Insertar datos iniciales para catálogos
INSERT INTO carreras (nombre, clave) VALUES 
('Sistemas', 'ISC'),
('Biología', 'BIO'),
('Administración', 'ADM');

INSERT INTO grados (nombre, nivel) VALUES 
('Primero', 'Grado'),
('Segundo', 'Grado'),
('Tercero', 'Grado'),
('Cuarto', 'Grado');