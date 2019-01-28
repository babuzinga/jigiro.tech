DROP TABLE IF EXISTS `api-variables`;
CREATE TABLE `api-variables` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hash_user` VARCHAR(200) NULL DEFAULT '' COMMENT 'хэш идентификатор пользователя',
  `var_name` VARCHAR(200) NULL DEFAULT '' COMMENT 'имя переменной',
  `var_value` VARCHAR(5) NULL DEFAULT '' COMMENT 'значение переменной',
  PRIMARY KEY (`id`)
)
COMMENT='Перемнные переданные через API'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;