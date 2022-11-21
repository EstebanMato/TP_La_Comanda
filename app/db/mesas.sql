-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
-- Foto es opcional, cargamos la ruta de donde se guarda, en caso de que no empty

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `foto` varchar(250) COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

