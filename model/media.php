<?php

/**
 * Class Model_Media
 */
class Model_Media extends Model {

  function __construct($id = 0) {
    parent::__construct();
    $this->properties['medias'] = array(
      'id',
      'dt_u',
      'dt',
      'user_id',
      'isVideo',
      'hash_sum',
      'title',
      'link',
    );

    if (!empty($id)) $this->getData($id);
  }

  function getUrl() {
    return PROTOCOL . HOST_NAME . '/data/cache/' . $this->link;
  }
}