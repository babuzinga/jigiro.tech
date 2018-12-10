<?php

class Controller_Index extends Controller {
  public function index() {
    $view = new View();
    $view->add('message', '123');
    $view->template = 'index/index.tpl';

    return $view->render();
  }
}