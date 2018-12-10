<?php

/*

CREATE TABLE `note` (
`id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'идентификатор',
	`date_created` INT(11) NOT NULL COMMENT 'дата создания',
	`date_editing` INT(11) NOT NULL COMMENT 'дата редактирования',
	`note` TEXT NOT NULL COMMENT 'текст записи',
	PRIMARY KEY (`id`)
)
COMMENT='Заметки'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

*/

/**
 * Class Model_Note
 */
class Model_Note extends Model {

  function __construct($id = 0) {
    parent::__construct();
    $this->properties['note'] = array(
      'id',
      'date_created',
      'date_editing',
      'note',
    );

    if ($id) $this->getData($id);
  }
}