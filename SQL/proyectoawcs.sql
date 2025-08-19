CREATE DATABASE IF NOT EXISTS proyectoawcs;
USE proyectoawcs;
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    contrasena VARCHAR(255),
    rol VARCHAR(20),
    seccion VARCHAR(15),
    fecha_registro DATE,
    estado_usuario TINYINT(1)
);

CREATE TABLE recurso (
    id_recurso INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20), -- 'Libro', 'Computadora', 'Tableta'
    nombre VARCHAR(100),
    cantidad_disponible INT,
    ruta_imagen VARCHAR(400)
);

CREATE TABLE genero (
    id_genero INT AUTO_INCREMENT PRIMARY KEY,
    tipo_genero VARCHAR(100)
);

CREATE TABLE autor (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100)
);

CREATE TABLE detalle_libro (
    id_recurso INT,
    id_autor INT,
    id_genero INT,
    PRIMARY KEY(id_recurso),
    FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso),
    FOREIGN KEY (id_autor) REFERENCES autor(id_autor),
    FOREIGN KEY (id_genero) REFERENCES genero(id_genero)
);

CREATE TABLE prestamo (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_recurso INT,
    id_usuario INT,
    fecha_prestamo DATE,
    fecha_devolucion DATE,
    estado VARCHAR(60),
    FOREIGN KEY (id_recurso) REFERENCES recurso(id_recurso),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);