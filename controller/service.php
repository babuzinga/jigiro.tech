<?php

class Controller_Service extends Controller {
  public function screen_size() {
    $view = new View();
    $view->template = 'service/screen-size.tpl';
    $view->add('user_ip', $_SERVER['REMOTE_ADDR']);
    $view->add('user_agent', $_SERVER['HTTP_USER_AGENT']);

    return $view->render();
  }

  public function use_curl() {
    $view = new View();
    $view->template = 'service/use-curl.tpl';

    return $view->render();
  }

  public function Services() {
    $services = array(
      '/service/screen-size/' => 'Информация о размеры и разрешении экрана',
      '/service/use-curl/' => 'Отправка curl-запросов на указанный адрес с передачей get или post параметров',
      '/service/money/' => 'Расчет и контроль денег на заданный период',
    );

    $view = new View();
    $view->add('services', $services);
    $view->template = 'service/services.tpl';

    return $view->render();
  }

  public function money() {
    $view = new View();
    $view->template = 'service/money.tpl';

    return $view->render();
  }
}