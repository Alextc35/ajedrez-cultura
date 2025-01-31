CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria ENUM('LIGA LOCAL', 'LIGA INFANTIL') NOT NULL,
    victorias INT DEFAULT 0,
    derrotas INT DEFAULT 0,
    tablas INT DEFAULT 0
);
