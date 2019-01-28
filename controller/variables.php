<?php

class Controller_Variables extends Controller {
  /**
   * http://www.jt1.local/variables
   *
   * Метод для работы с перемнными пользователя, полученных через API запросы
   *
   * ALTER TABLE `profiles` ADD COLUMN `token` VARCHAR(200) NULL DEFAULT '' COMMENT 'хэш идентификатор (токен)' AFTER `password`;
   *
   * @return string
   */
  public function Index() {
    $token = Request::getStr('token');
    if (empty($token))
      return View::error('Ошибка', 'Значение переменной token не указано');

    $token = DB::scalarSelect('SELECT token FROM profiles WHERE token=?', $token);
    if (empty($token))
      return View::error('Ошибка', 'Указанный token не найден');

    $view = new View();
    $view->add('token', $token);
    $view->template = 'variables/index.tpl';
    return $view->render();
  }
}