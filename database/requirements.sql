-- CREATE A USER

CREATE USER 'admin'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON db_enquete.* TO 'admin'@'localhost';


-- CREATE TABLES

CREATE TABLE `db_enquete`.`tab_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NULL,
  `password` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE);
  
  CREATE TABLE `db_enquete`.`tab_enquete` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user` INT NOT NULL,
  `title` VARCHAR(40) NOT NULL,
  `date_start` VARCHAR(12) NOT NULL,
  `date_end` VARCHAR(12) NOT NULL,
  PRIMARY KEY (`id`));
  
  CREATE TABLE `db_enquete`.`tab_enquete_opcao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(40) NULL,
  `id_enquete` INT NULL,
  `votes` INT NULL,
  PRIMARY KEY (`id`));