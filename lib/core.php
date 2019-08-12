<?php

/**
 * Основа сайта должна стройтся из следующих классах со следующим набором свойств/методов:
 * 1. Core - подлючение всех имеющихся библиотек, контроллеров, моделей, разбор url для получения имени контроллера, его метода и набора переменных ;
 * 2. Controller - родитель всех классов-контроллеров со стандартнеым набором методом и свойств ;
 * 3. Model - родитель всех классов-моделей со стандартнеым набором методом и свойств ;
 * 4. View - составление отображаемой страницы, набор страниц с ошибками, вывод стандартных блоков ошибок, галереи, инф.блоков и т.п. ;
 */

/**
 * Class Core
 *
 */
class Core {
  public $config;

  public function __construct() {
    // Чтение основного конфигурационного файла
    $this->config = parse_ini_file("/config/config.ini", true);
  }

  // array_shift - Извлекает первое значение массива array и возвращает его, сокращая размер array на один элемент. -> $result = array_splice($result, 1, count($result));
  // array_pop - Извлекает последний элемент массива -> unset($result[count($result)-1])

  // Разбор url
  /**
   * Алгоритм действий, по шагам:
   * 1.   Разбирает url/ссылку на массив по '/' - удаляя первый и последние элементы, если они пусты ;
   * 2.   По умолчанию, имя контроллера и метода Index ;
   * 3.   1-ый элемент массива, если он не пуст - это имя контроллера, осуществляется проверка наличия класса (был ли объявлен класс), после
   *      чего элемент удлаяется, путем сдвига остальных элементов влево ;
   * 4.   2-ой элемент массива, если он не пуст - это имя метода, которое проверяется, если такого метода в контроллере не существует, элемент
   *      массива будет считаться переданной переменной, если метод существует элемент удлаяется, путем сдвига остальных элементов влево ;
   * 5.   Оставшиеся элементы - параметры
   */

  /**
   * Парсер url - Controller / Method / Params
   * @return array
   * @throws Exception
   */
  public function parse_url() {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $path = $url['path'];
    $result = explode('/', $path);
    $controller = $method = 'index';
    $controller_class = collectNameController($controller);
    $error = false;
    if (!$result[0]) array_shift($result);
    if (!end($result)) array_pop($result);

    /**
     * Перед глобальной получением и проверкой имени Класса и его Метода (если он передан)
     * необходимо проверить наличия переданного url в конфигурационом файле
     */
    $result = $this->checkUrlMapper($result);

    // Controller
    if (!empty($result[0])) {
      $controller = strtolower($result[0]);
      $controller_class = collectNameController($controller);
      if (class_exists($controller_class)) {
        array_shift($result);
      } else {
        throw new Exception('Контроллер <span class="underline">' . $controller_class . '</span> не объявлен как класс.');
      }
    }
    define('CURRENT_CONTROLLER_NAME', $controller);

    // Method
    if (!empty($result[0])) {
      // Защита для корректной замены дефиса, т.е. чтобы это create_post != create-post
      $temp = urldecode($result[0]);
      $temp = str_replace("-", "_", str_replace("_", "", strtolower($temp)));
      if (method_exists($controller_class, $temp)) {
        $method = $temp;
        array_shift($result);
      } elseif (in_array($controller, array("api"))) {
        // Для некоторых контроллеров, сделана единая точка входа - метод onIndex
        $method = "index";
      } else {
        /**
         * Формирование данных для возвращения ошибки.
         * Поскольку в url явным образом имя метода может быть не указано (по умолчанию пойдет Index), а 2-ой аргумент
         * будет ялвятся переменная передавая методу Index, работа скрипта не останавливается а идет дальше, для проверки
         * целостности и корректности переменных для метода
         */
        throw new Exception('Метод <span class="underline">' . $temp . '</span> не объявлен в Контроллере <span class="underline">' . $controller_class . '</span>.');
      }
    } elseif(!method_exists($controller_class, $method)) {
      /**
       * В случае, если не в адресе странице не передано значение метода, оно по умолчанию будет равно Index
       * проверяем что такое метод, содержится в опеределенном классе
       */
      throw new Exception('Метод <span class="underline">' . $method . '</span> не объявлен в Контроллере <span class="underline">' . $controller_class . '</span>.');
    }

    define('CURRENT_METHOD_NAME', $method);

    // Params
    $params = $result;

    // Return result
    return array(
      $controller_class,
      $method,
      $params,
    );
  }

  /**
   * Проверка значений в маппинге конфигурационного файла
   */
  private function checkUrlMapper($result) {
    if ($url_mapper = $this->config['url-mapper']) {
      $url = '';
      foreach($result as $part) {
        $url .= empty($url) ? $part : '/'.$part;
        if ($val = $url_mapper[$url]) {
          $explode = explode('.', $val);
          // Запись может состоять из 3-х значение - page = <Controller>.<Method>.:params
          // <Controller> - контроллер, значение подставляется в 0 параметр стека значение
          $result[0] = $explode[0];
          // если существует :params, двигаем стек значений вперед на 1, чтобы освободить
          // 1 элемент для записи туда значения <Method>
          if (!empty($explode[2]) && $explode[2] == ':params') {
            $first = array_slice($result, 0, 1);
            $ending = array_slice($result, 1, count($result)-1);
            $result = array_merge($first, array('1' => 'method'), $ending);
          }
          // <Method> - метод контроллера, значение подставляется в 1 параметр стека значение
          $result[1] = $explode[1];
          break;
        }
      }
    }

    return $result;
  }
}