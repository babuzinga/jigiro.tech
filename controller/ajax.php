<?php

class Controller_Ajax extends Controller {
  public function SaveInstaMedia() {
    $user_id = 1;
    $type = !empty($_GET['type']) ? Request::getStr('type') : false;
    $url  = Request::getStr('url');

    $expansion = !empty($type) ? '.mp4' : '.jpg';
    /**
     * TODO:: ДОРАБОТКИ
     * - получить хэш-сумма файла
     *
     *
     */
    $filename = getFilename() . $expansion;
    $path     = getPath();
    $check    = DB::scalarSelect('SELECT id FROM medias WHERE user_id = ?i AND filename = ?', $user_id, $filename);
    if (!empty($check)) {
      DB::delete('medias', 'id='.$check);
    }

    $data = array(
      'dt_u'      => time(),
      'dt'        => date("Y-m-d H:i:s", time()),
      'user_id'   => $user_id,
      'isVideo'   => $type,
      'hash_sum'  => $url,
      'title'     => $filename,
      'link'      => $path . $filename,
    );

    checkDirs($path);

    if (DownloadPicture::Save($url, $path . $filename)) {
      DB::insert('medias', $data);
      ajax(array2json(array("complete" => $type)));
    } else {
      ajax(array2json(array("error" => 1)));
    }
  }
}