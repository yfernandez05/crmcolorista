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

-- 1-20-2025
ALTER TABLE matriculas
DROP FOREIGN KEY matriculas_inscripcion_id_foreign;

ALTER TABLE matriculas
DROP INDEX matriculas_inscripcion_id_foreign;

ALTER TABLE matriculas
DROP COLUMN inscripcion_id;


CREATE TABLE `tipocomprobantes` (
  `codcomprobante` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombrecomprobante` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigosunat` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `serie` char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `correlativo` int NOT NULL DEFAULT '0',
  `agregarigv` int DEFAULT '0',
  `estado` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `userIng` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaIng` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userUpd` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaUpd` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`codcomprobante`),
  UNIQUE KEY `ak_nombrecomprobante` (`nombrecomprobante`,`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

INSERT INTO `tipocomprobantes` (`codcomprobante`, `nombrecomprobante`, `codigosunat`, `serie`, `correlativo`, `agregarigv`, `estado`, `userIng`, `fechaIng`, `userUpd`, `fechaUpd`) VALUES
(1, 'Boleta de Venta', '03', 'B001', 1, 0, 'A', 'sistemas@padinsolutions.com', '2018-07-10 13:26:15', 'sistemas@padinsolutions.com', '2018-08-04 20:17:44');

ALTER TABLE cursos
ADD COLUMN duracion_meses int NOT NULL;

-- atencion 
CREATE TABLE `tipoatenciones` (
  `idtipoatencion` int(11) NOT NULL AUTO_INCREMENT,
  `tipoatencion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `backgroundColor` varchar(500) default '#d3d3d3',
  `textColor` varchar(500) default '#000',
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `userinsert` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sistemas@padinsolutions.com',
  `dateinsert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userupdate` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idtipoatencion`),
  UNIQUE KEY `ak_tipoatencion_tipoatenciones` (`tipoatencion`,`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tipoatenciones` (`idtipoatencion`, `tipoatencion`, `backgroundColor`, `textColor`, `estado`, `userinsert`, `dateinsert`, `userupdate`, `dateupdate`) VALUES
(1, 'Sin Atender', '#ff0000', '#fff','A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:20:06'),
(2, 'Inubicable', '#000000', '#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:20:22'),
(3, 'Cliente no perfilado', '#a636e2', '#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-10 18:27:27'),
(4, 'Pago realizado', '#077413', '#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(5, 'Venta finalizada', '#36c482', '#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(6, 'Negociación', '#fae500', '#000', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(7, 'Cliente perfilado', '#f05400', '#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(8, 'Contacto Inicial', '#4a4545', '#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09');

CREATE TABLE `etiquetatelefonica` (
  `idetiquetatele` int(11) NOT NULL AUTO_INCREMENT,
  `etiquetatele` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `backgroundColor` varchar(500) default '#d3d3d3',
  `textColor` varchar(500) default '#000',
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `userinsert` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sistemas@padinsolutions.com',
  `dateinsert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userupdate` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idetiquetatele`),
  UNIQUE KEY `ak_etiquetatele_etiquetatelefonica` (`idetiquetatele`,`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `etiquetatelefonica` (`idetiquetatele`, `etiquetatele`, `backgroundColor`, `textColor`,`estado`, `userinsert`, `dateinsert`, `userupdate`, `dateupdate`) VALUES
(1, 'Sin Atender', '#ff0000','#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:20:06'),
(2, 'Número errado', '#d3d3d3','#000', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:20:55'),
(3, 'Contesta tercero', '#fae500','#000', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(4, 'Buzón de voz', '#a636e2','#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(5, 'No contesta', '#000000','#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(6, 'Seguimiento', '#fae500','#000', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09'),
(7, 'Contactado', '#077413','#fff', 'A', 'sistemas@padinsolutions.com', '2020-06-27 06:11:50', NULL, '2020-07-06 03:21:09');


CREATE TABLE `atenciones` (
  `idatencion` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` bigint unsigned NOT NULL,
  `prospecto_id` bigint unsigned NOT NULL,
  `fechaatencion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comentario` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idtipoatencion` int(11) NOT NULL,
  `idetiquetatele` int(11) NOT NULL,
  `estado` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `userinsert` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sistemas@padinsolutions.com',
  `dateinsert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userupdate` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idatencion`),
  KEY `fk_iduser_atenciones` (`iduser`),
  KEY `fk_idtipoatencion_atenciones` (`idtipoatencion`),
  KEY `fk_idetiquetatele_atenciones` (`idetiquetatele`),
  KEY `fk_alumno_id_atenciones` (`prospecto_id`),
  CONSTRAINT `fk_prospecto_id_atenciones` FOREIGN KEY (`prospecto_id`) REFERENCES `prospectos` (`id`),
  CONSTRAINT `fk_idtipoatencion_atenciones` FOREIGN KEY (`idtipoatencion`) REFERENCES `tipoatenciones` (`idtipoatencion`),
   CONSTRAINT `fk_idetiquetatele_atenciones` FOREIGN KEY (`idetiquetatele`) REFERENCES `etiquetatelefonica` (`idetiquetatele`),
  CONSTRAINT `fk_iduser_atenciones` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------

alter table pagos add codcomprobante int DEFAULT NULL AFTER user_id;
alter table pagos add serie varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER codcomprobante;
alter table pagos add numero varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER serie;

DELIMITER $$
CREATE TRIGGER `pago_before_insert_add_serienumero` BEFORE INSERT ON `pagos` FOR EACH ROW BEGIN

    declare v_correlativo int;
    
    set v_correlativo = (select (correlativo + 1) from tipocomprobantes where codcomprobante = new.codcomprobante);
    
    set new.numero = v_correlativo;
    
    update tipocomprobantes set correlativo = v_correlativo where codcomprobante = new.codcomprobante;

END
$$
DELIMITER ;



-- -------------------------------------
-- -------------------------------------
-- -------------------------------------

ALTER TABLE prospectos
ADD COLUMN cursointeres varchar(100) NULL after fecha_registro;


CREATE TABLE `importaciones` (
  `idimportacion` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombrearchivo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cantregistros` int NOT NULL DEFAULT '0',
  `iduser` bigint UNSIGNED NOT NULL,
  `uuidimportacion` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A',
  `userinsert` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'sistemas@padinsolutions.com',
  `dateinsert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userupdate` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dateupdate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idimportacion`),
  KEY `fk_iduser_importaciones` (`iduser`),
  CONSTRAINT `fk_iduser_importaciones` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
