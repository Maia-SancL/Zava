-- Crear la base de datos
CREATE DATABASE Zava;
USE Zava;

-- Tabla Roles
CREATE TABLE Roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL
);

-- Tabla Usuarios
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    nickname VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    contrasenia VARCHAR(100) NOT NULL,
    rol INT,
    foto VARCHAR(255) DEFAULT 'perfil.png',
    FOREIGN KEY (rol) REFERENCES Roles(id_rol)
);

-- Tabla Usuarios_Baneados
CREATE TABLE Usuarios_Baneados (
    id_baneado INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    nickname VARCHAR(50),
    correo VARCHAR(100),
    motivo TEXT NOT NULL,
    fecha_baneo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Productos
CREATE TABLE Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    marca TEXT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    peso DECIMAL(10,2) NOT NULL,
    oferta ENUM('si', 'no') NOT NULL DEFAULT 'no',
    descuento VARCHAR(2) NOT NULL DEFAULT '0',
    precio DECIMAL(10,2) NOT NULL,
    favoritos ENUM('si', 'no') NOT NULL DEFAULT 'no',
    stock INT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'default.png',
    fecha_publicacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Favoritos_Productos
CREATE TABLE Favoritos_Productos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT,
    id_usuario INT,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Recetas
CREATE TABLE Recetas (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_publicacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ingredientes TEXT NOT NULL,
    pasos TEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'receta.png',
    tiempo_preparacion TIME NOT NULL,
    tipo_dieta ENUM('vegano', 'vegetariano', 'sin_lactosa') NOT NULL,
    tipo_comida ENUM('desayuno', 'almuerzo', 'cena', 'snack', 'merienda', 'eventos especiales') NOT NULL,
    porciones INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Comentarios_Recetas
CREATE TABLE Comentarios_Recetas (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT,
    id_usuario INT,
    comentario TEXT NOT NULL,
    fecha_comentario DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Favoritos_Recetas
CREATE TABLE Favoritos_Recetas (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT,
    id_usuario INT,
    FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Restaurantes
CREATE TABLE Restaurantes (
    id_restaurante INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    nombre VARCHAR(100) NOT NULL,
    promedio_calificacion DECIMAL(2,1) NOT NULL,
    tipo_comida TEXT NOT NULL,
    horarios TEXT NOT NULL,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    contactos TEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'restaurante.png',
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Comentarios_Restaurantes
CREATE TABLE Comentarios_Restaurantes (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT,
    id_usuario INT,
    comentario TEXT NOT NULL,
    FOREIGN KEY (id_restaurante) REFERENCES Restaurantes(id_restaurante),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Reseñas_Restaurantes
CREATE TABLE Reseñas_Restaurantes (
    id_resena INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT,
    id_usuario INT,
    calificacion DECIMAL(2,1) NOT NULL,
    FOREIGN KEY (id_restaurante) REFERENCES Restaurantes(id_restaurante),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Favoritos_Restaurantes
CREATE TABLE Favoritos_Restaurantes (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT,
    id_usuario INT,
    FOREIGN KEY (id_restaurante) REFERENCES Restaurantes(id_restaurante),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Datos_entrega
CREATE TABLE Datos_entrega (
    id_dato INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    calle VARCHAR(50) NOT NULL,
    numero VARCHAR(50) NOT NULL,
    piso VARCHAR(10),
    detalle_extra TEXT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Metodos_de_pago
CREATE TABLE Metodos_de_pago (
    id_metodo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    numero VARCHAR(50) NOT NULL,
    nombre_titular TEXT NOT NULL,
    fecha_vencimiento DATETIME NOT NULL,
    dni_titular VARCHAR(20) NOT NULL,
    codigo_trasero VARCHAR(4) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- Tabla Pedidos
CREATE TABLE Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    metodo_pago INT,
    tipo_entrega TEXT NOT NULL,
    nombre_retiro VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (metodo_pago) REFERENCES Metodos_de_pago(id_metodo)
);

-- Tabla Detalle_pedido
CREATE TABLE Detalle_pedido (
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad_productos INT NOT NULL,
    PRIMARY KEY (id_pedido, id_producto),
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);
