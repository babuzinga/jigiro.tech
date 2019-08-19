<?php

class Controller_Budget extends Controller {
  public function index() {
    $view = new View();
    $view->template = 'budget/index.tpl';

    return $view->render();
  }

  /**
   * Построение формы
   */
  public function build($budget_data = false) {
    if (empty($budget_data)) {
      // Перевод в unix-формат Дат начала и окончания периода
      $first_day = Request::getStr('dt_start');

      $dt_start_unix = strtotime(Request::getStr('dt_start'));
      $dt_end_unix = strtotime(Request::getStr('dt_end'));

      $budget_data = array(
        'hash'      => getRandomSet(20),  // Идентификатор бюджета
        'dt_start'  => $dt_start_unix,    // Дата начала периода
        'dt_end'    => $dt_end_unix,      // Дата окончания периода
        'days'      => 0,                 // Продолжительность
        'amount'    => 0,                 // Сумма
        'balance'   => 0,                 // Остаток
        'expense'   => 0,                 // Лимит в день
        'source'    => 0,                 // Источники дохода
        'costs'     => 0,                 // Источники расхода
      );

      $budget_data['days'] = floor(($budget_data['dt_end'] - $budget_data['dt_start']) / (60 * 60 * 24)) + 1;
    } else {
      $budget_data['source'] = unserialize($budget_data['source']);
      $budget_data['costs'] = unserialize($budget_data['costs']);
    }

    $error = '';
    $budget_source = $budget_costs = array();

    // Дата начала периода, не должна превышает дату его завершения
    if ($dt_start_unix > $dt_end_unix) {
      $error = 'Дата начала периода, превышает дату его завершения';
    } else {
      // ----------------------------------------------------------- //
      // Формирование массива данных по доходам
      if (!empty($budget_data['source'])) {
        foreach ($budget_data['source'] as $item) {
          $info = explode('::', $item);
          $budget_source[] = array(
            'name'    => $info[0],
            'amount'  => $info[1],
          );
        }
      } else {
        $budget_source[] = array(
          'name'      => '',
          'amount'    => '',
        );
      }
      // ----------------------------------------------------------- //
      // Формирование массива данных по расходам
      $dt_start = $budget_data['dt_start'];
      for ($i = 1; $i <= $budget_data['days']; $i++) {
        $day = date("d-m-Y", $dt_start);
        $budget_costs[$day] = array();

        if (!empty($budget_data['costs'][$day]) && count($budget_data['costs'][$day]) > 1) {
          foreach ($budget_data['costs'][$day] as $key => $item) {
            if ($key === '_total') continue;

            $info = explode('::', $item);
            $budget_costs[$day][] = array(
              'name'    => $info[0],
              'amount'  => $info[1],
            );
          }
        } else {
          $budget_costs[$day][] = array(
            'name'      => '',
            'amount'    => '',
          );
        }

        $dt_start = $dt_start + (60 * 60 * 24);
      }
      // ----------------------------------------------------------- //
    }
    
    //print_array($budget_costs);

    $view = new View();

    $view->add('budget_data', $budget_data);
    $view->add('budget_source', $budget_source);
    $view->add('budget_costs', $budget_costs);
    $view->add('current_day', date("d-m-Y"));
    
    $view->add('error', $error);
    $view->template = 'budget/build.tpl';
    $render = $view->render();

    if (!empty($budget_data)) return $render;

    ajax($render);
  }

  public function save() {
    $data = empty($_POST) ? false : $_POST;
    if (empty($data)) ajax('error');

    $budget_data = array();
    $costs = $costs_temp = 0;
    // -------------------------------------------------------------------------------- //
    foreach ($data as $key => $item) {
      // Хэш / Дата начало и конец периода / Количество дней / Сумма / Баланс / Затраты
      if (in_array($key, array('hash', 'dt_start', 'dt_end', 'days', 'amount', 'balance', 'expense'))) {
        $budget_data[$key] = $data[$key];
        continue;
      }

      // Источник - source
      if ($key == 'source_name') {
        $amount = 0;
        foreach($item as $key1 => $subitem) {
          $amount += $data['source_amount'][$key1];
          $budget_data['source'][] = $data['source_name'][$key1] . "::" . $data['source_amount'][$key1];
        }
      }
      // Расходы - costs
      if (strpos($key, 'costs_name__') !== false) {
        $d = explode('__', $key);
        $d = $d[1];
        $costs_temp = 0;
        foreach($item as $key1 => $subitem) {
          $costs_temp += $data['costs_amount__'.$d][$key1];
          $budget_data['costs'][$d][] = $data['costs_name__'.$d][$key1] . "::" . $data['costs_amount__'.$d][$key1];
        }
        $costs += $costs_temp;
        $budget_data['costs'][$d]['_total'] = $costs_temp;
      }
    }

    $budget_data['amount']  = $amount;
    $budget_data['balance'] = $amount - $costs;
    $budget_data['expense'] = $amount / $budget_data['days'];

    // -------------------------------------------------------------------------------- //

    // Вычисление затрат в день
    $budget_data['expense'] = round($budget_data['amount'] / $budget_data['days'], 2);
    $budget_data['source']  = serialize($budget_data['source']);
    $budget_data['costs']   = serialize($budget_data['costs']);

    $check = DB::scalarSelect('SELECT id FROM budget WHERE hash = ?', $budget_data['hash']);
    if (!empty($check))
      DB::update('budget', $check, $budget_data);
    else
      DB::insert('budget', $budget_data);

    $render = $this->build($budget_data);
    ajax($render);
  }

  public function show() {
    // Если не передан идентификатор Бюджета или данные по нему не найдены
    $budget = $this->params[0];
    if (empty($budget)) return View::error404();

    $budget_data = DB::singleRow('SELECT * FROM budget WHERE hash=?', $budget);
    if (empty($budget_data)) return View::error404();

    $render = $this->build($budget_data);

    $view = new View();
    $view->add('render', $render);
    $view->template = 'budget/show.tpl';

    return $view->render();
  }
}