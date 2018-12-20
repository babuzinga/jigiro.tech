<?php

class Controller_Api extends Controller {
  const E_METHOD_NOT_FOUND = 1;
  const E_DATABASE_ERROR = 2;
  const E_WRONG_SEARCH_PARAMETERS = 3;
  const E_REQUIRED_PARAMETER_MISSING = 4;
  const E_INVALID_PARAMETER_VALUE = 5;
  const E_TOKEN_REUIRED = 6;
  const E_INVALID_TOKEN = 7;
  const E_RECORD_ALREADY_THERE = 8;
  const E_NOT_IMPLEMENTED = 65533;
  const E_CUSTOM_ERROR = 65534;
  const E_UNKNOWN_ERROR = 65535;
  const E_PAGE_NOTE_FOUND = 404;

  const VERSION_ACTUAL = 'actual';
  const VERSION_HAS_UPDATE = 'has-update';
  const VERSION_NEED_UPDATE = 'need-update';

  public $start;

  public static $action_map = array(
    'media' => 'GetMediaWithInstagram',
  );

  /**
   * Массив со списком возвращаемых ошибок
   * @var array
   */
  public static $errors_api = array(
    self::E_METHOD_NOT_FOUND => 'Method not found',
    self::E_DATABASE_ERROR => 'Error retrieving data from database',
    self::E_UNKNOWN_ERROR => 'Unknown error',
    self::E_REQUIRED_PARAMETER_MISSING => 'Required parameter missing: :param',
    self::E_INVALID_PARAMETER_VALUE => 'Invalid parameter value: :param',
    self::E_CUSTOM_ERROR => 'Error: :error',
    self::E_NOT_IMPLEMENTED => 'Not implemented',
    self::E_TOKEN_REUIRED => 'Token required',
    self::E_INVALID_TOKEN => 'Invalid token',
    self::E_RECORD_ALREADY_THERE => 'Record already there',
    // Ошибки парсина страницы Instagram
    self::E_PAGE_NOTE_FOUND => 'К сожалению, по указанной вами ссылке страница не найдена, либо это закрытый профиль Инстаграм, скачивание фото и видео с которого невозможно',
  );

  /**
   * @var bool Debug mode
   */
  public static $debug_mode = false;

  /**
   * @var int Cache time in seconds
   */
  public static $cache_time = 1;

  public function __construct() {
    $this->start = microtime(true);
    /*
    self::$debug_mode = (HOST_TYPE === 'local');

    if (isset($_GET['debug'])) {
      self::$debug_mode = (bool)$_GET['debug'];
    }

    if (self::$debug_mode) {
      error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
      ini_set('display_errors', 1);
    }
    */
  }

  public function index() {
    $action = trim(str_replace('.', '', $this->params[0]));

    if (isset(self::$action_map[$action])) $action = self::$action_map[$action];
    if ($action == 'index') return $this->errorResponse(self::E_METHOD_NOT_FOUND);

    if (method_exists($this, 'api' . $action)) {
      try {
        return $this->{'api' . $action}();
      } catch (Exception $e) {
        return $this->handleException($e);
      }
    }

    return $this->errorResponse(self::E_METHOD_NOT_FOUND);
  }

  /**
   * Доработки:
   * 1 -
   */
  public function apiGetMediaWithInstagram() {
    /**
     * Аналог http://instasave.ru
     *
     *
     * $response->graphql->shortcode_media
     * Example page :
     * 'https://www.instagram.com/p/BprckGJBJ2E/' - image
     * 'https://www.instagram.com/p/Bq4gXMhhoZi/' - collection
     * 'https://www.instagram.com/p/BgbLcqtnMPy/' - video
     * 'https://www.instagram.com/p/BrNBVs5AzNJ/' - collection video and images
     *
     * Варинат проверки
     * if (!empty($url) && preg_match('/^https\:\/\/www\.instagram\.com\//is', $url)) :
     */

    // Получение значения url-страницы с постом в Instagram
    $url = Request::getStr('instagramMediaPageUrl');
    // Проверяем что значение не пустое и валидное, т.е. эта ссылка на пост
    if (!empty($url) && stristr($url, 'www.instagram.com/p/') !== false) :
      $response = self::getInstagramPage($url);
      $object_data = $response->graphql->shortcode_media;

      if (empty($object_data)) $this->errorResponse(self::E_PAGE_NOTE_FOUND, array(), 404);
      $media = array();

      // Если в посте, отсутствует множество дочерних объектов,
      // т.е. в посте всего одно изображение или видео - получаем данные только по нему
      if (empty($object_data->edge_sidecar_to_children)) {
        $media[] = array(
          'isVideo' => !empty($object_data->is_video) ? 1 : 0,
          'url'     => (!empty($object_data->is_video)) ? $object_data->video_url : $object_data->display_url,
        );
      } else {
        // ... если дочерних объектов множество, прогоняем их все, для получения данных
        $children = $object_data->edge_sidecar_to_children->edges;
        foreach ($children as $item) {
          $media[] = array(
            'isVideo' => !empty($item->node->is_video) ? 1 : 0,
            'url'     => (!empty($item->node->is_video)) ? $item->node->video_url : $item->node->display_url,
          );
        }
      }

      $array_info = array(
        'url'         => $url,
        'media_id'    => !empty($object_data->id) ? $object_data->id : '',
        'owner_id'    => !empty($object_data->owner->id) ? $object_data->owner->id : '',
        'owner_login' => !empty($object_data->owner->username) ? $object_data->owner->username : '',
        'full_name'   => !empty($object_data->owner->full_name) ? $object_data->owner->full_name : '',
        'caption'     => !empty($object_data->edge_media_to_caption->edges[0]->node->text) ? $object_data->edge_media_to_caption->edges[0]->node->text : '',
        'medias'      => $media,
        'timestamp'   => !empty($object_data->taken_at_timestamp) ? $object_data->taken_at_timestamp : '',
      );

      $result = array(
        'info' => $array_info
      );
      $this->response($result);
    else :
      $this->errorResponse(self::E_INVALID_PARAMETER_VALUE, array(':param' => 'url'), 400);
    endif;
  }

  /**
   * @param $error_code
   * @param array|null $replacements
   * @param int $status
   */
  private function errorResponse($error_code, array $replacements = null, $status = 200) {
    $error_text = $replacements
      ? strtr(self::$errors_api[$error_code], $replacements)
      : self::$errors_api[$error_code];

    $this->response(array(
      'error' => (int)$error_code,
      'description' => $error_text,
    ), $status);
  }

  /**
   * Коды ошибок https://tech.yandex.ru/webmaster/doc/dg/reference/errors-docpage/
   * 400 Bad Request - Тело запроса не прошло валидацию.
   * 403 Forbidden - Действие недоступно, у приложения нет требуемых разрешений.
   * 404 Not Found - Ресурс по запрошенному пути не существует.
   * 405 Method Not Allowed - HTTP-метод не поддерживается для этого ресурса.
   * 410 Gone - Ресурс недоступен.
   * 413 Payload Too Large - Размер файла превышает ограничения.
   * 415 Unsupported Media Type - Тип контента запроса не поддерживается.
   * 429 Too Many Requests - Превышен лимит на количество запросов.
   *
   * @param $data
   * @param int $status
   * @param bool|true $exit
   */
  private function response($data, $status = 200, $exit = true) {
    header('Content-Type: application/json; charset="utf-8"', true, $status);
    if (self::$debug_mode) {
      if (filter_input(INPUT_GET, 'tree') !== null && isset($data['categories'])) {
        $this->showDebugTree($data['categories']);
      } else {
        print_r($data);
      }
    } else {
      ajax(json_encode($data));
    }

    if ($exit) {
      exit;
    }
  }

  private function getInstagramPage($url) {
    $parse = parse_url($url);
    $url .= ((substr($url, -1) != '/') ? '/' : '') . (!empty($parse['query']) ? '&' : '?') . '__a=1';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
  }
}