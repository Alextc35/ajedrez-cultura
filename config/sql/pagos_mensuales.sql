CREATE TABLE pagos_mensuales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    mes ENUM('Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio') NOT NULL,
    año YEAR NOT NULL,
    pagado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id)
);