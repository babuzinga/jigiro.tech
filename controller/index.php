<?php

class Controller_Index extends Controller {
  public function index() {
    $view = new View();
    $view->add('description', 'Сервис позваляет скачать фото и видео из Instagram онлайн. Для скачивания необходимо указать ссылку на пост в Instagram, нажать кнопку «Загрузить» и получить нужные фотографии или видео. Скачивание бесплатно и не требует регистарции. ');
    $view->template = 'index/index.tpl';

    return $view->render();
  }
}