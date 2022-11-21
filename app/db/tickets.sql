-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
-- public $id;

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `id_mesa` int(5) NOT NULL,
  `id_mozo` int(5) NOT NULL,
  `estado` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

