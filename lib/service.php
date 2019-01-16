<?php

class Service_Startup {
  public static function connectDatabase() {
    DB::connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
  }

  public static function setResolution() {
    $rows = DB::getRows('SELECT * FROM medias WHERE video = 0 AND i_width = 0');
    if (!empty($rows)) :
      foreach ($rows as $item) {
        list($width, $height, $type, $attr) = getimagesize(BASE_DIR . '/data/originals/' . $item['link']);
        DB::update('medias', $item['id'], array('i_width' => $width, 'i_height' => $height));
      }
    endif;
  }

  public static function sendHeaders() {
    // запретим кэширование в браузере - Опера глючит из-за него?
    header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    header('Content-type: text/html; charset=utf-8');
  }
}