-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
-- 

CREATE TABLE `encuentas` (
  `id` int(11) NOT NULL,
  `codigo_ticket` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `mesa` int(11) NOT NULL,
  `restaurante` int(11) NOT NULL,
  `mozo` int(11) NOT NULL,
  `cocinero` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `comentarios` varchar(67) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `encuentas`
  ADD PRIMARY KEY (`id`);

--
ALTER TABLE `encuentas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

