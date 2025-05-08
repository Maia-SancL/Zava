CREATE DATABASE Zava;

CREATE TABLE Usuarios (
    id_usuario PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    nickname VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    contrasenia VARCHAR(100) NOT NULL,
    rol FOREIGN KEY REFERENCES Roles(id_rol),
    foto VARCHAR(255) DEFAULT 'perfil.png',
);

CREATE TABLE Roles (
    id_rol PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL
);

CREATE TABLE Usuarios_Baneados ( -- En caso de unban, se borra el registro
    id_baneado PRIMARY KEY AUTO_INCREMENT,
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario),
    nickname FOREIGN KEY REFERENCES Usuarios(nickname),
    correo FOREIGN KEY REFERENCES Usuarios(correo),
    motivo TEXT NOT NULL,
    fecha_baneo DATE NOT NULL,
)

CREATE TABLE Productos (
    id_producto PRIMARY KEY AUTO_INCREMENT,
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario),
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    favoritos ENUM('si', 'no') NOT NULL DEFAULT 'no',
    stock INT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'default.png'
);

CREATE TABLE Favoritos_Productos (
    id_favorito PRIMARY KEY AUTO_INCREMENT,
    id_producto FOREIGN KEY REFERENCES Productos(id_producto),
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Recetas (
    id_receta PRIMARY KEY AUTO_INCREMENT,
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario),
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
    id_comentario PRIMARY KEY AUTO_INCREMENT,
    id_receta FOREIGN KEY REFERENCES Recetas(id_receta),
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario),
    comentario TEXT NOT NULL
);

CREATE TABLE Favoritos_Recetas (
    id_favorito PRIMARY KEY AUTO_INCREMENT,
    id_receta FOREIGN KEY REFERENCES Recetas(id_receta),
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Recetas_Hechas (
    id_receta_hecha PRIMARY KEY AUTO_INCREMENT,
    id_receta FOREIGN KEY REFERENCES Recetas(id_receta),
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Restaurantes (
    id_restaurante PRIMARY KEY AUTO_INCREMENT,
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario),
    nombre VARCHAR(100) NOT NULL,
    promedio_calificacion DECIMAL(2, 1) NOT NULL,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    imagen VARCHAR(255) DEFAULT 'restaurante.png'
);

CREATE TABLE Comentarios_Restaurantes (
    id_comentario PRIMARY KEY AUTO_INCREMENT,
    id_restaurante FOREIGN KEY REFERENCES Restaurantes(id_restaurante),
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario),
    comentario TEXT NOT NULL
);

CREATE TABLE Reseñas_Restaurantes (
    id_resena PRIMARY KEY AUTO_INCREMENT,
    id_restaurante FOREIGN KEY REFERENCES Restaurantes(id_restaurante),
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario),
    calificacion DECIMAL(2, 1) NOT NULL,
);

CREATE TABLE Favoritos_Restaurantes (
    id_favorito PRIMARY KEY AUTO_INCREMENT,
    id_restaurante FOREIGN KEY REFERENCES Restaurantes(id_restaurante),
    id_usuario FOREIGN KEY REFERENCES Usuarios(id_usuario)
);