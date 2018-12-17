DROP TABLE IF EXISTS `rememberme`;
CREATE TABLE `rememberme` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`userid` INT(11) NOT NULL DEFAULT '0',
	`expire` INT(11) NOT NULL DEFAULT '0',
	`hash` VARCHAR(32) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	INDEX `rem_1` (`expire`),
	INDEX `rem_2` (`userid`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

