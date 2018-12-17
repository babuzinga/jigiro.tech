<?php

class Controller_User extends Controller {
  public function Index() {
    $view = new View();

    if (empty($user)) {
      $view->template = 'user/logon.tpl';
    } else {
      $view->add('user', $user);
      $view->template = 'user/index.tpl';
    }

    return $view->render();
  }

  public function Logon() {
    $login    = Request::postStr('login');
    $password = md5(Request::postStr('password'));


  }
}