<?php

class Controller_Budget extends Controller {
  public function index() {
    $cu = getCurrentUser();
    if (!$cu) return View::error401();

    $view = new View();
    $view->add('budget_save', DB::getRows('SELECT name, hash, dt_start, dt_end FROM budget WHERE user_id = ?i', $cu->id));
    $view->template = 'budget/index.tpl';

    return $view->render();
  }

  /**
   * Построение формы
   */
  public function build($budget_hash = false) {
    $cu = getCurrentUser();
    if (!$cu) return View::error401();
    
    $budget = new Model_Budget();
    if (empty($budget_hash)) {
      $budget->dt_start = strtotime(Request::getStr('dt_start'));
      $budget->dt_end = strtotime(Request::getStr('dt_end'));
    } else {
      $budget->getBy('hash', $budget_hash);
    }
    $budget->calculateLenght();
    //print_array($budget);
    
    // Дата начала периода, не должна превышает дату его завершения
    if ($budget->dt_start > $budget->dt_end) {
      $error = 'Дата начала периода, превышает дату его завершения';
    }

    $view = new View();
    $view->add('budget', $budget);
    $view->add('current_day', date("d-m-Y"));
    $view->add('error', !empty($error) ? $error : false);
    $view->template = 'budget/build.tpl';
    $render = $view->render();

    if (!empty($budget_hash)) return $render;

    ajax($render);
  }

  /**
   * Сохранение Бюджета
   * ------------------
   */
  public function save() {
    $cu = getCurrentUser();
    $data = empty($_POST) ? false : $_POST;
    if (empty($data) || !$cu) ajax('error');

    $budget = new Model_Budget();
    $hash = Request::postVars(array('name', 'hash', 'dt_start', 'dt_end', 'days', 'amount', 'balance', 'expense'));
    $hash['dt_start'] = strtotime($hash['dt_start']);
    $hash['dt_end'] = strtotime($hash['dt_end']);

    $budget->getBy('hash', $hash['hash']);
    if (!$budget->id) $hash['user_id'] = $cu->id;
    $budget->addFromHash($hash);
    $budget->setSourceData($_POST);
    $budget->setCostsData($_POST);
    //print_array($budget, 1);

    $budget->save();

    $render = $this->build($budget->hash);
    ajax($render);
  }

  /**
   * Показать Бюджет
   * ---------------
   */
  public function show() {
    $cu = getCurrentUser();
    if (!$cu) return View::error401();

    // Если не передан идентификатор Бюджета или данные по нему не найдены
    $budget = $this->params[0];
    if (empty($budget)) return View::error404();

    // Проверка что Бюджет принадлежит текущему пользователю
    $budget_hash = DB::scalarSelect('SELECT hash FROM budget WHERE hash=? AND user_id=?i', $budget, $cu->id);
    if (empty($budget_hash)) return View::error404();

    $render = $this->build($budget_hash);

    $view = new View();
    $view->add('render', $render);
    $view->template = 'budget/show.tpl';

    return $view->render();
  }

  /**
   * Удаление Бюджета
   * ----------------
   */
  public function delete() {
    $cu = getCurrentUser();
    if (!$cu) return View::error401();

    // Если не передан идентификатор Бюджета или данные по нему не найдены
    $budget = $this->params[0];
    if (empty($budget)) return View::error404();

    // Проверка что Бюджет принадлежит текущему пользователю
    $budget_data = DB::singleRow('SELECT * FROM budget WHERE hash=? AND user_id=?i', $budget, $cu->id);
    if (empty($budget_data)) return View::error404();

    $confirmation = Request::getStr('c');
    if (empty($confirmation)) {
      $desc = 'Вы уверены что хотите удалить Бюджет?';
      $confirmed = '';
      $rejected = '';
      return View::confirmation_page($desc, $confirmed, $rejected);
    } else {
      DB::delete('budget', 'id = '.$budget_data['id']);
      Redirect('/b/');
    }
  }
}