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
ALTER TABLE `a2_noticias` ADD `ambiente` TINYINT NULL;
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


