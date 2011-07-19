SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `afterthought`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `afterthought`.`users` (
  `userid` INT NOT NULL AUTO_INCREMENT ,
  `fname` VARCHAR(50) NOT NULL ,
  `lname` VARCHAR(75) NOT NULL ,
  `email` VARCHAR(200) NOT NULL ,
  `nickname` VARCHAR(75) NULL ,
  PRIMARY KEY (`userid`) );


-- -----------------------------------------------------
-- Table `afterthought`.`roles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `afterthought`.`roles` (
  `roleid` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(500) NOT NULL ,
  `name` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`roleid`) );


-- -----------------------------------------------------
-- Table `afterthought`.`authentication`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `afterthought`.`authentication` (
  `authenticationid` INT NOT NULL AUTO_INCREMENT ,
  `identity` VARCHAR(250) NOT NULL ,
  `password` CHAR(128) NOT NULL ,
  `salt` CHAR(64) NOT NULL ,
  `roleid` INT NOT NULL DEFAULT 3 ,
  `userid` INT NOT NULL ,
  `resetPassword` TINYINT NOT NULL DEFAULT 1 ,
  `disabled` TINYINT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`authenticationid`) ,
  INDEX `fk_authentication_users1` (`userid` ASC) ,
  INDEX `fk_authentication_roles1` (`roleid` ASC) ,
  CONSTRAINT `fk_authentication_users1`
    FOREIGN KEY (`userid` )
    REFERENCES `afterthought`.`users` (`userid` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_authentication_roles1`
    FOREIGN KEY (`roleid` )
    REFERENCES `afterthought`.`roles` (`roleid` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
