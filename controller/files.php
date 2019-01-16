<?php

class Controller_Files extends Controller {
  public function Saved() {
    $cu = getCurrentUser();

    if (empty($cu))
      return View::error403('Раздел доступен только зарегистрированным пользователям');

    /**
     * Данные для постраничной навигации
     * TODO - вынести в отдельный класс
     */
    $current_page = empty($_GET['page']) ? 1 : Request::getInt('page');
    $limit        = 9;
    $offset       = ($current_page - 1) * $limit;
    $cnt          = DB::scalarSelect('SELECT COUNT(*) FROM medias WHERE user_id = ' . $cu->id);
    $page_count   = ceil($cnt / $limit);

    $media = new Model_Media();
    $medias = $media->findByQuery('SELECT * FROM medias WHERE user_id = ' . $cu->id, 'id DESC', $limit, $offset);

    $view = new View();
    $view->add('medias', $medias);
    $view->add('cnt', $cnt);
    $view->add('current_page', $current_page);
    $view->add('page_count', $page_count);

    // Подгрузка рецептов
    if (!empty($_GET['mode']) && $_GET['mode'] == "upload") {
      $view->template = 'files/items.tpl';
      $view->add('upload', true);
      $template = $view->render();
      ajax(array2json(array('complete' => $template)));
    }

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