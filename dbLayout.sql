SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `35110m24661_3` ;
CREATE SCHEMA IF NOT EXISTS `35110m24661_3` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `35110m24661_3` ;

-- -----------------------------------------------------
-- Table `35110m24661_3`.`qr_ralleys`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_3`.`qr_ralleys` ;

CREATE TABLE IF NOT EXISTS `35110m24661_3`.`qr_ralleys` (
  `rID` INT NOT NULL AUTO_INCREMENT,
  `rName` TEXT NOT NULL,
  `rStart` TIMESTAMP NULL,
  `rEnd` TIMESTAMP NULL,
  `rSnippetsDelay` DOUBLE NULL,
  `rPassword` LONGTEXT NULL,
  `rMail` LONGTEXT NOT NULL,
  PRIMARY KEY (`rID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `35110m24661_3`.`qr_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_3`.`qr_items` ;

CREATE TABLE IF NOT EXISTS `35110m24661_3`.`qr_items` (
  `iID` INT NOT NULL AUTO_INCREMENT,
  `iSnippets` LONGTEXT NOT NULL,
  `iSolution` LONGTEXT NOT NULL,
  `iStart` TIMESTAMP NULL,
  `iEnd` TIMESTAMP NULL,
  PRIMARY KEY (`iID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `35110m24661_3`.`qr_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_3`.`qr_groups` ;

CREATE TABLE IF NOT EXISTS `35110m24661_3`.`qr_groups` (
  `gID` INT NOT NULL AUTO_INCREMENT,
  `gName` LONGTEXT NULL,
  `gHash` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`gID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `35110m24661_3`.`qr_ralleys_has_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_3`.`qr_ralleys_has_items` ;

CREATE TABLE IF NOT EXISTS `35110m24661_3`.`qr_ralleys_has_items` (
  `qr_ralleys_rID` INT NOT NULL,
  `qr_items_iID` INT NOT NULL,
  PRIMARY KEY (`qr_ralleys_rID`, `qr_items_iID`),
  INDEX `fk_qr_ralleys_has_qr_items_qr_items1_idx` (`qr_items_iID` ASC),
  INDEX `fk_qr_ralleys_has_qr_items_qr_ralleys_idx` (`qr_ralleys_rID` ASC),
  CONSTRAINT `fk_qr_ralleys_has_qr_items_qr_ralleys`
    FOREIGN KEY (`qr_ralleys_rID`)
    REFERENCES `35110m24661_3`.`qr_ralleys` (`rID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_qr_ralleys_has_qr_items_qr_items1`
    FOREIGN KEY (`qr_items_iID`)
    REFERENCES `35110m24661_3`.`qr_items` (`iID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `35110m24661_3`.`qr_groups_solved_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `35110m24661_3`.`qr_groups_solved_items` ;

CREATE TABLE IF NOT EXISTS `35110m24661_3`.`qr_groups_solved_items` (
  `qr_groups_gID` INT NOT NULL,
  `qr_items_iID` INT NOT NULL,
  `giSolvedAt` TIMESTAMP NULL,
  PRIMARY KEY (`qr_groups_gID`, `qr_items_iID`),
  INDEX `fk_qr_groups_has_qr_items_qr_items1_idx` (`qr_items_iID` ASC),
  INDEX `fk_qr_groups_has_qr_items_qr_groups1_idx` (`qr_groups_gID` ASC),
  CONSTRAINT `fk_qr_groups_has_qr_items_qr_groups1`
    FOREIGN KEY (`qr_groups_gID`)
    REFERENCES `35110m24661_3`.`qr_groups` (`gID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_qr_groups_has_qr_items_qr_items1`
    FOREIGN KEY (`qr_items_iID`)
    REFERENCES `35110m24661_3`.`qr_items` (`iID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
