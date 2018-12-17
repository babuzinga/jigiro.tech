DROP TABLE IF EXISTS `log_login`;
CREATE TABLE `log_login` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`userid` INT(11) NOT NULL DEFAULT '0',
	`dt` INT(11) NULL DEFAULT NULL,
	`ip` VARCHAR(16) NOT NULL DEFAULT '',
	`way` ENUM('login','autologin','remember','mobile','mail.ru','yandex.ru','gmail') NOT NULL DEFAULT 'login',
	PRIMARY KEY (`id`),
	INDEX `ll1` (`userid`, `dt`),
	INDEX `ll2` (`dt`)
)
COMMENT='Лог логирования пользователей'
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;
