SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `afterthought` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `afterthought` ;

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

-- -----------------------------------------------------
-- Data for table `afterthought`.`users`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `afterthought`;
INSERT INTO `afterthought`.`users` (`userid`, `fname`, `lname`, `email`, `nickname`) VALUES (1, 'John', 'Doe', 'admin@example.com', 'John');

COMMIT;

-- -----------------------------------------------------
-- Data for table `afterthought`.`roles`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `afterthought`;
INSERT INTO `afterthought`.`roles` (`roleid`, `description`, `name`) VALUES (1, 'Site Administrator, full privileges for all access.', 'Administrator');

COMMIT;

-- -----------------------------------------------------
-- Data for table `afterthought`.`authentication`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
USE `afterthought`;
INSERT INTO `afterthought`.`authentication` (`authenticationid`, `identity`, `password`, `salt`, `roleid`, `userid`, `resetPassword`, `disabled`) VALUES (NULL, 'admin@example.com', '8462e4dde835a9cbcaf906a52cd1731e845c1fb8aaed0fffcd781f8004cde292d3327e37926fe2d5a1d2e3b5420f9971c16cb03ddb8b89e33d4783981d4ccf6d', 'OYkjNtmVVrdwolePdHwB73MgfldxDVnd65laqCI1fU96OfBRWMrfm2gKTyhtIiP2', 1, 1, 0, 0);

COMMIT;
