DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dt_u` INT(11) NOT NULL DEFAULT '0',
  `dt` DATETIME NULL DEFAULT NULL,
  `login` VARCHAR(200) NULL DEFAULT '' COMMENT 'логин',
  `password` VARCHAR(200) NULL DEFAULT '' COMMENT 'пароль в md5',
  PRIMARY KEY (`id`)
)
COMMENT='пользователи'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;