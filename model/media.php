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
      'video',
      'hash_sum',
      'title',
      'link',
    );

    if (!empty($id)) $this->getData($id);
  }

  function getVideo() {
    return PROTOCOL . HOST_NAME . '/data/originals/' . $this->link;
  }

  public function getImage($type = '') {
    if ($this->link) {
      $size   = ($type ? '-' . $GLOBALS['THUMB_SIZES'][$type] : '') . '.jpg';
      $image  = PROTOCOL . HOST_NAME . '/data/cache/' . $this->link;
      return  str_replace('.jpg', $size, $image);
    } else {
      $str = PROTOCOL . HOST_NAME . '/public/image/noimagelarge.png';
      return $str;
    }
  }
}