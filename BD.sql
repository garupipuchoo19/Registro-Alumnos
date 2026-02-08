CREATE DATABASE IF NOT EXISTS escuela;
USE escuela;

-- Tabla de carreras
CREATE TABLE carreras (
    id_carrera INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    activa BOOLEAN DEFAULT TRUE
);

-- Tabla de turnos
CREATE TABLE turnos (
    id_turno INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    abreviatura VARCHAR(5) NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de grados
CREATE TABLE grados (
    id_grado INT AUTO_INCREMENT PRIMARY KEY,
    grado INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- Tabla de grupos
CREATE TABLE grupos (
    id_grupo INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) UNIQUE NOT NULL,
    id_carrera INT NOT NULL,
    id_grado INT NOT NULL,
    id_turno INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera),
    FOREIGN KEY (id_grado) REFERENCES grados(id_grado),
    FOREIGN KEY (id_turno) REFERENCES turnos(id_turno)
);

-- Tabla de alumnos
CREATE TABLE alumnos (
    id_alumno INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50),
    id_grupo INT NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_grupo) REFERENCES grupos(id_grupo)
);

-- Insertar datos iniciales
INSERT INTO carreras (nombre) VALUES 
('Sistemas'), ('Psicología'), ('Administración'), ('Contabilidad'),
('Derecho'), ('Medicina'), ('Arquitectura'), ('Ingeniería Civil'),
('Ingeniería Industrial'), ('Mercadotecnia'), ('Diseño Gráfico'),
('Enfermería'), ('Nutrición'), ('Odontología'), ('Pedagogía'),
('Turismo'), ('Gastronomía'), ('Comunicación'), ('Biología'),
('Química');

INSERT INTO turnos (nombre, abreviatura) VALUES 
('Matutino', 'M'),
('Vespertino', 'V'),
('Mixto', 'X');

INSERT INTO grados (grado) VALUES 
(1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11);
select * from  carreras;
select * from  grupos;
select * from alumnos;