<?php

class View {
  public $renderer;
  public $template;

  public function __construct() {
    $this->renderer = new Smarty();

    // присваиваем глобальные переменные
    $this->renderer->assign('title', "Заголовок");
    $this->renderer->assign('description', "Описание");
    $this->renderer->assign('keywords', "Ключевые слова");
    $this->renderer->assign('author', "Автор");

    $this->renderer->assign('localhost', HOST_TYPE == 'local');
    $this->renderer->assign("host_name", PROTOCOL . HOST_NAME);

    if (defined("CURRENT_CONTROLLER_NAME")) $this->renderer->assign('current_controller', CURRENT_CONTROLLER_NAME);
    if (defined("CURRENT_METHOD_NAME"))     $this->renderer->assign('current_method', CURRENT_METHOD_NAME);
  }

  public function render() {
    return $this->renderer->fetch($this->template);
  }

  public function add($key, $value) {
    $this->renderer->assign($key, $value);
  }






  public static function error401($reason = false, $standart = 'У вас нет прав на просмотр этой страницы.') {
    header("HTTP/1.1 401 Unauthorized");
    header('WWW-Authenticate: Basic realm="Login"');

    $view = new View();
    $view->add('reason', (DEV_MODE && $reason) ? $reason : $standart);
    $view->template = 'errors/401.tpl';

    return $view->render();
  }

  public static function error403($reason = false, $standart = 'Доступ к странице запрещен.') {
    header('HTTP/1.0 403 Unauthorized');

    $view = new View();
    $view->add('reason', (DEV_MODE && $reason) ? $reason : $standart);
    $view->template = 'errors/403.tpl';

    return $view->render();
  }

  public static function error404($reason = false, $standart = 'Страница не найдена.') {
    header('HTTP/1.0 404 Not Found');

    $view = new View();
    $view->add('reason', (DEV_MODE && $reason) ? $reason : $standart);
    $view->template = 'templates/errors/404.tpl';

    return $view->render();
  }
}
