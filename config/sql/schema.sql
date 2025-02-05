-- ============================================================
-- 📌 CREACIÓN DE BASE DE DATOS PARA CHESS LEAGUE
-- ============================================================

-- 📌 Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS chess_league;
USE chess_league;

-- 📌 Eliminar tabla alumnos si ya existe (para evitar errores en pruebas)
DROP TABLE IF EXISTS alumnos;

-- ============================================================
-- 📌 CREACIÓN DE TABLAS
-- ============================================================

-- 📌 Tabla: alumnos
CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identificador único
    nombre VARCHAR(100) NOT NULL,      -- Nombre del alumno
    liga ENUM('LIGA LOCAL', 'LIGA INFANTIL') NOT NULL DEFAULT 'LIGA LOCAL', -- Liga restringida
    victorias INT UNSIGNED NOT NULL DEFAULT 0, -- Número de victorias
    derrotas INT UNSIGNED NOT NULL DEFAULT 0,  -- Número de derrotas
    tablas INT UNSIGNED NOT NULL DEFAULT 0,    -- Número de tablas (empates)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha de inscripción
);

-- ============================================================
-- 📌 INSERCIÓN DE DATOS DE PRUEBA (Opcional)
-- ============================================================

INSERT INTO alumnos (nombre, liga, victorias, derrotas, tablas) VALUES
('Lucas Fernández', 'LIGA INFANTIL', 3, 2, 1),
('María Gómez', 'LIGA LOCAL', 5, 1, 2),
('Alejandro Pérez', 'LIGA LOCAL', 4, 2, 3),
('Daniel López', 'LIGA INFANTIL', 6, 0, 2),
('Sofía Ramírez', 'LIGA LOCAL', 3, 4, 1),
('Miguel Torres', 'LIGA INFANTIL', 4, 3, 2),
('Carla Ruiz', 'LIGA LOCAL', 2, 5, 0),
('Javier Navarro', 'LIGA INFANTIL', 5, 1, 3),
('Andrea Ortega', 'LIGA LOCAL', 6, 0, 2),
('Pablo Jiménez', 'LIGA INFANTIL', 3, 3, 2),
('Raúl Sánchez', 'LIGA LOCAL', 2, 4, 1),
('Elena Castillo', 'LIGA INFANTIL', 4, 2, 2),
('David Márquez', 'LIGA LOCAL', 5, 1, 1),
('Clara Domínguez', 'LIGA INFANTIL', 6, 0, 3),
('Tomás Herrera', 'LIGA LOCAL', 3, 3, 2),
('Isabel Medina', 'LIGA INFANTIL', 5, 1, 1),
('Hugo Vega', 'LIGA LOCAL', 4, 2, 2),
('Natalia Ríos', 'LIGA INFANTIL', 3, 3, 2),
('Cristian Guzmán', 'LIGA LOCAL', 5, 1, 1),
('Camila Muñoz', 'LIGA INFANTIL', 6, 0, 3),
('Fernando León', 'LIGA LOCAL', 4, 2, 2),
('Valeria Paredes', 'LIGA INFANTIL', 3, 3, 2),
('Martín Castro', 'LIGA LOCAL', 5, 1, 1),
('Sara Núñez', 'LIGA INFANTIL', 6, 0, 2);

-- ============================================================
-- 📌 COMPROBAR DATOS INSERTADOS
-- ============================================================

SELECT * FROM alumnos;

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