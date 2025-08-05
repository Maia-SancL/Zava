-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2025 a las 17:57:16
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zava`
--
CREATE DATABASE Zava;
USE Zava;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios_recetas`
--

CREATE TABLE `comentarios_recetas` (
  `id_comentario` int(11) NOT NULL,
  `id_receta` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `comentario` text NOT NULL,
  `fecha_comentario` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios_restaurantes`
--

CREATE TABLE `comentarios_restaurantes` (
  `id_comentario` int(11) NOT NULL,
  `id_restaurante` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `comentario` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_entrega`
--

CREATE TABLE `datos_entrega` (
  `id_dato` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `calle` varchar(50) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `piso` varchar(10) DEFAULT NULL,
  `detalle_extra` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_productos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos_productos`
--

CREATE TABLE `favoritos_productos` (
  `id_favorito` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos_recetas`
--

CREATE TABLE `favoritos_recetas` (
  `id_favorito` int(11) NOT NULL,
  `id_receta` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos_restaurantes`
--

CREATE TABLE `favoritos_restaurantes` (
  `id_favorito` int(11) NOT NULL,
  `id_restaurante` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_vistas`
--

CREATE TABLE `historial_vistas` (
  `id_vista` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_contenido` enum('receta','restaurante','producto') NOT NULL,
  `id_contenido` int(11) NOT NULL,
  `fecha_vista` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_de_pago`
--

CREATE TABLE `metodos_de_pago` (
  `id_metodo` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `numero` varchar(50) NOT NULL,
  `nombre_titular` text NOT NULL,
  `fecha_vencimiento` datetime NOT NULL,
  `dni_titular` varchar(20) NOT NULL,
  `codigo_trasero` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_pedido` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` int(11) DEFAULT NULL,
  `tipo_entrega` text NOT NULL,
  `nombre_retiro` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `marca` text NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `peso` decimal(10,2) NOT NULL,
  `oferta` enum('si','no') NOT NULL DEFAULT 'no',
  `descuento` varchar(2) NOT NULL DEFAULT '0',
  `precio` decimal(10,2) NOT NULL,
  `favoritos` enum('si','no') NOT NULL DEFAULT 'no',
  `stock` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT 'default.png',
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas`
--

CREATE TABLE `recetas` (
  `id_receta` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp(),
  `ingredientes` text NOT NULL,
  `pasos` text NOT NULL,
  `imagen` varchar(255) DEFAULT 'receta.png',
  `tiempo_preparacion` time NOT NULL,
  `tipo_dieta` enum('vegana','vegetariana','sin lactosa','otra','sin especificar') NOT NULL,
  `tipo_comida` enum('desayuno','almuerzo','cena','snack','merienda','evento especial','sin especificar') NOT NULL,
  `porciones` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id_receta`, `id_usuario`, `nombre`, `descripcion`, `fecha_publicacion`, `ingredientes`, `pasos`, `imagen`, `tiempo_preparacion`, `tipo_dieta`, `tipo_comida`, `porciones`) VALUES
(1, 1, 'Torta de chocolate y castañas', 'Hacé esta rica torta de chocolate y castañas sin tacc y disfrutá de un delicioso postre casero', '2025-07-06 16:00:50', '6 huevos, 250 gramos castañas peladas, 75 gramos manteca a temperatura ambiente, 250 gramos azúcar, 150 gramos almidón de maíz Maizena, 500 mililitros leche, 1 Pizca sal, 1 cucharada polvo de hornear', 'En una olla mezclar las avellanas con la leche y la sal, cocinar a fuego moderado durante media hora o hasta que estén bien tiernas.. Luego triturar las avellanas con la leche hasta formar una crema. Incorporar la manteca a temperatura ambiente, la Maizena y el polvo de hornear, batiendo hasta integrar todo.. Por último batir los huevos con el azúcar hasta espumar, integrarlos a la mezcla anterior con movimientos envolventes y volcar en una fuente enmantecada y espolvoreada con Maizena para hornear a fuego moderado durante 45 minutos o hasta que pinchando con un palillo este salga limpio.. Dejar enfriar y servir. Disfrutá esta torta sin tacc con Maizena', 'uploads/torta de castañas.avif', '00:45:00', 'sin especificar', 'merienda', 10),
(6, 1, 'Postrecito de Vainilla', 'Postrecito de vainilla. Si querés un postre fácil, rápido y sin tacc, probá hacer el postrecito de vainilla Maizena apto para celíacos. Una delicia.', '2025-07-06 16:16:49', '1 litro de leche, 5 cucharadas de Maizena, 4 cucharadas de azúcar, esencia de vainilla', 'Servir una taza de leche y agregar las 5 cucharadas de Maizena, mezclar hasta que no queden grumos. Colocar la leche restante junto con el azúcar y esencia de vainilla a fuego lento en una cacerola. Una vez que esté bien caliente, agregamos la preparación anterior de la taza y revolvemos. Cuando esté bien espeso y por hervir, sacarlo del fuego. Seguir batiendo (con cuchara de madera) hasta que baje la temperatura. Dejar enfriar y disfrutá de este postre apto para celíacos, sin tacc', 'uploads/postre de vainilla.avif', '00:15:00', 'otra', 'merienda', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseñas_restaurantes`
--

CREATE TABLE `reseñas_restaurantes` (
  `id_resena` int(11) NOT NULL,
  `id_restaurante` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `calificacion` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurantes`
--

CREATE TABLE `restaurantes` (
  `id_restaurante` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `promedio_calificacion` decimal(2,1) NOT NULL,
  `tipo_comida` text NOT NULL,
  `horarios` text NOT NULL,
  `descripcion` text NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `contactos` text NOT NULL,
  `imagen` varchar(255) DEFAULT 'restaurante.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'vendedor', 'Usuario vendedor'),
(2, 'cliente', 'Usuario cliente'),
(3, 'administrador', 'Usuario administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `contrasenia` varchar(100) NOT NULL,
  `rol` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'perfil.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `nickname`, `correo`, `telefono`, `contrasenia`, `rol`, `foto`) VALUES
(1, 'Joaquin', 'Roldan', 'jolareka', 'roldanjoaquind42@gmail.com', '2262540188', 'b4b147bc522828731f1a016bfa72c073', 2, 'img/perfiles/perfil_1_1751858717.jpg'),
(2, 'Matias', 'Gigena', 'VendedorGige', 'Matias@vendedor.com', '2222', 'b4b147bc522828731f1a016bfa72c073', 1, 'perfil.png'),
(3, 'Joaquin', 'Roldan', 'eviljol', 'roldanevil42@gmail.com', '22', 'b4b147bc522828731f1a016bfa72c073', 3, 'perfil.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_baneados`
--

CREATE TABLE `usuarios_baneados` (
  `id_baneado` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `motivo` text NOT NULL,
  `fecha_baneo` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios_recetas`
--
ALTER TABLE `comentarios_recetas`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_receta` (`id_receta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `comentarios_restaurantes`
--
ALTER TABLE `comentarios_restaurantes`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `id_restaurante` (`id_restaurante`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `datos_entrega`
--
ALTER TABLE `datos_entrega`
  ADD PRIMARY KEY (`id_dato`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_pedido`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `favoritos_productos`
--
ALTER TABLE `favoritos_productos`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `favoritos_recetas`
--
ALTER TABLE `favoritos_recetas`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_receta` (`id_receta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `favoritos_restaurantes`
--
ALTER TABLE `favoritos_restaurantes`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_restaurante` (`id_restaurante`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `historial_vistas`
--
ALTER TABLE `historial_vistas`
  ADD PRIMARY KEY (`id_vista`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `metodos_de_pago`
--
ALTER TABLE `metodos_de_pago`
  ADD PRIMARY KEY (`id_metodo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `metodo_pago` (`metodo_pago`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD PRIMARY KEY (`id_receta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `reseñas_restaurantes`
--
ALTER TABLE `reseñas_restaurantes`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `id_restaurante` (`id_restaurante`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD PRIMARY KEY (`id_restaurante`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `rol` (`rol`);

--
-- Indices de la tabla `usuarios_baneados`
--
ALTER TABLE `usuarios_baneados`
  ADD PRIMARY KEY (`id_baneado`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios_recetas`
--
ALTER TABLE `comentarios_recetas`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentarios_restaurantes`
--
ALTER TABLE `comentarios_restaurantes`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `datos_entrega`
--
ALTER TABLE `datos_entrega`
  MODIFY `id_dato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `favoritos_productos`
--
ALTER TABLE `favoritos_productos`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `favoritos_recetas`
--
ALTER TABLE `favoritos_recetas`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `favoritos_restaurantes`
--
ALTER TABLE `favoritos_restaurantes`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_vistas`
--
ALTER TABLE `historial_vistas`
  MODIFY `id_vista` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metodos_de_pago`
--
ALTER TABLE `metodos_de_pago`
  MODIFY `id_metodo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recetas`
--
ALTER TABLE `recetas`
  MODIFY `id_receta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `reseñas_restaurantes`
--
ALTER TABLE `reseñas_restaurantes`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  MODIFY `id_restaurante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios_baneados`
--
ALTER TABLE `usuarios_baneados`
  MODIFY `id_baneado` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios_recetas`
--
ALTER TABLE `comentarios_recetas`
  ADD CONSTRAINT `comentarios_recetas_ibfk_1` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id_receta`),
  ADD CONSTRAINT `comentarios_recetas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `comentarios_restaurantes`
--
ALTER TABLE `comentarios_restaurantes`
  ADD CONSTRAINT `comentarios_restaurantes_ibfk_1` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id_restaurante`),
  ADD CONSTRAINT `comentarios_restaurantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `datos_entrega`
--
ALTER TABLE `datos_entrega`
  ADD CONSTRAINT `datos_entrega_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `favoritos_productos`
--
ALTER TABLE `favoritos_productos`
  ADD CONSTRAINT `favoritos_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `favoritos_productos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `favoritos_recetas`
--
ALTER TABLE `favoritos_recetas`
  ADD CONSTRAINT `favoritos_recetas_ibfk_1` FOREIGN KEY (`id_receta`) REFERENCES `recetas` (`id_receta`),
  ADD CONSTRAINT `favoritos_recetas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `favoritos_restaurantes`
--
ALTER TABLE `favoritos_restaurantes`
  ADD CONSTRAINT `favoritos_restaurantes_ibfk_1` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id_restaurante`),
  ADD CONSTRAINT `favoritos_restaurantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `historial_vistas`
--
ALTER TABLE `historial_vistas`
  ADD CONSTRAINT `historial_vistas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `metodos_de_pago`
--
ALTER TABLE `metodos_de_pago`
  ADD CONSTRAINT `metodos_de_pago_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`metodo_pago`) REFERENCES `metodos_de_pago` (`id_metodo`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `recetas`
--
ALTER TABLE `recetas`
  ADD CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `reseñas_restaurantes`
--
ALTER TABLE `reseñas_restaurantes`
  ADD CONSTRAINT `reseñas_restaurantes_ibfk_1` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id_restaurante`),
  ADD CONSTRAINT `reseñas_restaurantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `restaurantes`
--
ALTER TABLE `restaurantes`
  ADD CONSTRAINT `restaurantes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `usuarios_baneados`
--
ALTER TABLE `usuarios_baneados`
  ADD CONSTRAINT `usuarios_baneados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
