DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL DEFAULT '0',
  `hash` VARCHAR(20) NULL DEFAULT '' COMMENT 'Идентификатор бюджета',
  `dt_start` INT(11) NULL DEFAULT NULL COMMENT 'Дата начала периода',
  `dt_end` INT(11) NULL DEFAULT NULL COMMENT 'Дата окончания периода',
  `days` INT(11) NULL DEFAULT NULL COMMENT 'Продолжительность',
  `amount` INT(11) NULL DEFAULT NULL COMMENT 'Сумма',
  `balance` INT(11) NULL DEFAULT NULL COMMENT 'Остаток',
  `expense` FLOAT NULL DEFAULT NULL COMMENT 'Лимит в день',
  `source` TEXT NULL DEFAULT '' COMMENT 'Источники дохода',
  `costs` TEXT NULL DEFAULT '' COMMENT 'Источники расхода',
  PRIMARY KEY (`id`)
)
COMMENT='бюджет'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;