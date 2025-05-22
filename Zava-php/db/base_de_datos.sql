CREATE DATABASE Zava;
USE Zava;

-- Tablas de la base de datos Zava
CREATE TABLE Roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL
);

CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    nickname VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    contrasenia VARCHAR(100) NOT NULL,
    rol INT, FOREIGN KEY (rol) REFERENCES Roles(id_rol),
    foto VARCHAR(255) DEFAULT 'perfil.png'
);

CREATE TABLE Usuarios_Baneados ( -- En caso de unban, se borra el registro
    id_baneado INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    nickname VARCHAR(50), FOREIGN KEY (nickname) REFERENCES Usuarios(nickname),
    correo VARCHAR(100), FOREIGN KEY (correo) REFERENCES Usuarios(correo),
    motivo TEXT NOT NULL,
    fecha_baneo DATETIME NOT NULL
);

CREATE TABLE Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    favoritos ENUM('si', 'no') NOT NULL DEFAULT 'no',
    stock INT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'default.png'
);

CREATE TABLE Favoritos_Productos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT, FOREIGN KEY (id_producto) REFERENCES Productos(id_producto),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Recetas (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    pasos TEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'receta.png',
    tiempo_preparacion TIME NOT NULL,
    tipo_dieta ENUM('vegano', 'vegetariano', 'sin_lactosa') NOT NULL,
    tipo_comida ENUM('desayuno', 'almuerzo', 'cena', 'snack', 'merienda', 'eventos especiales') NOT NULL,
    porciones INT NOT NULL,
    favoritos ENUM('si', 'no') NOT NULL DEFAULT 'no',
    hechas ENUM('si', 'no') NOT NULL DEFAULT 'no'
);

CREATE TABLE Comentarios_Recetas (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT, FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    comentario TEXT NOT NULL
);

CREATE TABLE Favoritos_Recetas (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT, FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Recetas_Hechas (
    id_receta_hecha INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT, FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Restaurantes (
    id_restaurante INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    nombre VARCHAR(100) NOT NULL,
    promedio_calificacion DECIMAL(2, 1) NOT NULL,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    imagen VARCHAR(255) DEFAULT 'restaurante.png'
);

CREATE TABLE Comentarios_Restaurantes (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT, FOREIGN KEY (id_restaurante) REFERENCES Restaurantes(id_restaurante),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    comentario TEXT NOT NULL
);

CREATE TABLE Reseñas_Restaurantes (
    id_resena INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT, FOREIGN KEY (id_restaurante) REFERENCES Restaurantes(id_restaurante),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    calificacion DECIMAL(2, 1) NOT NULL
);

CREATE TABLE Favoritos_Restaurantes (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT, FOREIGN KEY (id_restaurante) REFERENCES Restaurantes(id_restaurante),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);