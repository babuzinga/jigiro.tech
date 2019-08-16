DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `budget_id` VARCHAR(20) NULL DEFAULT '' COMMENT 'идентификатор',
  `budget_data` TEXT NULL DEFAULT '' COMMENT 'Данные',
  `budget_dt_start` INT(11) NULL DEFAULT NULL,
  `budget_dt_end` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
COMMENT='бюджет'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;