<?
class DownloadPicture {
  static function Save($url, $target) {
    $target = BASE_DIR . '/data/originals/' . $target;

    // загружаем картинку с указанного УРЛ
    $temp_path = BASE_DIR . '/tmp/' . md5($url . microtime());

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

    // проверим картинка ли?
    $info = getimagesize($temp_path);
    if ($info[0] == 0 || $info[1] == 0) {
      unlink($temp_path);

      return false;
    }

    //UploadPicture::CheckDirs($fn);
    if (file_exists($target)) unlink($target); // удаляем предыдущую версию, если она есть

    copy($temp_path, $target);
    unlink($temp_path);

    return true;
  }
}