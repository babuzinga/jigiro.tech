<?php

/**
 * Class Model_Profile
 */
class Model_Profile extends Model {

  function __construct($id = 0) {
    parent::__construct();
    $this->properties['profiles'] = array(
      'id',
      'dt_u',
      'dt',
      'login',
      'password',
    );

    if ($id) $this->getData($id);
  }

  function getUrl() {
    return PROTOCOL . HOST_NAME . '/user/' . $this->login . '/';
  }
}