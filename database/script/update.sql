----------------------------------------
----------------------------------------
---------- ATENCIONES ------------------
----------------------------------------
----------------------------------------
CREATE TABLE `atenciones` (
  `idatencion` int(11) NOT NULL,
  `iduser` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `fechaatencion` timestamp NOT NULL DEFAULT current_timestamp(),
  `comentario` varchar(700) NOT NULL,
  `idtipoatencion` int(11) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A',
  `userinsert` varchar(60) DEFAULT 'sistemas@padinsolutions.com',
  `dateinsert` timestamp NOT NULL DEFAULT current_timestamp(),
  `userupdate` varchar(60) DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipoatenciones` (
  `idtipoatencion` int(11) NOT NULL,
  `tipoatencion` varchar(150) NOT NULL,
  `backgroundColor` varchar(100) DEFAULT '#d3d3d3',
  `textColor` varchar(100) DEFAULT '#000',
  `estado` char(1) NOT NULL DEFAULT 'A',
  `userinsert` varchar(60) DEFAULT 'sistemas@padinsolutions.com',
  `dateinsert` timestamp NOT NULL DEFAULT current_timestamp(),
  `userupdate` varchar(60) DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `atenciones`
  ADD PRIMARY KEY (`idatencion`),
  ADD KEY `fk_iduser_atenciones` (`iduser`),
  ADD KEY `fk_idtipoatencion_atenciones` (`idtipoatencion`),
  ADD KEY `fk_idcliente_atenciones` (`idcliente`);
  
ALTER TABLE `atenciones`
  MODIFY `idatencion` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tipoatenciones`
  ADD PRIMARY KEY (`idtipoatencion`),
  ADD UNIQUE KEY `ak_tipoatencion_tipoatenciones` (`tipoatencion`,`estado`);
  
ALTER TABLE `tipoatenciones`
  MODIFY `idtipoatencion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
ALTER TABLE `atenciones`
  ADD CONSTRAINT `fk_idcliente_atenciones` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliente`),
  ADD CONSTRAINT `fk_idtipoatencion_atenciones` FOREIGN KEY (`idtipoatencion`) REFERENCES `tipoatenciones` (`idtipoatencion`),
  ADD CONSTRAINT `fk_iduser_atenciones` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`);


-- ----------------------------------
-- ----------------------------------
-- ------- STORE PROCEDURE ----------
-- ----------------------------------
-- ----------------------------------
-- ----
DROP procedure IF EXISTS `SP_RP_REGISTROS_ANIOEGERSO`;
DELIMITER ;;
 CREATE PROCEDURE `SP_RP_REGISTROS_ANIOEGERSO`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100),
    IN p_asistencia INT,
    IN p_import INT
)
BEGIN
	IF p_idevento > 0 THEN
		SELECT 
			cl.anioegreso, COUNT(ec.idcliente) AS total 
			FROM clientes cl
            INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
			WHERE 	cl.estado = 'A'
			AND cl.idcampania = p_idcampania
            AND (p_asistencia = 0 OR ec.asistencia = p_asistencia)
            AND ec.idevento = p_idevento
			AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY cl.anioegreso;
    ELSE
		SELECT 
			anioegreso, COUNT(idcliente) AS total
			FROM clientes
			WHERE 	estado = 'A'
			AND idcampania = p_idcampania
            AND (p_asistencia = 0 OR asistencia = p_asistencia)
            AND (p_asistencia = 1 OR (p_import = 0 OR (p_import = 1 AND uuidimportacion IS NULL)) )
			AND (p_colegio IS NULL OR p_colegio = '' OR colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY anioegreso;
	END IF;
END ;;
DELIMITER ; 

DROP procedure IF EXISTS `SP_RP_REGISTROS_SEDES`;
DELIMITER ;;
CREATE  PROCEDURE `SP_RP_REGISTROS_SEDES`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100),
    IN p_asistencia INT,
    IN p_import INT
)
BEGIN
	IF p_idevento > 0 THEN
		SELECT 
			cl.colegio, COUNT(ec.idcliente) AS total 
			FROM clientes cl
            INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
			WHERE 	cl.estado = 'A'
			AND cl.idcampania = p_idcampania
            AND (p_asistencia = 0 OR ec.asistencia = p_asistencia)
            AND ec.idevento = p_idevento
			AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY cl.colegio;
    
    ELSE
		SELECT 
			colegio, COUNT(idcliente) AS total
			FROM clientes
			WHERE 	estado = 'A'
			AND idcampania = p_idcampania
            AND (p_asistencia = 0 OR asistencia = p_asistencia)
            AND (p_asistencia = 1 OR (p_import = 0 OR (p_import = 1 AND uuidimportacion IS NULL)) )
			AND (p_colegio IS NULL OR p_colegio = '' OR colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY colegio;
	END IF;
END ;;
DELIMITER ;

DROP procedure IF EXISTS `SP_REGSITROSFECHAS_CLIENTES`;
DELIMITER ;;
CREATE PROCEDURE `SP_REGSITROSFECHAS_CLIENTES`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100),
	IN p_asistencia INT,
	IN p_import INT
)
BEGIN
    IF p_idevento > 0 THEN
        -- Consulta con filtro por p_idevento
        SELECT 
            cl.colegio,
            DATE_FORMAT(ec.fecharegistro, "%Y-%m-%d %H:00:00") AS fechaGeneral,
            COUNT(ec.idcliente) AS total
        FROM 
            clientes cl
            INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
        WHERE 
            cl.estado = 'A'
            AND cl.idcampania = p_idcampania
            AND (p_asistencia = 0 OR ec.asistencia = p_asistencia)
            AND ec.idevento = p_idevento
            AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
        GROUP BY 
            cl.colegio, DATE_FORMAT(ec.fecharegistro, "%Y-%m-%d %H:00:00")
        ORDER BY 
            fechaGeneral ASC;
    ELSE
        -- Consulta sin filtro por p_idevento
        SELECT 
            cl.colegio,
            DATE_FORMAT(cl.fecharegistro, "%Y-%m-%d %H:00:00") AS fechaGeneral,
            COUNT(cl.idcliente) AS total
        FROM 
            clientes cl
        WHERE 
            cl.estado = 'A'
            AND cl.idcampania = p_idcampania
            AND (p_asistencia = 0 OR asistencia = p_asistencia)
            AND (p_asistencia = 1 OR (p_import = 0 OR (p_import = 1 AND uuidimportacion IS NULL)) )
            AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
        GROUP BY 
            cl.colegio, DATE_FORMAT(cl.fecharegistro, "%Y-%m-%d %H:00:00")
        ORDER BY 
            fechaGeneral ASC;
    END IF;
END ;;


DROP procedure IF EXISTS `SP_REGSITROSFECHAS_CLIENTES_ASISTIDO`;
DELIMITER ;;
CREATE PROCEDURE `SP_REGSITROSFECHAS_CLIENTES_ASISTIDO`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100),
	IN p_asistencia INT,
	IN p_import INT
)
BEGIN
    IF p_idevento > 0 THEN
        -- Consulta con filtro por p_idevento
        SELECT 
            cl.colegio,
            DATE_FORMAT(ec.fechaasistencia, "%Y-%m-%d %H:00:00") AS fechaGeneral,
            COUNT(ec.idcliente) AS total
        FROM 
            clientes cl
            INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
        WHERE 
            cl.estado = 'A'
            AND cl.idcampania = p_idcampania
            AND (p_asistencia = 0 OR ec.asistencia = p_asistencia)
            AND ec.idevento = p_idevento
            AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
        GROUP BY 
            cl.colegio, DATE_FORMAT(ec.fechaasistencia, "%Y-%m-%d %H:00:00")
        ORDER BY 
            fechaGeneral ASC;
    ELSE
        -- Consulta sin filtro por p_idevento
        SELECT 
            cl.colegio,
            DATE_FORMAT(cl.fechaasistencia, "%Y-%m-%d %H:00:00") AS fechaGeneral,
            COUNT(cl.idcliente) AS total
        FROM 
            clientes cl
        WHERE 
            cl.estado = 'A'
            AND cl.idcampania = p_idcampania
            AND (p_asistencia = 0 OR asistencia = p_asistencia)
            AND (p_asistencia = 1 OR (p_import = 0 OR (p_import = 1 AND uuidimportacion IS NULL)) )
            AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
        GROUP BY 
            cl.colegio, DATE_FORMAT(cl.fechaasistencia, "%Y-%m-%d %H:00:00")
        ORDER BY 
            fechaGeneral ASC;
    END IF;
END ;;

DROP procedure IF EXISTS `SP_RP_REGISTROS_CARRERAS`;
DELIMITER ;;
CREATE PROCEDURE `SP_RP_REGISTROS_CARRERAS`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100),
    IN p_asistencia INT,
	IN p_import INT
)
BEGIN
	IF p_idevento > 0 THEN
    
        SELECT 
			cl.carrera, COUNT(ec.idcliente) AS total
			FROM clientes cl
            INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
			WHERE 	cl.estado = 'A'
			AND cl.idcampania = p_idcampania
			AND (p_asistencia = 0 OR ec.asistencia = p_asistencia)
            AND ec.idevento = p_idevento
			AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY cl.carrera;
    
    ELSE
		SELECT 
			carrera, COUNT(idcliente) AS total
			FROM clientes
			WHERE 	estado = 'A'
			AND idcampania = p_idcampania
			AND (p_asistencia = 0 OR asistencia = p_asistencia)
            AND (p_asistencia = 1 OR (p_import = 0 OR (p_import = 1 AND uuidimportacion IS NULL)) )
			AND (p_colegio IS NULL OR p_colegio = '' OR colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY carrera;
	END IF;
END ;;
DELIMITER ;


-- -----------------------------
-- -----------------------------
-- -----------------------------
CREATE TABLE `buttonmessages` (
  `idbutton` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backgroundColor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '#d3d3d3',
  `textColor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '#000',
  `descriptionwhatsapp` text COLLATE utf8mb4_unicode_ci NULL,
  `estado` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `userinsert` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'sistemas@padinsolutions.com',
  `dateinsert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userupdate` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idbutton`),
  UNIQUE KEY `ak_name_buttonmessages` (`name`,`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ----------
-- ----------
-- ----------
-- ----------
DROP procedure IF EXISTS `SP_RP_REGISTROS_ENVIONOTIFACION`;
DELIMITER ;;
CREATE PROCEDURE `SP_RP_REGISTROS_ENVIONOTIFACION`(
    IN p_idcampania INT
)
BEGIN
    SELECT 
        ta.tipoatencion,
        DATE_FORMAT(a.fechaatencion, '%Y-%m-%d') AS fecha_atencion, 
        COUNT(a.idatencion) AS total_atenciones
    FROM 
        atenciones a
    JOIN 
        clientes c ON a.idcliente = c.idcliente
    JOIN 
        tipoatenciones ta ON a.idtipoatencion = ta.idtipoatencion
    WHERE 
        a.idtipoatencion = 2
        AND c.idcampania = p_idcampania
        AND c.estado = 'A'
    GROUP BY 
        fecha_atencion, ta.tipoatencion
    ORDER BY 
        fecha_atencion ASC, ta.tipoatencion ASC;
END ;;
DELIMITER ;



CREATE DEFINER=`proveedor_rds`@`%` PROCEDURE `SP_RP_ASISTENCIA_SEDE`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100)
)
BEGIN
	
    IF p_idevento > 0 THEN
        -- Consulta con filtro por p_idevento
        SELECT 
			cl.colegio, COUNT(CASE WHEN ec.asistencia = '1' THEN 1 END) AS cantidad_asistencias
		FROM clientes cl INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
		WHERE cl.estado = 'A'
            AND cl.idcampania = p_idcampania
            AND ec.idevento = p_idevento
            AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY colegio;
        
    ELSE
		SELECT 
			colegio, COUNT(CASE WHEN asistencia = '1' THEN 1 END) AS cantidad_asistencias
		FROM clientes
		WHERE 	estado = 'A'
            AND idcampania = p_idcampania
            AND (p_colegio IS NULL OR p_colegio = '' OR colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY colegio;
    END IF;
    
END ;;
DELIMITER ;;

-- ----------
-- ----------
-- ----------
-- ----------

DROP procedure IF EXISTS `SP_RP_REGISTROS_PROCEDENCIAS`;
DELIMITER ;;
CREATE PROCEDURE `SP_RP_REGISTROS_PROCEDENCIAS`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100),
    IN p_asistencia INT,
	IN p_import INT
)
BEGIN
	IF p_idevento > 0 THEN
    
        SELECT 
			cl.procedencia, COUNT(ec.idcliente) AS total
			FROM clientes cl
            INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
			WHERE 	cl.estado = 'A'
			AND cl.idcampania = p_idcampania
			AND (p_asistencia = 0 OR ec.asistencia = p_asistencia)
            AND ec.idevento = p_idevento
			AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY cl.procedencia;
    
    ELSE
		SELECT 
			procedencia, COUNT(idcliente) AS total
			FROM clientes
			WHERE 	estado = 'A'
			AND idcampania = p_idcampania
			AND (p_asistencia = 0 OR asistencia = p_asistencia)
            AND (p_asistencia = 1 OR (p_import = 0 OR (p_import = 1 AND uuidimportacion IS NULL)) )
			AND (p_colegio IS NULL OR p_colegio = '' OR colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY procedencia;
	END IF;
END ;;
DELIMITER ;;


-- -----------
-- -----------
-- -----------
-- -----------
ALTER TABLE users ADD COLUMN `idcampania` int NULL AFTER `idcuenta`;


-- ---------
-- ---------
-- ---------
-- ---------
DROP procedure IF EXISTS `SP_RP_REGISTROS_CAMPAINRESOURCE`;
DELIMITER //
CREATE PROCEDURE `SP_RP_REGISTROS_CAMPAINRESOURCE`(
    IN p_idevento INT,
    IN p_idcampania INT,
    IN p_colegio VARCHAR(100),
    IN p_asistencia INT,
	IN p_import INT
)
BEGIN
	IF p_idevento > 0 THEN
    
        SELECT 
			cl.campaign_source, COUNT(ec.idcliente) AS total
			FROM clientes cl
            INNER JOIN evento_cliente ec ON cl.idcliente = ec.idcliente
			WHERE 	cl.estado = 'A'
            AND cl.campaign_source IS NOT NULL
			AND cl.idcampania = p_idcampania
			AND (p_asistencia = 0 OR ec.asistencia = p_asistencia)
            AND ec.idevento = p_idevento
			AND (p_colegio IS NULL OR p_colegio = '' OR cl.colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY cl.campaign_source;
    
    ELSE
		SELECT 
			campaign_source, COUNT(idcliente) AS total
			FROM clientes
			WHERE 	estado = 'A'
            AND campaign_source IS NOT NULL
			AND idcampania = p_idcampania
			AND (p_asistencia = 0 OR asistencia = p_asistencia)
            AND (p_asistencia = 1 OR (p_import = 0 OR (p_import = 1 AND uuidimportacion IS NULL)) )
			AND (p_colegio IS NULL OR p_colegio = '' OR colegio COLLATE utf8mb4_unicode_ci = p_colegio COLLATE utf8mb4_unicode_ci)
		GROUP BY campaign_source;
	END IF;
END //
DELIMITER //



-- ---------
-- ---------
-- ---------
-- ---------
DROP PROCEDURE IF EXISTS `SP_RP_REGISTROSSTANDVENTA`;
DELIMITER //
CREATE PROCEDURE `SP_RP_REGISTROSSTANDVENTA`(
    IN p_idcampania INT,
    IN p_asistencia INT
)
BEGIN
    IF p_idcampania > 0 THEN
        SELECT 
            e.idevento,
            e.nombreevento,
            COUNT(ec.ideventocliente) AS total
        FROM 
            eventos e
        JOIN 
            evento_cliente ec ON e.idevento = ec.idevento
        WHERE 
            e.estado = 'A'
            AND ec.estado = 'A'
            AND e.idcampania = p_idcampania
            AND  ec.asistencia = p_asistencia
            AND e.nombreevento LIKE '%venta%'
        GROUP BY 
            e.idevento, e.nombreevento;
    ELSE
        SELECT 'Error: idcampania debe ser mayor que 0' AS mensaje_error;
    END IF;
END //
DELIMITER ;


-- ---------
-- ---------
-- ---------
-- ---------
DROP PROCEDURE IF EXISTS `SP_RP_REGISTROSSTANDBECA`;
DELIMITER //
CREATE PROCEDURE `SP_RP_REGISTROSSTANDBECA`(
    IN p_idcampania INT,
    IN p_asistencia INT
)
BEGIN
    IF p_idcampania > 0 THEN
        SELECT 
            e.idevento,
            e.nombreevento,
            COUNT(ec.ideventocliente) AS total
        FROM 
            eventos e
        JOIN 
            evento_cliente ec ON e.idevento = ec.idevento
        WHERE 
            e.estado = 'A'
            AND ec.estado = 'A'
            AND e.idcampania = p_idcampania
            AND  ec.asistencia = p_asistencia
            AND e.nombreevento LIKE '%beca%'
        GROUP BY 
            e.idevento, e.nombreevento;
    ELSE
        SELECT 'Error: idcampania debe ser mayor que 0' AS mensaje_error;
    END IF;
END //
DELIMITER ;


-- ---------
-- ---------
-- ---------
-- ---------
DROP PROCEDURE IF EXISTS `SP_RP_CANTIDADASISTENCIASEDES`;
DELIMITER //
CREATE PROCEDURE `SP_RP_CANTIDADASISTENCIASEDES`(
    IN p_idcampania INT,
    IN p_asistencia INT
)
BEGIN
    IF p_idcampania > 0 THEN
        SELECT 
            e.idevento,
            e.nombreevento,
            COUNT(ec.ideventocliente) AS total
        FROM 
            eventos e
        JOIN 
            evento_cliente ec ON e.idevento = ec.idevento
        WHERE 
            e.estado = 'A'
            AND ec.estado = 'A'
            AND e.idcampania = p_idcampania
            AND  ec.asistencia = p_asistencia
            AND e.nombreevento NOT LIKE '%venta%'
            AND e.nombreevento NOT LIKE '%beca%'
        GROUP BY 
            e.idevento, e.nombreevento;
    ELSE
        SELECT 'Error: idcampania debe ser mayor que 0' AS mensaje_error;
    END IF;
END //
DELIMITER ;




/***update 14/01/25********/
ALTER TABLE matriculas
ADD COLUMN alumno_id BIGINT UNSIGNED NOT NULL AFTER turno_id,
ADD COLUMN carrera_id BIGINT UNSIGNED NOT NULL AFTER alumno_id,
ADD KEY matriculas_alumno_id_foreign (alumno_id),
ADD KEY matriculas_carrera_id_foreign (carrera_id),
ADD CONSTRAINT matriculas_alumno_id_foreign FOREIGN KEY (alumno_id) REFERENCES alumnos (id),
ADD CONSTRAINT matriculas_carrera_id_foreign FOREIGN KEY (carrera_id) REFERENCES carreras (id);

ALTER TABLE matriculas
DROP FOREIGN KEY matriculas_inscripcion_id_foreign;

ALTER TABLE matriculas
DROP INDEX matriculas_inscripcion_id_foreign;

ALTER TABLE matriculas
DROP COLUMN inscripcion_id;




/******************* update 16-01-25 *************************/

DROP TABLE IF EXISTS `pagos`;
CREATE TABLE `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `matricula_id` bigint unsigned NOT NULL,
  `subtotal` decimal(18,2) NOT NULL,
  `detalle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_matricula_id_foreign` (`matricula_id`),
  KEY `pagos_user_id_foreign` (`user_id`),
  CONSTRAINT `pagos_matricula_id_foreign` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas` (`id`),
  CONSTRAINT `pagos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `detallepagos`;
CREATE TABLE `detallepagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pago_id` bigint unsigned NOT NULL, -- Relación con la tabla `pagos`
  `concepto_id` bigint unsigned NOT NULL, -- Relación con los conceptos de pago
  `precio_unitario` decimal(18,2) NOT NULL, -- Precio por unidad
  `descuento` decimal(18,2) NOT NULL DEFAULT 0.00,
  `importe` decimal(18,2) NOT NULL, -- el total de la suma
  `created_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_usr` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pago_detalles_pago_id_foreign` (`pago_id`),
  KEY `pago_detalles_concepto_id_foreign` (`concepto_id`),
  CONSTRAINT `pago_detalles_pago_id_foreign` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pago_detalles_concepto_id_foreign` FOREIGN KEY (`concepto_id`) REFERENCES `concepto_pagos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE matriculas DROP FOREIGN KEY matriculas_condicion_id_foreign;
ALTER TABLE matriculas DROP FOREIGN KEY matriculas_periodo_id_foreign;
alter table matriculas drop periodo_id, drop condicion_id;
