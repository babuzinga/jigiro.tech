<?php

if ($_SERVER['REQUEST_URI'] == '/ping/') { echo 'ok'; exit(); }

define('BASE_DIR', dirname(__FILE__));
ini_set('date.timezone', 'Asia/Vladivostok');

$st = microtime(true);
$gen_time_sql = array();




include BASE_DIR . '/config/server.php';
include BASE_DIR . '/config/thumbs.php';
// Подключение всех файлов php c контроллерами, моделями и библиотеками
foreach(glob("lib/*.php") as $file) include $file;
foreach(glob("controller/*.php") as $file) include $file;
foreach(glob("model/*.php") as $file) include $file;

include 'lib/smarty3/Smarty.class.php';

Service_Startup::connectDatabase();
Service_Startup::sendHeaders();

Controller_User::rememberMe();

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





if (DEV_MODE) :
  $gen_time_php = round((microtime(true) - $st), 4);
  $dt_db        = array_sum($gen_time_sql);

  echo "
  <div id='debug'>
    <!-- controller_name :: {$controller_name}<br/>
    method_name :: {$method_name}<br/>
    params :: ".print_r($params,true)."<br/>
    <br/> -->

    <table>
    <tr>
      <td>php</td><td>&nbsp;:&nbsp;</td><td>{$gen_time_php}</td>
    </tr>
    <tr>
      <td>html</td><td>&nbsp;:&nbsp;</td><td><span>0</span></td>
    </tr>
    <tr>
      <td>sql</td><td>&nbsp;:&nbsp;</td><td>{$dt_db}</td>
    </tr>
    </table>
  </div>
";
endif;
