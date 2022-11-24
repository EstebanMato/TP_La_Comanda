-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


  INSERT INTO `usuarios`(`id`, `nombre`, `clave`, `tipo`) VALUES ('esteban','$2y$10$B8QYO97oM/3chYdI/US91OjouHpxN89czuW6G/OrYC2ueK3p8PUIK','socio');