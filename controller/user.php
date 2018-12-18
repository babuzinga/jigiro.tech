<?php

class Controller_User extends Controller {
  public function Logon() {
    $error = array();
    if (!empty($_POST['submit'])) :
      $login    = Request::postStr('login');
      $password = md5(Request::postStr('password'));

      if (empty($login)) $error['login'] = 'Введите логин';
      if (empty($password)) $error['password'] = 'Введите пароль';

      if (!empty($login) && !empty($password)) :
        $profile_id = DB::scalarSelect('SELECT id FROM profiles WHERE login=? AND password=?', $login, md5($password . SALT));
        if (empty($profile_id)) {
          $error['login'] = 'Логин или пароль указаны неверно';
        } else {
          $profile = new Model_Profile($profile_id);
          $back_url = !empty($_REQUEST['backurl']) ? $_REQUEST['backurl'] : '/';

          // логируем факт логина
          DB::insert('log_login', array(
            'userid' => $profile->id,
            'dt' => time(),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'way' => 'login'
          ));

          setCurrentUser($profile);
          // запомнить меня
          setcookie('rm', Model_Rememberme::createUserHash($profile->id, 30), time() + 86400*30, '/', COOKIE_DOMAIN, false, true);
          Redirect($back_url);
        }
      endif;
    endif;

    $view = new View();
    $view->add('error', $error);
    $view->template = 'user/logon.tpl';
    return $view->render();
  }

  public function Logout() {
    $back_url = !empty($_REQUEST['backurl']) ? $_REQUEST['backurl'] : '/';
    unsetCurrentUser();

    setcookie(session_name(), "", time() - 3000*86400, "/", COOKIE_DOMAIN);
    if (!empty($_COOKIE['rm'])) Model_Rememberme::logOut($_COOKIE['rm']);
    setcookie("rm", "", time() - 3000*86400, "/", COOKIE_DOMAIN);

    Redirect($back_url);
  }

  public static function rememberMe() {
    // если еще нету сессии, но в куках есть инфа о "запомнить меня"
    if (!hasSessionUser() && !empty($_COOKIE['rm'])) {
      $userid = Model_Rememberme::getUserIdFromHash($_COOKIE['rm']);
      if (!$userid) return;

      $profile = new Model_Profile($userid);
      if (!$profile->id) return;

      setCurrentUser($profile);
    }
  }
}