SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `DBNAME` ;
CREATE SCHEMA IF NOT EXISTS `DBNAME` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `DBNAME` ;

-- -----------------------------------------------------
-- Table `DBNAME`.`qr_rallyes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `DBNAME`.`qr_rallyes` ;

CREATE TABLE IF NOT EXISTS `DBNAME`.`qr_rallyes` (
  `rID` INT NOT NULL AUTO_INCREMENT,
  `rName` TEXT NOT NULL,
  `rSnippetsDelay` DOUBLE NULL,
  `rPassword` LONGTEXT NULL,
  `rMail` LONGTEXT NOT NULL,
  PRIMARY KEY (`rID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBNAME`.`qr_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `DBNAME`.`qr_items` ;

CREATE TABLE IF NOT EXISTS `DBNAME`.`qr_items` (
  `iID` INT NOT NULL AUTO_INCREMENT,
  `iSnippets` LONGTEXT NOT NULL,
  `iSolution` LONGTEXT NOT NULL,
  `iStart` TIMESTAMP NULL,
  PRIMARY KEY (`iID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBNAME`.`qr_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `DBNAME`.`qr_groups` ;

CREATE TABLE IF NOT EXISTS `DBNAME`.`qr_groups` (
  `gID` INT NOT NULL AUTO_INCREMENT,
  `gName` LONGTEXT NULL,
  `gHash` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`gID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBNAME`.`qr_rallyes_has_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `DBNAME`.`qr_rallyes_has_items` ;

CREATE TABLE IF NOT EXISTS `DBNAME`.`qr_rallyes_has_items` (
  `qr_rallyes_rID` INT NOT NULL,
  `qr_items_iID` INT NOT NULL,
  PRIMARY KEY (`qr_rallyes_rID`, `qr_items_iID`),
  INDEX `fk_qr_ralleys_has_qr_items_qr_items1_idx` (`qr_items_iID` ASC),
  INDEX `fk_qr_ralleys_has_qr_items_qr_ralleys_idx` (`qr_rallyes_rID` ASC),
  CONSTRAINT `fk_qr_ralleys_has_qr_items_qr_ralleys`
    FOREIGN KEY (`qr_rallyes_rID`)
    REFERENCES `DBNAME`.`qr_rallyes` (`rID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_qr_ralleys_has_qr_items_qr_items1`
    FOREIGN KEY (`qr_items_iID`)
    REFERENCES `DBNAME`.`qr_items` (`iID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `DBNAME`.`qr_groups_solved_items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `DBNAME`.`qr_groups_solved_items` ;

CREATE TABLE IF NOT EXISTS `DBNAME`.`qr_groups_solved_items` (
  `qr_groups_gID` INT NOT NULL,
  `qr_items_iID` INT NOT NULL,
  `giSolvedAt` TIMESTAMP NULL,
  PRIMARY KEY (`qr_groups_gID`, `qr_items_iID`),
  INDEX `fk_qr_groups_has_qr_items_qr_items1_idx` (`qr_items_iID` ASC),
  INDEX `fk_qr_groups_has_qr_items_qr_groups1_idx` (`qr_groups_gID` ASC),
  CONSTRAINT `fk_qr_groups_has_qr_items_qr_groups1`
    FOREIGN KEY (`qr_groups_gID`)
    REFERENCES `DBNAME`.`qr_groups` (`gID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_qr_groups_has_qr_items_qr_items1`
    FOREIGN KEY (`qr_items_iID`)
    REFERENCES `DBNAME`.`qr_items` (`iID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
