<?php

class Controller_Service extends Controller {
  public function setResolution() {
    $rows = DB::getRows('SELECT * FROM medias WHERE video = 0 AND i_width = 0');
    if (!empty($rows)) :
      foreach ($rows as $item) {
        list($width, $height, $type, $attr) = getimagesize(BASE_DIR . '/data/originals/' . $item['link']);
        DB::update('medias', $item['id'], array('i_width' => $width, 'i_height' => $height));
      }
    endif;
  }
}