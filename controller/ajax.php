<?php

class Controller_Ajax extends Controller {
  /**
   * TODO:: ДОРАБОТКИ
   *
   */
  public function SaveInstaMedia() {
    $cu = getCurrentUser();
    if (empty($cu))
      ajax(array2json(array('error' => 1)));

    $type = !empty($_GET['type']) ? Request::getStr('type') : false;
    $url  = Request::getStr('url');

    $expansion = !empty($type) ? '.mp4' : '.jpg';

    $filename = getFilename() . $expansion;
    $path     = getPath();
    $check    = DB::scalarSelect('SELECT id FROM medias WHERE user_id = ?i AND title = ?', $cu->id, $filename);
    if (!empty($check)) {
      DB::delete('medias', 'id='.$check);
    }

    $data = array(
      'dt_u'      => time(),
      'dt'        => date("Y-m-d H:i:s", time()),
      'user_id'   => $cu->id,
      'video'     => $type,
      'title'     => $filename,
      'link'      => $path . $filename,
    );

    checkDirs($path);

    if ($md5_file = DownloadPicture::Save($url, $path . $filename)) {
      $data['hash_sum'] = $md5_file;
      DB::insert('medias', $data);
      ajax(array2json(array('complete' => 1)));
    } else {
      ajax(array2json(array('error' => 1)));
    }
  }



  /**
   * TODO:: ДОРАБОТКИ
   *
   */

  // http://www.jt1.local/ajax/RemoveInstaMedia?id=2
  public function RemoveInstaMedia() {
    $cu = getCurrentUser();
    if (empty($cu))
      ajax(array2json(array('error' => 1)));

    $media_id = !empty($_GET['id']) ? Request::getStr('id') : false;
    $check    = DB::singleRow('SELECT * FROM medias WHERE user_id = ?i AND id = ?i', $cu->id, $media_id);

    if (empty($check)) {
      ajax(array2json(array('error' => 'Недостаточно прав на удаление')));
    }

    try {
      $original = BASE_DIR . '/data/originals/' . $check['link'];
      if (file_exists($original)) unlink($original);
      if (empty($check['video'])) {
        $cache = BASE_DIR . '/data/cache/' . str_replace('.jpg', '', $check['link']);
        array_map("unlink", glob($cache . '*'));
      }

      DB::delete('medias', 'id=' . $check['id']);
      ajax(array2json(array('complete' => 'ok')));
    } catch (Exception $e) {
      ajax(array2json(array('error' => $e->getMessage())));
    }
  }
}