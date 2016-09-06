CREATE TABLE `eyuiformdb` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `model_id` INTEGER NOT NULL,
  `form_id` VARCHAR(45) NOT NULL,
  `field_name` VARCHAR(45) NOT NULL,
  `field_value` BLOB,
  INDEX `eyuiformdb_index` (`model_id` ASC,`form_id` ASC,`field_name` ASC) ,
  PRIMARY KEY (`id`)
 );