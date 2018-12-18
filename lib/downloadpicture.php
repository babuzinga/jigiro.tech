<?
class DownloadPicture {
  static function Save($url, $target) {
    $target = BASE_DIR . '/data/originals/' . $target;

    // загружаем картинку с указанного УРЛ
    $temp_path = BASE_DIR . '/tmp/' . md5($url . microtime());
    //
    $md5_file = self::getFile($url, $temp_path);

    /*
    // проверим картинка ли?
    $info = getimagesize($temp_path);
    if ($info[0] == 0 || $info[1] == 0) {
      unlink($temp_path);

      return false;
    }
    */

    if (file_exists($target)) unlink($target); // удаляем предыдущую версию, если она есть

    copy($temp_path, $target);
    unlink($temp_path);

    return $md5_file;
  }

  static function ReturnFile($url, $video, $local = false) {
    if (empty($local)) {
      // загружаем картинку с указанного УРЛ
      $temp_filename = md5($url . microtime());
      $temp_path = BASE_DIR . '/tmp/' . $temp_filename;
      self::getFile($url, $temp_path);
    } else {
      $temp_filename = DB::scalarSelect('SELECT title FROM medias WHERE link =? ', $url);
      $temp_path = BASE_DIR . '/data/originals/' . $url;
    }

    if (file_exists($temp_path)) {
      // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
      // если этого не сделать файл будет читаться в память полностью!
      if (ob_get_level()) {
        ob_end_clean();
      }

      $content_type = !empty($video) ? 'video/mp4' : 'image/jpeg';

      // заставляем браузер показать окно сохранения файла
      header('Content-Description: File Transfer');
      header('Content-Type: ' . $content_type);
      header('Content-Transfer-Encoding: Binary');
      header('Content-Disposition: attachment; filename="' . $temp_filename . '"');
      header('Content-Length: ' . filesize($temp_path));
      readfile($temp_path);

      if (empty($local)) unlink($temp_path);
      exit();
    }

    return View::error('Ошибка загрузки', 'Файл '.$temp_path.' не найден');
  }

  /**
   * @param $url
   * @param $temp_path
   * @return string
   */
  private function getFile($url, $temp_path) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $str = curl_exec($ch);
    curl_close($ch);
    $fp = fopen($temp_path, 'wb');
    fwrite($fp, $str);
    fclose($fp);

    return hash_file('md5', $temp_path);
  }
}