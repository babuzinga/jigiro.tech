<?php

/*
 * Удаление каталога и его содержимого
 * Рекурсивная функция удаления каталога с произвольной степенью вложенности
 */
function complete_removal_directory($directory) {
  // Открываем каталог
  $dir = opendir($directory);
  // В цикле выводим его содержимое
  while (($file = readdir($dir)) !== false) {
    // Если функция readdir() вернула файл — удаляем его
    if (is_file("$directory/$file")) unlink("$directory/$file");
    // Если функция readdir() вернула каталог и он
    // не равен текущему или родительскому — осуществляем
    // рекурсивный вызов complete_removal_directory() для этого каталога
    else if (is_dir("$directory/$file") && $file != "." && $file != "..") {
      complete_removal_directory("$directory/$file");
    }
  }
  closedir($dir);
  rmdir($directory);

  return true;
}

// Возвращает полное имя контроллера
function collectNameController($name) {
  return 'Controller_' . ucfirst(strtolower($name));
}

// Возвращает полное имя модели
function collectNameModel($name) {
  return 'Model_' . ucfirst(strtolower($name));
}

function array2json($array = array()) {
  return json_encode($array);
}

function getFormatDate($date) {
  return date("H:i:s d-m-Y", $date);
}

function print_array($array = array(), $exit = false) {
  echo "<pre>" . print_r($array, true) . "</pre>";
  if ($exit) exit();
}

function ajax($str) {
  echo $str;
  exit;
}

function isSmartPhone() {
  return preg_match('/(?:tablet|ipad|ipod|mobile|mini|phone|symbian|android|ios|blackberry|webos)/', strtolower($_SERVER['HTTP_USER_AGENT']));
}

function getPath() {
  date_default_timezone_set("Europe/Moscow");
  return date('Y') . strtolower(date('M')) . '/' . date('d') . '/' . date('i') . '/';
}

function getFilename() {
  DB::query('INSERT INTO filenames (foo) VALUES (1)');
  $id = DB::getInsertId();
  DB::query('DELETE FROM filenames WHERE id<' . $id);

  return $id . '_' . rand(10000, 99999);
}

function checkDirs($fn) {
  // поскольку файл создается по датам, то
  // создаем нужные директории при необходимости
  $oldumask = umask(0);
  $dirs = explode('/', $fn);

  $path = '';
  foreach ($dirs as $dir) {
    $path .= '/' . $dir;
    if (!file_exists(BASE_DIR . '/data/originals' . $path)) {
      mkdir(BASE_DIR . '/data/originals' . $path, 0775);
    }
    if (!file_exists(BASE_DIR . '/data/cache' . $path)) {
      mkdir(BASE_DIR . '/data/cache' . $path, 0775);
    }
  }

  umask($oldumask);
}

function instr($str, $substr) {
  return is_int(strpos($str, $substr));
}





function getCurrentUser() {
  return new Model_Profile(1); //$GLOBALS['runtime_current_user'];
}
