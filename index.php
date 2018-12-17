<?php

if ($_SERVER['REQUEST_URI'] == '/ping/') { echo 'ok'; exit(); }

define('BASE_DIR', dirname(__FILE__));
ini_set('date.timezone', 'Asia/Vladivostok');
$start = microtime(true);




include BASE_DIR . '/config/server.local.php';
// Подключение всех файлов php c контроллерами, моделями и библиотеками
foreach(glob("lib/*.php") as $file) include $file;
foreach(glob("controller/*.php") as $file) include $file;
foreach(glob("model/*.php") as $file) include $file;

include 'lib/smarty3/Smarty.class.php';

DB::connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

// TODO --- http://php.net/manual/ru/language.exceptions.php
// Если переданны данные
try {
  list($controller_name, $method_name, $params) = Core::parse_url();
  $controller = new $controller_name();
  // Вызывается метод, с передачей массива параметров
  $response = $controller->run($method_name, $params);
} catch (Exception $e) {
  $controller = new View();
  $response = View::error404($e->getMessage());
}

echo $response;
mysql_close();





$time = microtime(true) - $start;
if (DEV_MODE) echo "
  <div id='debug'>
    <!-- controller_name :: {$controller_name}<br/>
    method_name :: {$method_name}<br/>
    params :: ".print_r($params,true)."<br/>
    <br/> -->
    " . round($time,6) . " сек.
  </div>
";
