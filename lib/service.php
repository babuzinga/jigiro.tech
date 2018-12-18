<?php

class Service_Startup {
  public static function connectDatabase() {
    DB::connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
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