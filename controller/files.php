<?php

class Controller_Files extends Controller {
  public function Saved() {
    $cu = getCurrentUser();

    if (empty($cu))
      return View::error403('Раздел доступен только зарегистрированным пользователям');

    $media = new Model_Media();
    $medias = $media->findByQuery('SELECT * FROM medias WHERE user_id = ' . $cu->id, 'id DESC');

    $view = new View();
    $view->add('medias', $medias);
    $view->template = 'files/saved.tpl';

    return $view->render();
  }

  public function Download() {
    $url    = Request::getStr('url');
    $video  = Request::getStr('video');
    $local  = Request::getStr('local');

    return DownloadPicture::ReturnFile($url, $video, $local);
  }
}