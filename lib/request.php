<?
class Request {
  public static function getVars($strings) {
    $list = array();
    foreach ($strings as $name) {
      $list[$name] = isset($_GET[$name]) ? self::escape($_GET[$name]) : null;
    }

    return $list;
  }

  public static function postVars($variables) {
    $list = array();
    foreach ($variables as $name) {
      $list[$name] = isset($_POST[$name]) ? self::escape($_POST[$name]) : null;
    }

    return $list;
  }

  public static function vars($variables) {
    $list = array();
    foreach ($variables as $name) {
      $list[$name] = isset($_REQUEST[$name]) ? self::escape($_REQUEST[$name]) : null;
    }

    return $list;
  }

  public static function getInt($name, $default = 0) {
    return isset($_GET[$name]) ? (int)$_GET[$name] : $default;
  }

  public static function getStr($name) {
    if (isset($_GET[$name]))
      return self::escape($_GET[$name]);
    else
      return '';
  }

  public static function postInt($name) {
    if (isset($_POST[$name]))
      return intval($_POST[$name]);
    else
      return 0;
  }

  public static function postStr($name) {
    if (isset($_POST[$name]))
      return self::escape($_POST[$name]);
    else
      return '';
  }

  public static function escape($str) {
    // если массив, например чекбоксов - не трогаем
    if (is_array($str)) {
      foreach ($str as $k => $v) {
        $str[$k] = htmlspecialchars(trim($v), ENT_COMPAT | ENT_HTML401, "cp1251");
      }

      return $str;
    }

    // 1. поступающие через Request:: строки
    // применяем htmlspecialchars
    // The translations performed are:
    // '&' (ampersand) becomes '&amp;'
    // '"' (double quote) becomes '&quot;'
    // '<' (less than) becomes '&lt;'
    // '>' (greater than) becomes '&gt;'

    return htmlspecialchars(trim($str), ENT_COMPAT | ENT_HTML401, "cp1251");
  }
}