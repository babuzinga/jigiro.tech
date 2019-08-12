DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dt_u` INT(11) NOT NULL DEFAULT '0',
  `dt` DATETIME NULL DEFAULT NULL,
  `login` VARCHAR(200) NULL DEFAULT '' COMMENT 'логин',
  `password` VARCHAR(200) NULL DEFAULT '' COMMENT 'пароль в md5',
	`token` VARCHAR(200) NULL DEFAULT '' COMMENT 'хэш идентификатор (токен)',
  PRIMARY KEY (`id`)
)
COMMENT='пользователи'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

-- babuzinga!23
INSERT INTO `profiles` (`id`, `dt_u`, `dt`, `login`, `password`) VALUES (1, 1545006459, '2018-12-17 10:27:56', 'jigiro', 'c4cdaded90fb6649d333b7d71de28fa5 ');