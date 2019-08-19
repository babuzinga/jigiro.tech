<?php

class Controller_Blocks extends Controller {
  public static function blockSetDate($params = array()) {
    set_time_limit(2);

    $months_title = array(
      'Январь',
      'Февраль',
      'Март',
      'Апрель',
      'Май',
      'Июнь',
      'Июль',
      'Август',
      'Сентябрь',
      'Октябрь',
      'Ноябрь',
      'Декабрь',
    );

    $month = Request::getInt('month');
    $name = Request::getStr('name');

    $current_day = date("d-m-Y");
    if (!empty($month)) {
      $ajax = true;
      $desc = '';
    } else {
      $current_day_unix = !empty($params['value']) ? $params['value'] : time();
      $current_day = date("d-m-Y", $current_day_unix);

      $month = date('n', $current_day_unix); // Порядковый номер месяца без ведущего нуля
      $name = $params['name'];
      $desc = $params['desc'];
    }
    
    $year = date('Y'); // Порядковый номер года, 4 цифры
    $unix = mktime(0, 0, 0, $month, 1, $year);
    $days_month = date('t', $unix); // Количество дней в указанном месяце
    $date_array = array();

    for ($i = 1; $i <= $days_month; $i++) {
      // Дата в Unix-формате
      $unix = mktime(0, 0, 0, $month, $i, $year);
      // Порядковый номер дня недели в соответствии со стандартом ISO-8601 
      $snd = date('N', $unix);
      // Если первое число месяца не начало недели, доставляем даты из предыдущего месяца
      if ($snd != 1 && empty($date_array)) {
        $unix_temp = $unix - (60*60*24*($snd-1));
        $snd_temp = date('N', $unix_temp);

        do {
          $key = date("d-m-Y", $unix_temp);
          $date_array[$key] = array('date' => date('j', $unix_temp), 'class' => 'another');

          $unix_temp = $unix_temp + (60*60*24);
          $snd_temp = date('N', $unix_temp);
        } while ($snd!=$snd_temp);
      }

      $key = date("d-m-Y", $unix); 
      $date_array[$key] = array('date' => date('j', $unix));

      // Если последнее число месяца не конец недели, доставляем даты из следующего месяца
      if ($i == $days_month && $snd != 7) {
        $unix_temp = $unix;

        do {
          $unix_temp = $unix_temp + (60*60*24);
          $snd_temp = date('N', $unix_temp);

          $key = date("d-m-Y", $unix_temp);
          $date_array[$key] = array('date' => date('j', $unix_temp), 'class' => 'another');
        } while ($snd_temp!=7);
      }
    }

    $view = new View();
    $view->add('name', $name);
    $view->add('desc', $desc);
    $view->add('date_array', $date_array);
    $view->add('months_title', $months_title);

    $prev_month = ($month - 1 == 0) ? 12 : $month - 1;
    $next_month = ($month + 1 == 13) ? 1 : $month + 1;
    $view->add('year', $year);
    $view->add('month', $month);
    $view->add('prev_month', $prev_month);
    $view->add('next_month', $next_month);
    $view->add('current_day', $current_day);
    $view->template = 'blocks/set-date.tpl';

    if (!empty($ajax)) {
      $view->template = 'blocks/calendar.tpl';
      $templates = $view->render();
      ajax($templates);
    }

    return $view->render();
  }
}