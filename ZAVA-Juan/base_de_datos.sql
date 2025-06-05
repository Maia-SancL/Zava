CREATE DATABASE Zava;
USE Zava;

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
    fecha_baneo DATETIME NOT NULL CURRENT_TIMESTAMP
);

CREATE TABLE Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    marca TEXT NOT NULL,
    tipo VARCHAR(50) NOT NULL, -- Por ejemplo: dentro de la categoria "golosinas": chocolate, caramelos, etc.
    categoria VARCHAR(50) NOT NULL, -- Por ejemplo: "frutas", "verduras", "lácteos", "carnes", "golosinas", etc.
    peso DECIMAL(10, 2) NOT NULL,
    oferta ENUM('si', 'no') NOT NULL DEFAULT 'no',
    descuento VARCHAR(2) NOT NULL DEFAULT '0', -- Porcentaje de descuento, por ejemplo: '10' para un 10% de descuento
    precio DECIMAL(10, 2) NOT NULL, -- Precio del producto
    favoritos ENUM('si', 'no') NOT NULL DEFAULT 'no',
    stock INT NOT NULL,
    imagen VARCHAR(255) DEFAULT 'default.png',
    fecha_publicacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
    fecha_publicacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ingredientes TEXT NOT NULL,
    pasos TEXT NOT NULL,
    imagen TEXT DEFAULT 'receta.png, receta1.png, receta2.png', -- Se pueden agregar varias imágenes separadas por comas
    tiempo_preparacion TIME NOT NULL,
    tipo_dieta ENUM('vegano', 'vegetariano', 'sin_lactosa') NOT NULL,
    tipo_comida ENUM('desayuno', 'almuerzo', 'cena', 'snack', 'merienda', 'eventos especiales') NOT NULL,
    porciones INT NOT NULL
);

CREATE TABLE Comentarios_Recetas (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT, FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    comentario TEXT NOT NULL,
    fecha_comentario DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Favoritos_Recetas (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT, FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta),
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Restaurantes (
    id_restaurante INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    nombre VARCHAR(100) NOT NULL,
    promedio_calificacion DECIMAL(2, 1) NOT NULL,
    tipo_comida TEXT NOT NULL,
    horarios TEXT NOT NULL, -- Por ejemplo: "Lunes a Viernes: 9:00 - 22:00, Sábado y Domingo: 10:00 - 23:00"
    descripcion TEXT NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    contactos TEXT NOT NULL, -- Por ejemplo: "Teléfono: 123456789, Email:
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

CREATE TABLE Datos_entrega (
    id_dato INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    calle VARCHAR(50) NOT NULL,
    numero VARCHAR(50) NOT NULL,
    piso VARCHAR(10),
    detalle_extra TEXT
);

CREATE TABLE Metodos_de_pago (
    id_metodo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    numero VARCHAR(50) NOT NULL,
    nombre_titular TEXT NOT NULL,
    fecha_vencimiento DATETIME NOT NULL,
    dni_titular VARCHAR(20) NOT NULL, -- DNI del titular de la tarjeta
    codigo_trasero VARCHAR(4) NOT NULL
);

CREATE TABLE Pedidos(
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    metodo_pago INT, FOREIGN KEY (metodo_pago) REFERENCES Metodos_de_pago(id_metodo),
    tipo_entrega TEXT NOT NULL,
    nombre_retiro VARCHAR(100) NOT NULL, -- Nombre del usuario que retira el pedido
);

CREATE TABLE Detalle_pedido{
    id_pedido INT(15) NOT NULL, FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido),
    id_producto INT(15) NOT NULL, FOREIGN KEY (id_producto) REFERENCES Productos(id_producto),
    cantidad_productos INT NOT NULL -- Cantidad total de productos en el pedido
}