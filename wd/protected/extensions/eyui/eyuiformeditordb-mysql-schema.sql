CREATE TABLE `eyuiformeditordb` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `model_id` INT NOT NULL,
  `form_id` VARCHAR(45) NOT NULL,
  `item` INT NOT NULL,
  `parent_id` INT,
  `item_id` VARCHAR(45) NOT NULL,
  `label` VARCHAR(250) NOT NULL,
  `descr` VARCHAR(250),
  `position` INTEGER UNSIGNED DEFAULT 0,
  `data` BLOB,
  INDEX `eyuiformeditor_index` (`model_id` ASC,`form_id` ASC) ,
  INDEX `eyuiformeditor_index_item` (`model_id` ASC,`form_id` ASC, `item` ASC) ,
  INDEX `eyuiformeditor_parent` (`parent_id` ASC) ,
  PRIMARY KEY (`id`)
 );