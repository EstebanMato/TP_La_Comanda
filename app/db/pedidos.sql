-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `cliente` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `id_mozo` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `id_producto` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `id_mesa` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `tiempo_restante` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

