DROP TABLE IF EXISTS `medias`;
CREATE TABLE `medias` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dt_u` INT(11) NOT NULL DEFAULT '0',
  `dt` DATETIME NULL DEFAULT NULL,
  `user_id` INT(11) NOT NULL DEFAULT '0',
  `video` INT(1) NOT NULL DEFAULT '0',
  `hash_sum` VARCHAR(200) NULL DEFAULT '' COMMENT 'хэш сумма файла',
  `title` VARCHAR(200) NULL DEFAULT '' COMMENT 'имя файла',
  `link` VARCHAR(200) NULL DEFAULT '' COMMENT 'полный путь до файла',
  PRIMARY KEY (`id`)
)
COMMENT='Сохраненые медиа файлы с Instagram'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;