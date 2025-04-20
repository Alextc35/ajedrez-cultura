-- TABLA usuarios
-- CREATE TABLE usuarios (
--     id INT UNSIGNED AUTO_INCREMENT,
--     usuario VARCHAR(20) NOT NULL UNIQUE,
--     password VARCHAR(255) NOT NULL,
--     PRIMARY KEY (id)
-- );
-- INSERT INTO usuarios (usuario, password) VALUES ('admin', 'admin');

-- TABLA logs_login
CREATE TABLE logs_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45),
    user_agent TEXT
);

-- TABLA alumnos
CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    anio_nacimiento YEAR NULL DEFAULT NULL,
    liga ENUM('Local', 'Infantil') NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- INSERT INTO alumnos (nombre, anio_nacimiento, liga) VALUES ('Lucas Martínez', 2010, 'Infantil'), ('María García', 2008, 'Local'), ('Carlos Pérez', 2011, 'Infantil'), ('Ana Torres', 2007, 'Local');

-- TABLA torneos
CREATE TABLE torneos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    liga ENUM('Local', 'Infantil') NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL
);
-- INSERT INTO torneos (nombre, liga, fecha_inicio, fecha_fin) VALUES ('Torneo Oct-Dic Infantil', 'Infantil', '2024-10-01', '2024-12-15'), ('Torneo Oct-Dic Local', 'Local', '2024-10-01', '2024-12-15'), ('Torneo Ene-Abr Infantil', 'Infantil', '2025-01-10', '2025-04-10'), ('Torneo Ene-Abr Local', 'Local', '2025-01-10', '2025-04-10');

-- TABLA enfrentamientos
CREATE TABLE enfrentamientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    torneo_id INT NOT NULL,
    alumno1_id INT NULL,
    alumno2_id INT NULL,
    resultado ENUM('tablas', 'blancas', 'negras') NOT NULL,
    fecha DATE NOT NULL,
    FOREIGN KEY (torneo_id) REFERENCES torneos(id),
    FOREIGN KEY (alumno1_id) REFERENCES alumnos(id),
    FOREIGN KEY (alumno2_id) REFERENCES alumnos(id)
);
-- INSERT INTO enfrentamientos (torneo_id, alumno1_id, alumno2_id, resultado, fecha) VALUES (1, 1, 3, 'blancas', '2024-10-12'), (2, 2, 4, 'tablas', '2024-10-13'), (1, 3, 1, 'negras', '2024-10-20'), (2, 4, 2, 'blancas', '2024-10-21');

-- TABLA pagos_mensuales
CREATE TABLE pagos_mensuales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    mes ENUM('Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio') NOT NULL,
    anio YEAR NOT NULL,
    pagado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id)
);
-- INSERT INTO pagos_mensuales (alumno_id, mes, anio, pagado) VALUES (1, 'Octubre', 2024, TRUE), (1, 'Noviembre', 2024, FALSE), (2, 'Octubre', 2024, TRUE), (3, 'Octubre', 2024, TRUE), (4, 'Octubre', 2024, FALSE);