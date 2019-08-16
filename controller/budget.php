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
  public function build($budget = false) {
    if (!empty($budget)) {
      $budget_data = unserialize($budget);
      print_array($budget_data);

      $budgetid = $budget_data['budgetid'];

      $dt_start_unix = $budget_data['dt_start'];
      $dt_end_unix = $budget_data['dt_end'];

      // --------------------------------------------------------------------- //
      // Компоновка данных по доходам
      $budget_source = array();
      $count = count($budget_data['budget_name_source']);
      for ($i = 0; $i < $count; $i++) {
        $budget_source[] = array(
          'budget_name_source' => $budget_data['budget_name_source'][$i],
          'budget_amount_source' => $budget_data['budget_amount_source'][$i],
        );
      }
      // --------------------------------------------------------------------- //

      $budget_day = array();
    } else {
      $budgetid = time();
      // Дата начала и окончания периода
      $dt_start = Request::getStr('dt_start');
      $dt_end = Request::getStr('dt_end');

      $dt_start_unix = strtotime($dt_start);
      $dt_end_unix = strtotime($dt_end);

      $budget_source = array(
        array(
          'budget_name_source' => '',
          'budget_amount_source' => '',
        )
      );
      $budget_day = array();
    }
    
    $amount_money = 0;
    $error = '';

    // Дата начала периода, не должна превышает дату его завершения
    if ($dt_start_unix > $dt_end_unix) {
      $error = 'Дата начала периода, превышает дату его завершения';
      $days = 0;
    } else {
      // Вычисление разницы в днях между датами
      $datediff = $dt_end_unix - $dt_start_unix;
      // Получение значения длинны периода
      $days = floor($datediff / (60 * 60 * 24)) + 1;
      // Затраты в день
      $expense = round($amount_money / $days, 2);

      $dt_start_unix_temp = $dt_start_unix;
      for ($i = 1; $i <= $days; $i++) {
        $day = date("d-m-Y", $dt_start_unix_temp);

        // budget_where_19-08-2019
        // budget_amount_19-08-2019
        $budget_day[$i] = array(
          'date'        => $day,
          'expenses'    => array(
            '0' => array(
              'where' => '',
              'amount' => '',
            )
          ),
        );
        $dt_start_unix_temp = $dt_start_unix_temp + (60 * 60 * 24);
      }
    }

    //print_array($budget_day);
    
    $view = new View();
    $view->add('budgetid', $budgetid);
    $view->add('amount_money', $amount_money);
    $view->add('dt_start', $dt_start_unix);
    $view->add('dt_end', $dt_end_unix);

    $view->add('days', $days);
    $view->add('expense', $expense);
    $view->add('budget_source', $budget_source);
    $view->add('budget_day', $budget_day);
    $view->add('current_day', date("d-m-Y"));
    
    $view->add('error', $error);
    $view->template = 'budget/build.tpl';
    $render = $view->render();

    if (!empty($budget)) return $render;

    ajax($render);
  }

  public function save() {
    $data = empty($_POST) ? false : $_POST;
    if (empty($data)) ajax('error');

    $budget_data = array(
      'budget_id' => $data['budgetid'],
      'budget_data' => serialize($data),
      'budget_dt_start' => $data['dt_start'],
      'budget_dt_end' => $data['dt_end'],
    );

    $check = DB::scalarSelect('SELECT id FROM budget WHERE budget_id = ?', $budget_data['budget_id']);
    if (!empty($check))
      DB::update('budget', $check, $budget_data);
    else
      DB::insert('budget', $budget_data);

    $render = $this->build($budget_data['budget_data']);
    ajax($render);
  }

  public function show() {
    // Если не передан идентификатор Бюджета или данные по нему не найдены
    $budget = $this->params[0];
    if (empty($budget)) return View::error404();

    $budget_data = DB::scalarSelect('SELECT budget_data FROM budget WHERE budget_id=?', $budget);
    if (empty($budget_data)) return View::error404();

    $render = $this->build($budget_data);

    $view = new View();
    $view->add('render', $render);
    $view->template = 'budget/show.tpl';

    return $view->render();
  }
}