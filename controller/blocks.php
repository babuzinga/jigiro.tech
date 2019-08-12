<?php

class Controller_Blocks extends Controller {
  public static function blockSetDate($params = array()) {
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

    $month = date('n'); // Порядковый номер месяца без ведущего нуля
    $year = date('Y'); // Порядковый номер года, 4 цифры
    
    $days_month = date('t'); // Количество дней в указанном месяце

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
        do {
          $unix_temp = $unix + (60*60*24);
          $snd_temp = date('N', $unix_temp);

          $key = date("d-m-Y", $unix_temp);
          $date_array[$key] = array('date' => date('j', $unix_temp), 'class' => 'another');
        } while ($snd_temp!=7);
      }
    }
    //print_array($date_array);

    $view = new View();
    $view->add('name', $params['name']);
    $view->add('desc', $params['desc']);
    $view->add('date_array', $date_array);
    $view->add('month_title', $months_title[$month-1]);
    $view->add('current_day', date("d-m-Y"));
    $view->template = 'blocks/set-date.tpl';

    return $view->render();
  }
}