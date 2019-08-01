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
}