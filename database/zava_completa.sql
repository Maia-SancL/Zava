-- =====================================================
-- BASE DE DATOS ZAVA - VERSIÓN COMPLETA
-- Lista para importar directamente
-- =====================================================

DROP DATABASE IF EXISTS Zava;
CREATE DATABASE Zava;
USE Zava;

-- =====================================================
-- TABLAS BÁSICAS DE CONFIGURACIÓN
-- =====================================================

-- Tabla Roles
CREATE TABLE Roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Tabla Categorias (simplificada)
CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('producto', 'receta') NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- =====================================================
-- TABLA USUARIOS
-- =====================================================

-- Tabla Usuarios
CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    nickname VARCHAR(50) UNIQUE NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    contrasenia VARCHAR(255) NOT NULL,
    id_rol INT DEFAULT 1,
    foto VARCHAR(255) DEFAULT 'perfil.png',
    activo BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verificado BOOLEAN DEFAULT FALSE,
    token_verificacion VARCHAR(255) NULL,
    token_expiracion DATETIME NULL,
    token_restauracion VARCHAR(255) NULL,
    token_restauracion_expiracion DATETIME NULL,
    nuevo_correo VARCHAR(100) NULL,
    token_cambio_correo VARCHAR(255) NULL,
    token_cambio_correo_expiracion DATETIME NULL,
    token_eliminacion VARCHAR(255) NULL,
    token_eliminacion_expiracion DATETIME NULL,
    FOREIGN KEY (id_rol) REFERENCES Roles(id_rol)
);

-- Tabla Usuarios Baneados
CREATE TABLE Usuarios_Baneados (
    id_baneo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    motivo TEXT NOT NULL,
    fecha_baneo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

-- =====================================================
-- TABLA PRODUCTOS
-- =====================================================

-- Tabla Productos
CREATE TABLE Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    marca VARCHAR(100),
    id_categoria INT,
    peso DECIMAL(10,2),
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    descuento BOOLEAN NOT NULL DEFAULT FALSE,
    porcentaje_descuento INT(3) NULL DEFAULT NULL,
    imagen_principal VARCHAR(255) DEFAULT 'producto_default.png',
    imagen VARCHAR(255) DEFAULT 'producto_default.png',
    activo BOOLEAN DEFAULT TRUE,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria)
);

-- Tabla Imágenes de Productos (simplificada)
CREATE TABLE Producto_Imagenes (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    ruta_imagen VARCHAR(500) NOT NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE
);

-- Tabla Favoritos Productos
CREATE TABLE Favoritos_Productos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (id_producto, id_usuario)
);

-- =====================================================
-- TABLA RECETAS
-- =====================================================

-- Tabla Recetas
CREATE TABLE Recetas (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    ingredientes TEXT NOT NULL,
    pasos TEXT NOT NULL,
    tiempo_preparacion INT, -- en minutos
    porciones INT NOT NULL DEFAULT 1,
    dificultad ENUM('fácil', 'intermedio', 'difícil') DEFAULT 'fácil',
    tipo_comida ENUM('desayuno', 'almuerzo', 'merienda', 'cena', 'postre', 'snack', 'bebida') NOT NULL,
    tipo_dieta ENUM('omnívora', 'vegetariana', 'vegana', 'sin_gluten') DEFAULT 'omnívora',
    id_categoria INT,
    imagen_principal VARCHAR(255) DEFAULT 'receta_default.png',
    imagen VARCHAR(255) DEFAULT 'receta_default.png',
    activa BOOLEAN DEFAULT TRUE,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria)
);

-- Tabla Imágenes de Recetas (simplificada)
CREATE TABLE Receta_Imagenes (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT NOT NULL,
    ruta_imagen VARCHAR(500) NOT NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta) ON DELETE CASCADE
);

-- Tabla Favoritos Recetas
CREATE TABLE Favoritos_Recetas (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_agregado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (id_receta, id_usuario)
);

-- Tabla Calificaciones de Recetas
CREATE TABLE Receta_Calificaciones (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT NOT NULL,
    id_usuario INT NOT NULL,
    calificacion DECIMAL(2,1) NOT NULL CHECK (calificacion >= 1 AND calificacion <= 5),
    comentario TEXT,
    fecha_calificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY unique_rating (id_receta, id_usuario),
    INDEX idx_receta (id_receta),
    INDEX idx_usuario (id_usuario)
);

-- Tabla Comentarios de Recetas
CREATE TABLE Comentarios_Recetas (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT NOT NULL,
    id_usuario INT NOT NULL,
    comentario TEXT NOT NULL,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_receta) REFERENCES Recetas(id_receta) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_receta (id_receta),
    INDEX idx_usuario (id_usuario)
);

-- =====================================================
-- SISTEMA DE PEDIDOS
-- =====================================================

-- Tabla Datos de Entrega
CREATE TABLE Datos_Entrega (
    id_dato INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    calle VARCHAR(100) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    piso VARCHAR(10),
    ciudad VARCHAR(50) NOT NULL,
    detalle_extra TEXT,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

-- Tabla Métodos de Pago
CREATE TABLE Metodos_Pago (
    id_metodo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo_metodo ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
    detalle VARCHAR(100), -- últimos 4 dígitos, banco, etc.
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
);

-- Tabla Pedidos
CREATE TABLE Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    numero_pedido VARCHAR(20) UNIQUE NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'preparando', 'listo', 'entregado', 'cancelado') DEFAULT 'pendiente',
    subtotal DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    id_metodo_pago INT,
    id_datos_entrega INT,
    tipo_entrega ENUM('domicilio', 'retiro') NOT NULL,
    nombre_retiro VARCHAR(100),
    fecha_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_metodo_pago) REFERENCES Metodos_Pago(id_metodo),
    FOREIGN KEY (id_datos_entrega) REFERENCES Datos_Entrega(id_dato)
);

-- Tabla Detalle de Pedido
CREATE TABLE Detalle_Pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);

-- =====================================================
-- SISTEMA DE HISTORIAL Y ACTIVIDAD
-- =====================================================

-- Tabla Historial de Vistas
CREATE TABLE Historial_Vistas (
    id_vista INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo_contenido ENUM('receta', 'producto') NOT NULL,
    id_contenido INT NOT NULL,
    fecha_vista TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    INDEX idx_usuario_fecha (id_usuario, fecha_vista),
    INDEX idx_tipo_contenido (tipo_contenido, id_contenido)
);

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Insertar Roles básicos
INSERT INTO Roles (nombre, descripcion) VALUES
('Usuario', 'Usuario estándar'),
('Vendedor', 'Usuario vendedor'),
('Admin', 'Administrador');

-- Insertar Categorías básicas
INSERT INTO Categorias (nombre, tipo) VALUES
-- Productos
('Golosinas', 'producto'),
('Panaderia', 'producto'),
('Snacks', 'producto'),
('Cereales', 'producto'),
('Aderezos', 'producto'),
('Infusiones', 'producto'),
('Pastas', 'producto'),
('Harinas y premezclas', 'producto'),
('Arroz y legumbres', 'producto'),
('Mermeladas y Dulces', 'producto'),
('Congelados', 'producto'),
('Lacteos', 'producto'),
('Quesos', 'producto'),
('Bebidas', 'producto'),
('Salsas y Pure de Tomate', 'producto'),
-- Recetas
('Platos Principales', 'receta'),
('Postres', 'receta'),
('Bebidas', 'receta'),
('Entradas', 'receta'),
('Ensaladas', 'receta'),
('Sopas', 'receta'),


-- =====================================================
-- DATOS DE PRUEBA
-- =====================================================

-- Usuario administrador
INSERT INTO Usuarios (nombre, apellido, nickname, correo, contrasenia, id_rol) VALUES
('Admin', 'Sistema', 'admin', 'admin@zava.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4);

-- Usuarios de prueba
INSERT INTO Usuarios (nombre, apellido, nickname, correo, contrasenia, id_rol) VALUES
('Juan', 'Pérez', 'juanperez', 'juan@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('María', 'González', 'mariagonzalez', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Carlos', 'Rodríguez', 'carlosrestaurante', 'carlos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3),
('Ana', 'López', 'analopez', 'ana@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Productos de prueba
INSERT INTO Productos (id_usuario, nombre, descripcion, marca, id_categoria, precio, stock, imagen_principal, imagen) VALUES
(3, 'Leche Entera', 'Leche fresca de vaca, 1 litro', 'La Serenísima', 1, 120.50, 50, 'leche_entera.jpg', 'uploads/productos/leche_entera.jpg'),
(3, 'Pan Integral', 'Pan integral artesanal', 'Panadería Don Juan', 7, 85.00, 30, 'pan_integral.jpg', 'uploads/productos/pan_integral.jpg'),
(3, 'Queso Mozzarella', 'Queso mozzarella fresco', 'La Paulina', 1, 280.00, 25, 'queso_mozzarella.jpg', 'uploads/productos/queso_mozzarella.jpg'),
(3, 'Tomates Cherry', 'Tomates cherry frescos, 500g', 'Huerta Verde', 4, 150.00, 40, 'tomates_cherry.jpg', 'uploads/productos/tomates_cherry.jpg');

-- Recetas de prueba
INSERT INTO Recetas (id_usuario, nombre, descripcion, ingredientes, pasos, tiempo_preparacion, porciones, dificultad, tipo_comida, id_categoria, imagen_principal, imagen) VALUES
(2, 'Pasta Carbonara', 'Deliciosa pasta italiana con huevos y panceta', 'Pasta, huevos, queso parmesano, panceta, pimienta negra', '1. Cocinar la pasta\n2. Freír la panceta\n3. Mezclar huevos con queso\n4. Combinar todo', 30, 4, 'intermedio', 'almuerzo', 9, 'pasta_carbonara.jpg', 'uploads/recetas/pasta_carbonara.jpg'),
(2, 'Ensalada César', 'Ensalada fresca con pollo y aderezo césar', 'Lechuga romana, pollo, crutones, queso parmesano, aderezo césar', '1. Lavar y cortar lechuga\n2. Cocinar pollo\n3. Preparar crutones\n4. Mezclar con aderezo', 20, 2, 'fácil', 'almuerzo', 13, 'ensalada_cesar.jpg', 'uploads/recetas/ensalada_cesar.jpg'),
(4, 'Brownies de Chocolate', 'Brownies húmedos y deliciosos', 'Chocolate, manteca, huevos, azúcar, harina, nueces', '1. Derretir chocolate\n2. Mezclar ingredientes\n3. Hornear 25 minutos', 45, 8, 'fácil', 'postre', 10, 'brownies.jpg', 'uploads/recetas/brownies.jpg');



-- Favoritos de prueba
INSERT INTO Favoritos_Productos (id_producto, id_usuario) VALUES
(1, 2), (2, 2), (3, 4);

INSERT INTO Favoritos_Recetas (id_receta, id_usuario) VALUES
(1, 4), (2, 2), (3, 2);



-- Calificaciones de prueba
INSERT INTO Receta_Calificaciones (id_receta, id_usuario, calificacion, comentario) VALUES
(1, 4, 4.5, 'Excelente receta, muy fácil de seguir'),
(2, 4, 5.0, 'Perfecta para el almuerzo'),
(3, 2, 4.0, 'Muy ricos brownies');



-- Comentarios de prueba
INSERT INTO Comentarios_Recetas (id_receta, id_usuario, comentario) VALUES
(1, 4, 'Me encantó esta receta, la hice para mi familia'),
(2, 4, 'Muy fresca y sabrosa'),
(3, 2, 'Los brownies quedaron perfectos');



-- Historial de vistas de prueba
INSERT INTO Historial_Vistas (id_usuario, tipo_contenido, id_contenido, fecha_vista) VALUES
(2, 'receta', 1, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(2, 'producto', 1, DATE_SUB(NOW(), INTERVAL 2 HOURS)),

(4, 'receta', 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(4, 'producto', 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),


-- Pedidos de prueba
INSERT INTO Datos_Entrega (id_usuario, calle, numero, ciudad) VALUES
(2, 'Av. Libertador', '1500', 'Buenos Aires'),
(4, 'Corrientes', '800', 'Buenos Aires');

INSERT INTO Metodos_Pago (id_usuario, tipo_metodo, detalle) VALUES
(2, 'tarjeta', '**** 1234'),
(4, 'efectivo', 'Pago en efectivo');

INSERT INTO Pedidos (id_usuario, numero_pedido, estado, subtotal, total, id_metodo_pago, id_datos_entrega, tipo_entrega) VALUES
(2, 'PED-001', 'entregado', 401.50, 401.50, 1, 1, 'domicilio'),
(4, 'PED-002', 'pendiente', 235.00, 235.00, 2, 2, 'retiro');

INSERT INTO Detalle_Pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES
(1, 1, 2, 120.50, 241.00),
(1, 3, 1, 280.00, 280.00),
(2, 2, 1, 85.00, 85.00),
(2, 4, 1, 150.00, 150.00);

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

SELECT 'Base de datos Zava creada exitosamente' AS mensaje;
SELECT COUNT(*) AS total_tablas FROM information_schema.tables WHERE table_schema = 'Zava';
SELECT 'Usuarios creados:', COUNT(*) FROM Usuarios;
SELECT 'Productos creados:', COUNT(*) FROM Productos;
SELECT 'Recetas creadas:', COUNT(*) FROM Recetas;

