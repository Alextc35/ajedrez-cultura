CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    anio_nacimiento YEAR NULL DEFAULT NULL,
    liga ENUM('Local', 'Infantil') NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO alumnos (nombre, anio_nacimiento, liga) VALUES
-- Liga Infantil
('Sofía Ramírez', 2011, 'Infantil'),
('Mateo López', 2012, 'Infantil'),
('Valentina Cruz', 2010, 'Infantil'),
('Sebastián Herrera', 2011, 'Infantil'),
('Isabella Rojas', 2010, 'Infantil'),
('Benjamín Díaz', 2012, 'Infantil'),
('Emma Castillo', 2009, 'Infantil'),
('Santiago Morales', 2011, 'Infantil'),
('Camila Ortega', 2010, 'Infantil'),
('Tomás Vargas', 2012, 'Infantil'),
('Martina Paredes', 2011, 'Infantil'),
('Lucas Romero', 2009, 'Infantil'),
('Antonia León', 2012, 'Infantil'),
('Gabriel Méndez', 2010, 'Infantil'),
('Julieta Navarro', 2011, 'Infantil'),

-- Liga Local
('Alejandro Gómez', 2007, 'Local'),
('Daniela Peña', 2006, 'Local'),
('Diego Navarro', 2008, 'Local'),
('Lucía Mendoza', 2007, 'Local'),
('Samuel Iglesias', 2006, 'Local'),
('Victoria Salinas', 2008, 'Local'),
('Emiliano Pino', 2007, 'Local'),
('Florencia Soto', 2006, 'Local'),
('Joaquín Palma', 2008, 'Local'),
('Renata Fuentes', 2007, 'Local'),
('Agustín Carrasco', 2006, 'Local'),
('Josefina Lagos', 2008, 'Local'),
('Maximiliano Vidal', 2007, 'Local'),
('Paula Riquelme', 2006, 'Local'),
('Facundo Espinoza', 2008, 'Local');
