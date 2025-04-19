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