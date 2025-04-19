-- ============================================================
-- 📌 CREACIÓN DE BASE DE DATOS PARA CHESS LEAGUE
-- ============================================================

-- 📌 Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS ajedrez_clase;
USE ajedrez_clase;

-- 📌 Eliminar tabla alumnos si ya existe (para evitar errores en pruebas)
DROP TABLE IF EXISTS alumnos;

-- ============================================================
-- 📌 CREACIÓN DE TABLAS
-- ============================================================

-- 📌 Tabla: alumnos
CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identificador único
    nombre VARCHAR(100) NOT NULL,      -- Nombre del alumno
    anio_nacimiento INT CHECK (anio_nacimiento BETWEEN 1925 AND 2025),
    liga ENUM('LIGA LOCAL', 'LIGA INFANTIL') NOT NULL DEFAULT 'LIGA LOCAL', -- Liga restringida
    victorias INT UNSIGNED NOT NULL DEFAULT 0, -- Número de victorias
    derrotas INT UNSIGNED NOT NULL DEFAULT 0,  -- Número de derrotas
    tablas INT UNSIGNED NOT NULL DEFAULT 0,    -- Número de tablas (empates)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha de inscripción
);

-- 📌 Tabla: usuarios
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT,
    usuario VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

INSERT INTO usuarios (usuario, password)
VALUES ('admin', 'admin');

-- ============================================================
-- 📌 INSERCIÓN DE DATOS DE PRUEBA (Opcional)
-- ============================================================

-- INSERT INTO alumnos (nombre, anio_nacimiento, liga, victorias, derrotas, tablas) VALUES
-- ('Lucas Fernández', 2013, 'LIGA INFANTIL', 3, 2, 1),
-- ('María Gómez', 2009, 'LIGA LOCAL', 5, 1, 2),
-- ('Alejandro Pérez', 2010, 'LIGA LOCAL', 4, 2, 3),
-- ('Daniel López', 2012, 'LIGA INFANTIL', 6, 0, 2),
-- ('Sofía Ramírez', 2008, 'LIGA LOCAL', 3, 4, 1),
-- ('Miguel Torres', 2014, 'LIGA INFANTIL', 4, 3, 2),
-- ('Carla Ruiz', 2011, 'LIGA LOCAL', 2, 5, 0),
-- ('Javier Navarro', 2013, 'LIGA INFANTIL', 5, 1, 3),
-- ('Andrea Ortega', 2009, 'LIGA LOCAL', 6, 0, 2),
-- ('Pablo Jiménez', 2012, 'LIGA INFANTIL', 3, 3, 2),
-- ('Raúl Sánchez', 2010, 'LIGA LOCAL', 2, 4, 1),
-- ('Elena Castillo', 2013, 'LIGA INFANTIL', 4, 2, 2),
-- ('David Márquez', 2009, 'LIGA LOCAL', 5, 1, 1),
-- ('Clara Domínguez', 2014, 'LIGA INFANTIL', 6, 0, 3),
-- ('Tomás Herrera', 2010, 'LIGA LOCAL', 3, 3, 2),
-- ('Isabel Medina', 2013, 'LIGA INFANTIL', 5, 1, 1),
-- ('Hugo Vega', 2011, 'LIGA LOCAL', 4, 2, 2),
-- ('Natalia Ríos', 2012, 'LIGA INFANTIL', 3, 3, 2),
-- ('Cristian Guzmán', 2008, 'LIGA LOCAL', 5, 1, 1),
-- ('Camila Muñoz', 2014, 'LIGA INFANTIL', 6, 0, 3),
-- ('Fernando León', 2009, 'LIGA LOCAL', 4, 2, 2),
-- ('Valeria Paredes', 2012, 'LIGA INFANTIL', 3, 3, 2),
-- ('Martín Castro', 2008, 'LIGA LOCAL', 5, 1, 1),
-- ('Sara Núñez', 2013, 'LIGA INFANTIL', 6, 0, 2);

-- ============================================================
-- 📌 COMPROBAR DATOS INSERTADOS
-- ============================================================

-- SELECT * FROM alumnos;

-- ============================================================
-- 📌 COMANDOS PARA IMPORTAR ESTE ARCHIVO SQL EN MYSQL
-- ============================================================

-- 📌 Opción 1: Usando MySQL desde la terminal (CMD o Bash)
-- Ejecutar este comando en la terminal dentro del directorio donde está `schema.sql`
-- mysql -u tu_usuario -p < schema.sql

-- 📌 Opción 2: Usando phpMyAdmin
-- 1️⃣ Abre phpMyAdmin y selecciona la base de datos `chess_league`
-- 2️⃣ Ve a la pestaña "Importar"
-- 3️⃣ Selecciona el archivo `schema.sql`
-- 4️⃣ Haz clic en "Ejecutar" y verifica los datos.
