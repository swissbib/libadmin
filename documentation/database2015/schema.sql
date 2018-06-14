SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0;
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0;
SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'TRADITIONAL';


-- -----------------------------------------------------
-- Table `institution`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `institution`;

CREATE TABLE IF NOT EXISTS `institution` (
		`id`          INT          NOT NULL AUTO_INCREMENT,
		`bib_code`    VARCHAR(10)  NULL,
		`sys_code`    VARCHAR(10)  NULL,
		`is_active`   TINYINT(1)   NULL,
		`label_de`    VARCHAR(100) NULL,
		`label_fr`    VARCHAR(100) NULL,
		`label_it`    VARCHAR(100) NULL,
		`label_en`    VARCHAR(100) NULL,
		`name_de`     VARCHAR(200) NULL,
		`name_fr`     VARCHAR(200) NULL,
		`name_it`     VARCHAR(200) NULL,
		`name_en`     VARCHAR(200) NULL,
		`url_de`      VARCHAR(255) NULL,
		`url_fr`      VARCHAR(255) NULL,
		`url_it`      VARCHAR(255) NULL,
		`url_en`      VARCHAR(255) NULL,
		`address`     TEXT         NULL,
		`zip`         MEDIUMINT    NULL,
		`city`        VARCHAR(100) NULL,
		`country`     VARCHAR(10)  NULL,
		`canton`      VARCHAR(10)  NULL,
		`website`     VARCHAR(255) NULL,
		`email`       VARCHAR(100) NULL,
		`phone`       VARCHAR(45)  NULL,
		`skype`       VARCHAR(45)  NULL,
		`facebook`    VARCHAR(45)  NULL,
		`coordinates` VARCHAR(100) NULL,
		`isil`        VARCHAR(45)  NULL,
		`notes`       TEXT         NULL,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARSET =utf8
		DEFAULT COLLATE utf8_general_ci;


-- -----------------------------------------------------
-- Table `group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `group`;

CREATE TABLE IF NOT EXISTS `group` (
		`id`        INT          NULL AUTO_INCREMENT,
		`code`      VARCHAR(45)  NULL,
		`label_de`  VARCHAR(100) NULL,
		`label_fr`  VARCHAR(100) NULL,
		`label_it`  VARCHAR(100) NULL,
		`label_en`  VARCHAR(100) NULL,
		`notes`     TEXT         NULL,
		`is_active` TINYINT(1)   NULL,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARSET =utf8
		DEFAULT COLLATE utf8_general_ci;


-- -----------------------------------------------------
-- Table `view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `view`;

CREATE TABLE IF NOT EXISTS `view` (
		`id`        INT         NOT NULL AUTO_INCREMENT,
		`code`      VARCHAR(45) NULL,
		`label`     VARCHAR(45) NULL,
		`is_active` TINYINT(1)  NULL,
		`notes`     TEXT        NULL,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARSET =utf8
		DEFAULT COLLATE utf8_general_ci;


-- -----------------------------------------------------
-- Table `mm_institution_group_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mm_institution_group_view`;

CREATE TABLE IF NOT EXISTS `mm_institution_group_view` (
		`id_view`        INT        NOT NULL,
		`id_group`       INT        NOT NULL,
		`id_institution` INT        NOT NULL,
		`is_favorite`    TINYINT(1) NULL,
		`position`       INT        NULL,
		PRIMARY KEY (`id_group`, `id_institution`, `id_view`),
		CONSTRAINT `fk_link_institution1`
		FOREIGN KEY (`id_institution`)
		REFERENCES `institution` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
		CONSTRAINT `fk_link_view1`
		FOREIGN KEY (`id_view`)
		REFERENCES `view` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
		CONSTRAINT `fk_link_network1`
		FOREIGN KEY (`id_group`)
		REFERENCES `group` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
		ENGINE = InnoDB
		DEFAULT CHARSET =utf8
		DEFAULT COLLATE utf8_general_ci;


-- -----------------------------------------------------
-- Table `mm_group_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mm_group_view`;

CREATE TABLE IF NOT EXISTS `mm_group_view` (
		`id_group` INT NOT NULL,
		`id_view`  INT NOT NULL,
		`position` INT NULL,
		PRIMARY KEY (`id_view`, `id_group`),
		INDEX `group` (`id_group` ASC),
		INDEX `view` (`id_view` ASC),
		CONSTRAINT `fk_mm_group_view_group1`
		FOREIGN KEY (`id_group`)
		REFERENCES `group` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION,
		CONSTRAINT `fk_mm_group_view_view1`
		FOREIGN KEY (`id_view`)
		REFERENCES `view` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION)
		ENGINE = InnoDB
		DEFAULT CHARSET =utf8
		DEFAULT COLLATE utf8_general_ci;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user`;

CREATE TABLE IF NOT EXISTS `user` (
		`id`        INT         NOT NULL AUTO_INCREMENT,
		`username`  VARCHAR(45) NULL,
		`password`  VARCHAR(45) NULL,
		`is_active` TINYINT(1)  NULL,
		`is_admin`  TINYINT(1)  NULL,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARSET =utf8
		DEFAULT COLLATE utf8_general_ci;


-- -----------------------------------------------------
-- Table `log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `log`;

CREATE TABLE IF NOT EXISTS `log` (
		`id`      INT          NOT NULL AUTO_INCREMENT,
		`date`    DATETIME     NULL,
		`id_view` INT          NULL,
		`host`    VARCHAR(100) NULL,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARSET =utf8
		DEFAULT COLLATE utf8_general_ci;


SET SQL_MODE = @OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;