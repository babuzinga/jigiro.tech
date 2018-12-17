<?php

/**
 * Class Model_Rememberme
 */
class Model_Rememberme extends Model {
  public function __construct($id = 0) {
    $this->properties['rememberme'] = array(
      'id',
      'userid',
      'expire',
      'hash',
    );

    parent::__construct();

    if ($id) $this->getById($id);
  }

  public static function createUserHash($userid, $days = 30) {
    $rm = new Model_Rememberme();
    $rm->userid = $userid;
    $rm->expire = time() + 86400*$days;
    $rm->hash = self::generateHash($userid);
    $rm->save();

    return $rm->id . '.' . $userid . '.' . $rm->hash;
  }

  public static function logOut($str) {
    $arr = explode('.', $str);
    if (count($arr) != 3) return false;

    $id = intval($arr[0]);
    $userid = intval($arr[1]);
    $hash = $arr[2];

    $rm = new Model_Rememberme($id);
    if (!$rm->id) return false;
    if ($rm->hash != $hash) return false;
    if ($rm->userid != $userid) return false;

    DB::query('DELETE FROM rememberme WHERE id=?i', $rm->id);

    return true;
  }

  public static function getUserIdFromHash($str) {
    $arr = explode('.', $str);
    if (count($arr) != 3) return false;

    $id = intval($arr[0]);
    $userid = intval($arr[1]);
    $hash = $arr[2];

    $rm = new Model_Rememberme($id);
    if (!$rm->id) return false;
    if ($rm->hash != $hash) return false;
    if ($rm->userid != $userid) return false;
    if ($rm->expire < time()) return false;

    // Продлеваем куку еще на 30 дней с момента успешного использования
    setcookie('rm', $str, time() + 86400*30, '/', COOKIE_DOMAIN, false, true);
    DB::query('UPDATE rememberme SET expire=?i WHERE id=?i', time() + 86400*30, $rm->id);

    return $rm->userid;
  }

  // Генерирует однозначный hash на основе id пользователя и начала его ip
  private static function generateHash($userid) {
    $nums = explode('.', $_SERVER['REMOTE_ADDR']);
    $ip_start = $nums[0] . '.' . $nums[1];

    return substr(md5($userid . '-' . $ip_start . '-' . SALT), 10, 12);
  }
}