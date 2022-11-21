-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
-- 

CREATE TABLE `mesa` (
  `id` int(11) NOT NULL,
  `mesa` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `restaurante` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `mozo` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `cocinero` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `id_pedido` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id`);

--
ALTER TABLE `mesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

