-- CLIENTES --
ALTER TABLE `a2_clientes` ADD estado ENUM('ACTIVO','PENDIENTE','ELIMINADO') NULL;
UPDATE `a2_clientes` SET estado='ACTIVO';
-- INMUEBLES --
ALTER TABLE `a2_noticias` ADD `luz` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `gas` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `cloaca` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `agua` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `parrilla` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `salon_u_m` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `piscina` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `seguridad` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `amueblado` BOOLEAN  NULL DEFAULT FALSE ;

ALTER TABLE `a2_noticias` ADD `descripcion` TEXT NULL;
-- ALTER TABLE `a2_noticias` ADD `ambiente` TINYINT NULL;
ALTER TABLE `a2_noticias` ADD `apto_comercial` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `apto_profesional` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `portero_electrico` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `disposicion` ENUM('FRENTE','CONTRAFRENTE') NULL;
ALTER TABLE `a2_noticias` ADD `antiguedad` INTEGER NULL;
ALTER TABLE `a2_noticias` ADD `ambiente` TINYINT NULL;
ALTER TABLE `a2_noticias` ADD `ascensor` TINYINT NULL;
ALTER TABLE `a2_noticias` ADD `cochera` TINYINT NULL;
ALTER TABLE `a2_noticias` ADD `patio` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `balcon` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_noticias` ADD `barrio` INTEGER NULL;
ALTER TABLE `a2_noticias` MODIFY COLUMN `barrio`  VARCHAR(255) NULL;
ALTER TABLE `a2_noticias` ADD estado_reg ENUM('ACTIVO','PENDIENTE','ELIMINADO') NULL;
ALTER TABLE `a2_noticias` ADD `porcion` TINYINT NULL;
ALTER TABLE `a2_noticias` ADD `codigo_postal` INT NULL;

ALTER TABLE a2_noticias MODIFY COLUMN frente FLOAT;
ALTER TABLE a2_noticias MODIFY COLUMN fondo FLOAT;

UPDATE `a2_noticias` SET estado_reg='ACTIVO';
--REMOVER LOS CAMPOS QUE SON OBLIGATORIOS----

-- CONTRATO --
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `plazo` TINYINT  NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `tipo_contrato` ENUM('LOCACION','COMODATO') NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `firma_representante` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `representante` VARCHAR(255) NOT NULL AFTER `firma_representante`;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `representante_cuit` VARCHAR(15) NOT NULL AFTER `representante`;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `locador` INTEGER NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `locador_1` INTEGER NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `locador_2` INTEGER NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `inquilino_1` INTEGER NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `inquilino_2` INTEGER NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `deposito_garantia` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `deposito_monto` DECIMAL(10,2) NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `deposito_cuotas` TINYINT NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `deposito_contrato_monto` DECIMAL(10,2) NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `excento` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `destino_contrato` ENUM('LOCAL COMERCIAL','DOMESTICO','OFICINAS');
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `honorarios` BOOLEAN  NULL DEFAULT FALSE ;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `excento_monto` DECIMAL(10,2) NULL;	
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `excento_cuotas` DECIMAL(10,2) NULL;	
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `estado_renovacion` ENUM('RENOVADO','NUEVO') DEFAULT 'NUEVO';
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `contrato_firmado` BOOLEAN  NULL DEFAULT FALSE;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `fecha_firma_contrato` DATE  NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `fecha_firma_convenio` DATE  NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `expensas` TEXT NOT NULL AFTER `tiene_expensas`;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `nota` TEXT NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `ultimo_contacto` DATETIME NULL;
ALTER TABLE `a2_operaciones_inmobiliarias` ADD `usuario_contacto` VARCHAR(255) NULL;


ALTER TABLE `a2_operaciones_inmobiliarias` ADD estado ENUM('ACTIVO','PENDIENTE','RENOVADO','FINALIZADO','ELIMINADO') NULL;
UPDATE `a2_operaciones_inmobiliarias` SET estado='ACTIVO';

--- MOVIMIENTOS ---
ALTER TABLE `a2_movimientos` ADD `propiedad_id` INTEGER NULL;
ALTER TABLE `a2_movimientos` ADD `operacion_id` INTEGER NULL;
ALTER TABLE `a2_movimientos` ADD `data` TEXT NULL;

--- ITEMS OPERACION INMOBILIARIA----------
ALTER TABLE `a2_operaciones_items` ADD estado ENUM('ACTIVO','PENDIENTE','ELIMINADO') NULL;
UPDATE `a2_operaciones_items` SET estado='ACTIVO';

-------- EXPENSAS -----------
ALTER TABLE `operaciones_expensas` ADD estado_reg ENUM('ACTIVO','PENDIENTE','ELIMINADO') NULL;
UPDATE `operaciones_expensas` SET estado_reg='ACTIVO';
ALTER TABLE `operaciones_expensas` ADD `grupo_expensas_id` INTEGER NULL;

-- LIQUIDACION --
ALTER TABLE `a2_liquidaciones` ADD estado ENUM('ACTIVO','PENDIENTE','PREIMPRESO','PAGADO','ELIMINADO') NULL;
UPDATE `a2_liquidaciones` SET estado='ACTIVO' WHERE fecha_pago IS NULL OR fecha_pago='0000-00-00 00:00:00';
UPDATE `a2_liquidaciones` SET estado='PAGADO' WHERE fecha_pago IS NOT NULL;

-- INMUEBLES PROPIETARIOS --
ALTER TABLE `inmuebles_propietarios` ADD `porcentaje` FLOAT NULL AFTER comision;
ALTER TABLE `inmuebles_propietarios` ADD `estado` ENUM('ACTIVO','ELIMINADO') NULL DEFAULT 'ACTIVO';

-- PROPIETARIOS --
ALTER TABLE `propietarios` ADD `localidad` VARCHAR(255) NOT NULL AFTER `direccion`;
ALTER TABLE `propietarios` ADD `provincia` VARCHAR(255) NOT NULL AFTER `localidad`;

-- TABLAS --
CREATE  TABLE IF NOT EXISTS `dinamica`.`plantillas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `titulo` VARCHAR(255) NULL ,
  `texto` TEXT NULL ,
  `estado` ENUM('ACTIVO','ELIMINADO') NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `dinamica`.`llaves` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `inmueble_id` INT NULL ,
  `numero_llave` VARCHAR(45) NULL ,
  `inmobiliaria_id` INT NULL ,
  `fecha_solicitud` DATETIME NULL ,
  `tipo_solicitud` ENUM('PRESTAMO','DEVOLUCION') NULL ,
  `persona` VARCHAR(255) NULL ,
  `observacion` TEXT NULL ,
  `fecha_devolucion` DATETIME NULL ,
  `usuario_devolucion` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `dinamica`.`contratos_documentos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `operacion_inmobiliaria_id` INT NULL ,
  `texto` TEXT NULL ,
  `estado` ENUM('ACTIVO','PENDIENTE','ELIMINADO') NULL ,
  `usuario_create` VARCHAR(45) NULL ,
  `time_create` DATETIME NULL ,
  `usuario_update` VARCHAR(45) NULL ,
  `time_update` DATETIME NULL ,
  `plantilla_id` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

--CREATE  TABLE IF NOT EXISTS `dinamica_prueba`.`inmueble_porcentaje_propietario` (
--  `id` INT NOT NULL AUTO_INCREMENT ,
--  `inmueble_id` INT NULL ,
--  `porcentaje` FLOAT NULL ,
--  `propietario_id` INT NULL ,
--  `estado` ENUM('ACTIVO','ELIMINADO') NULL ,
--  PRIMARY KEY (`id`) )
--ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `dinamica`.`Gastos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `inmueble_id` INT NULL ,
  `operacion_id` INT NULL ,
  `fecha` DATE NULL ,
  `importe` DECIMAL(10,2) NULL ,
  `estado` ENUM('PENDIENTE','PAGADO') NULL DEFAULT 'PENDIENTE' ,
  `observacion` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `dinamica`.`liqpagadas_gastos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `liquidacionpagadas_id` INT NULL ,
  `gastos_id` VARCHAR(45) NULL ,
  `importe` DECIMAL(10,2) NULL ,
  `fecha_carga` DATETIME NULL ,
  `movimientos_id` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `dinamica`.`liqpagadas_expensas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `liquidacionpagadas_id` INT NULL ,
  `expensa_id` INT NULL ,
  `importe` DECIMAL(10,2) NULL ,
  `fecha_carga` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `dinamica`.`gestion_cobranzas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fecha` DATE NULL ,
  `fecha_notificacion` DATE NULL ,
  `hora` TIME NULL ,
  `cliente_id` INT NULL ,
  `inmueble_id` INT NULL ,
  `operacion_id` INT NULL ,
  `nivel` TINYINT NULL ,
  `observaciones` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `dinamica`.`grupo_expensas` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `grupo_id` INT NULL ,
  `tipo_expensa_id` INT NULL ,
  `mes` INT NULL ,
  `year` INT NULL ,
  `importe` DECIMAL(10,2) NULL ,
  `expensas_por` ENUM('GRUPO','RUBRO','MULTIPLE') NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `dinamica`.`auditoria_contratos` (
  `id` INT NOT NULL,
  `operacion_id` INT NULL,
  `fecha_contrato` DATE NULL,
  `fecha_procesamiento` DATE NULL,
  `estado_contrato` ENUM('RENOVADO', 'ELIMINADO', 'PENDIENTE', 'NUEVO', 'FINALIZADO','ACTIVO') NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE `resume_periodos_cobro` (

`id` int NULL,

`operacion_id` int NULL,

`mes` int NULL,

`anio` int NULL,

`factura_A` decimal NULL,

`factura_B` decimal NULL,

`recibo_X` decimal NULL,

`documento_D` decimal NULL,

`recibo_comun` decimal NULL,

`total` decimal NULL,

PRIMARY KEY (`id`) 

);

CREATE TABLE `pagos_parciales` (

`id` int NULL,

`liquidacion_id` int NULL,

`monto` decimal NULL,

`estado` enum('anulado','pagado') NULL,

`data` text NULL,

`movimiento_id` int NULL

);


