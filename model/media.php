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
      'filesize',
      'title',
      'link',
    );

    if (!empty($id)) $this->getData($id);
  }

  public function getOriginalUrl() {
    return PROTOCOL . HOST_NAME . '/data/originals/' . $this->link;
  }

  public function getVideo() {
    return $this->getOriginalUrl();
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

  public function getFilesize() {
    $file_size = $this->filesize;

    if($file_size > 1024){
      $file_size = ($file_size/1024);
      if($file_size > 1024){
        $file_size = ($file_size/1024);
        if($file_size > 1024) {
          $file_size = ($file_size/1024);
          $file_size = round($file_size, 1);
          return $file_size . " Gb";
        } else {
          $file_size = round($file_size, 1);
          return $file_size." Mb";
        }
      } else {
        $file_size = round($file_size, 1);
        return $file_size." Kb";
      }
    } else {
      $file_size = round($file_size, 1);
      return $file_size." byte";
    }
  }
}