<?php

class Controller_Files extends Controller {
  public function Saved() {
    $cu = getCurrentUser();

    if (empty($cu))
      return View::error403('Раздел доступен только зарегистрированным пользователям');

    $media = new Model_Media();
    $medias = $media->findByQuery('SELECT * FROM medias WHERE user_id = ' . $cu->id);

    $view = new View();
    $view->add('medias', $medias);
    $view->template = 'files/saved.tpl';

    return $view->render();
  }
}