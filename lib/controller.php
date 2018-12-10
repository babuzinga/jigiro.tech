<?php
class Controller {
  public $params;
  public $errors = array();
  public $success = array();

  public function __construct() {
    session_start();
    if (isset($_SESSION['errors'])) $this->errors = $_SESSION['errors'];
  }

  public function index() {
  }

  public function run($handler, $params) {
    $this->params = $params;
    return $this->$handler();
  }

  // 301 Moved Permanently - переехал навсегда
  // 302 Found - найденный
  // 303 See Other - смотрите другое
  // 307 Temporary Redirect - временное перенаправление
  public function redirect($url, $code = 302) {
    if (substr($url, 0, 7) != 'http://') $url = 'http://' . HOST_NAME . $url;
    header("Location: $url", true, $code);
    exit;
  }

  public function addError($error) {
    $this->errors[] = $error;
    $_SESSION['errors'] = $this->errors;
  }

  public function addSuccess($success) {
    $this->success[] = $success;
    $_SESSION['success'] = $this->success;
  }
}