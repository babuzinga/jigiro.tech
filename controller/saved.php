<?php

class Controller_Saved extends Controller {
  public function Index() {
    $cu = getCurrentUser();

    if (empty($cu))
      return View::error403('Раздел доступен только зарегистрированным пользователям');

    $media = new Model_Media();
    $medias = $media->findByQuery('SELECT * FROM medias WHERE user_id = ' . $cu->id);

    $view = new View();
    $view->add('medias', $medias);
    $view->template = 'saved/index.tpl';

    return $view->render();
  }
}