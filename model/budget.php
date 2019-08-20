<?php

/**
 * Class Model_Budget
 */
class Model_Budget extends Model {

  function __construct($id = 0) {
    parent::__construct();
    $this->properties['budget'] = array(
      'id',
      'name',
      'user_id',
      'hash',
      'dt_start',
      'dt_end',
      'days',
      'amount',
      'balance',
      'expense',
      'source',
      'costs',
    );

    if ($id) {
      $this->getData($id);
      $this->source = unserialize($this->source);
      $this->costs = unserialize($this->costs);
    } else {
      $this->hash = getRandomSet(20);
    }
  }

  public function addFromHash($hash) {
    $hash['source'] = !empty($hash['source']) ? unserialize($hash['source']) : '';
    $hash['costs'] = !empty($hash['costs']) ? unserialize($hash['costs']) : '';

    parent::addFromHash($hash);
  }

  public function getUrl() {
    return PROTOCOL . HOST_NAME . '/budget/show/' . $this->hash . '/';
  }

  /**
   * Пересчет периода Бюджета
   */
  public function calculateLenght() {
    $days = floor(($this->dt_end - $this->dt_start) / (60 * 60 * 24)) + 1;
    $this->days = $days;
  }

  public function getTitle() {
    if ($this->name) {
      return $this->name;
    } else {
      return date('d-m-Y', $this->dt_start) . ' &mdash; ' . date('d-m-Y', $this->dt_end);
    }
  }

  public function getLink($target = false) {
    return '<a href="'.$this->getUrl().'">'.$this->getTitle().'</a>';
  }

  /**
   * Формирование массива данных по доходам
   */
  public function getSourceData() {
    $data = array();
    $source = $this->source;
    if (!empty($source)) {
      foreach ($source as $item) {
        $info = explode('::', $item);
        $data[] = array(
          'name'    => $info[0],
          'amount'  => $info[1],
        );
      }
    } else {
      $data[] = array(
        'name'      => '',
        'amount'    => '',
      );
    }

    return $data;
  }
  
  /**
   * Формирование массива данных по расходам
   */
  public function getCostsData() {
    $data = array();
    $costs = $this->costs;
    $dt_start = $this->dt_start;
    
    for ($i = 1; $i <= $this->days; $i++) {
      $day = date("d-m-Y", $dt_start);
      $data[$day] = array();

      if (!empty($costs[$day]) && count($costs[$day]) > 1) {
        foreach ($costs[$day] as $key => $item) {
          if ($key === '_total') continue;

          $info = explode('::', $item);
          $data[$day][] = array(
            'name'    => $info[0],
            'amount'  => $info[1],
          );
        }
      } else {
        $data[$day][] = array(
          'name'      => '',
          'amount'    => '',
        );
      }

      $dt_start = $dt_start + (60 * 60 * 24);
    }

    return $data;
  }

  static function getSavedBudget() {
    $cu = getCurrentUser();
    if (!$cu) return array();

    $model = new Model_Budget();
    $saved = $model->findBy('user_id', $cu->id);

    if (empty($saved)) return '';
    $block = '<ul class="saved-budget"><li>Сохраненные : </li>';
    foreach ($saved as $item)
      $block .= '<li>' . $item->getLink() . '</li>';

    $block .= '</ul>';
    return $block;
  }

  public function save() {
    $this->calculateLenght();
    $this->expense = round($this->amount / $this->days, 2);
    $this->source  = serialize($this->source);
    $this->costs   = serialize($this->costs);

    parent::save();
  }

  public function setSourceData($data) {
    if (empty($data['source_name'])) return;

    $amount = 0;
    $temp = array();
    foreach($data['source_name'] as $key => $item) {
      $amount += $data['source_amount'][$key];
      $temp[] = $data['source_name'][$key] . "::" . $data['source_amount'][$key];
    }

    $this->source = $temp;
    $this->amount = $amount;
  }

  public function setCostsData($data) {
    if (empty($data)) return;

    $costs = 0;
    $temp = array();
    foreach ($data as $key => $item) {
      if (strpos($key, 'costs_name__') !== false) {
        $d = explode('__', $key);
        $d = $d[1];
        $costs_temp = 0;
        foreach($item as $key1 => $subitem) {
          $costs_temp += $data['costs_amount__'.$d][$key1];
          $temp[$d][] = $data['costs_name__'.$d][$key1] . "::" . $data['costs_amount__'.$d][$key1];
        }
        $costs += $costs_temp;
        $temp[$d]['_total'] = $costs_temp;
      }
    }

    $this->costs = $temp;
    $this->balance = $this->amount - $costs;
  }
}