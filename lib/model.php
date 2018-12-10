<?php

class Model {
  public $properties = array();
  public $values = array();
  public $dirty = array();
  public $objects = array();
  public $_is_new = false;

  /*
   * __construct - Метод конструктора текущего класса, автоматически вызывается при создании экземпляра объекта по классу
   */
  function __construct($id = 0) {

  }

  /*
   * __get - Метод вызывается при обращении к неопределенному свойству
   */
  function __get($key) {
    if (isset($this->values[$key])) return $this->values[$key];
    if (isset($this->values[$key . 'id']) && $this->values[$key . 'id'] > 0) {
      // ищем в кэше объектов
      if (isset($this->objects[$key])) return $this->objects[$key];
      // загружаем по FK (foreign key)
      $class_name = 'Model_' . $key;
      if (class_exists($class_name)) {
        $this->objects[$key] = new $class_name;
        $this->objects[$key]->getBy("id", $this->values[$key . 'id']);

        return $this->objects[$key];
      }
    }
  }

  /*
   * __set - Метод вызывается, когда неопределенному свойству присваивается значение
   */
  function __set($key, $value) {
    if (!isset($this->values[$key]) || $this->values[$key] != $value) {
      $this->dirty[$key] = true;
    }
    if (!isset($value)) $value = ''; // боремся с NULL значениями из БД
    $this->values[$key] = $value;
  }

  /*
   * __call - Метод вызывается при обращении к неопределенному методу
   */
  function __call($method, $args) {
    if (substr($method, 0, 5) == 'getBy') {
      $key = substr($method, 5);

      return $this->getBy(strtolower($key), $args[0]);
    } else {
      //debug_print_backtrace(); exit;
      throw new Exception('Unknown method: ' . $method);
    }
  }

  function addFromHash($hash) {
    if (!is_array($hash)) return;
    foreach ($hash as $key => $value) {
      $this->$key = $value;
      if (isset($hash['id'])) $this->dirty[$key] = false; // загружаем из БД
    }
  }

  function isNew() {
    if ($this->_is_new) return true;

    return !isset($this->values['id']);
  }

  function getDirtyProps($table) {
    $props = Array();
    $fields = $this->properties[$table];
    //return $fields; // если убрать глючит создание группы
    // например:
    // создался объект, сохранился, затем добавляем к нему свойство и снова сохраняем - это свойство не сохраняется
    foreach ($fields as $k) {
      if ($this->dirty[$k]) $props[] = $k;
    }

    return $props;
  }

  function isDirty($prop) {
    if ($this->dirty[$prop]) return true;

    return false;
  }

  function save() {
    // эмулируем транзакцию, и при первой же ошибке откатываем уже вставленные строки
    $already_inserted = Array();

    $new = $this->isNew();
    foreach ($this->properties as $table => $fields) {
      if ($new) {
        $row = Array();
        foreach ($fields as $prop) if ($prop != 'id' || $this->values[$prop]) $row[$prop] = $this->values[$prop];
        try {
          $newid = DB::insert($table, $row);
          if (!isset($this->values['id'])) $this->values['id'] = $newid;
          $already_inserted[$table] = $this->values['id'];
        } catch (ExceptionDB $ex) {
          // откатываемся
          foreach ($already_inserted as $t_table => $t_id) {
            DB::delete($t_table, 'id=' . $t_id);
          }
          throw $ex;
        }
      } else {
        // получаем список измененных полей
        $props = $this->getDirtyProps($table);
        if (sizeof($props) > 0) {
          $row = Array();
          foreach ($props as $prop) $row[$prop] = $this->values[$prop];
          DB::update($table, $this->id, $row);
        }
      }
    }

    $this->_is_new = false;
    $this->cleanDirtyProperties();
  }

  function reload() {
    if ($this->id) $this->getById($this->id);
  }

  function delete() {
    $tables_done = Array();
    foreach ($this->properties as $table => $fields) {
      if (!$tables_done[$table]) {
        DB::delete($table, "id=" . $this->values['id']);
      }
      $tables_done[$table] = true;
    }
  }

  function getBy($key, $value) {
    $fields_str = '';
    $tables_str = '';
    $first = true;
    $maintable = '';
    $links_str = '';
    $prev_table = '';
    foreach ($this->properties as $table => $fields) {
      if (!$maintable) $maintable = $table;

      if ($prev_table) {
        $links_str .= $prev_table . '.id=' . $table . '.id and ';
      }
      $prev_table = $table;

      if ($tables_str) $tables_str .= ',';
      $tables_str .= $table;
      foreach ($fields as $field) {
        if (!$first && $field == 'id') continue;
        if ($first && $field == 'id') $first = false;

        if ($fields_str) $fields_str .= ', ';
        $fields_str .= $table . '.' . $field;
      }
    }
    if ($key == 'id') $key = $maintable . '.' . $key;
    $hash = DB::singleRow("SELECT {$fields_str} FROM {$tables_str} WHERE {$links_str} {$key} = ?", $value);
    $this->addFromHash($hash);
    $this->cleanDirtyProperties();
  }

  function find($order = '', $limit = 0, $offset = 0) {
    return $this->findBy('', '', $order, $limit, $offset);
  }

  function findByCondition($cond_str, $order = '', $limit = 0, $offset = 0) {
    $fields_str = '';
    $tables_str = '';
    $first = true;
    $maintable = '';
    $links_str = '';
    $prev_table = '';
    foreach ($this->properties as $table => $fields) {
      if (!$maintable) $maintable = $table;

      if ($prev_table) {
        $links_str .= $prev_table . '.id=' . $table . '.id and ';
      }
      $prev_table = $table;

      if ($tables_str) $tables_str .= ',';
      $tables_str .= $table;
      foreach ($fields as $field) {
        if (!$first && $field == 'id') continue;
        if ($first && $field == 'id') $first = false;

        if ($fields_str) $fields_str .= ', ';
        $fields_str .= $table . '.' . $field;
      }
    }
    $query = "select $fields_str from $tables_str where $links_str $cond_str";

    return $this->findByQuery($query, $order, $limit, $offset);
  }

  function findBy($key, $value, $order = '', $limit = 0, $offset = 0) {
    $maintable = '';
    foreach ($this->properties as $table => $fields) {
      if (!$maintable) $maintable = $table;
    }
    $value = DB::escape($value);
    if ($key == 'id') $key = $maintable . '.' . $key;
    if ($key) $key_str = "$key='$value'";
    else $key_str = '1';

    return $this->findByCondition($key_str, $order, $limit, $offset);
  }

  function findByQuery($query, $order = '', $limit = 0, $offset = 0) {
    if ($order) $query .= ' order by ' . $order;
    if ($limit && $offset) $query .= ' limit ' . $offset . ',' . $limit;
    if ($limit && !$offset) $query .= ' limit ' . $limit;
    $rows = DB::getRows($query);
    $objects = Array();
    foreach ($rows as $row) {
      $obj = $this->instance();
      $obj->addFromHash($row);
      $this->cleanDirtyProperties();
      $objects[] = $obj;
    }

    return $objects;
  }

  function getCount($condition = '') {
    if ($condition) {
      $tables_str = '';
      $first = true;
      $maintable = '';
      $links_str = '';
      $prev_table = '';
      foreach ($this->properties as $table => $fields) {
        if (!$maintable) $maintable = $table;

        if ($prev_table) {
          $links_str .= $prev_table . '.id=' . $table . '.id and ';
        }
        $prev_table = $table;

        if ($tables_str) $tables_str .= ',';
        $tables_str .= $table;
        foreach ($fields as $field) {
          if (!$first && $field == 'id') continue;
          if ($first && $field == 'id') $first = false;
        }
      }

      return DB::scalarSelect("select count(*) from $tables_str where $links_str " . $condition);
    } else {
      return DB::scalarSelect("select count(*) from " . $this->getLastTable());
    }
  }

  function instance() {
    $class_name = get_class($this);

    return new $class_name;
  }

  function getClassName() {
    $class_name = strtolower(get_class($this));

    return str_replace('model_', '', $class_name);
  }

  function getLastTable() {
    $ak = array_keys($this->properties);

    return $ak[sizeof($ak) - 1];
  }

  function getData($value) {
    $table = key($this->properties);
    $hash = DB::singleRow("SELECT * FROM {$table} WHERE id = ?", $value);
    if (is_array($hash))
      foreach ($hash as $key => $value) $this->$key = $value;
  }

  private function cleanDirtyProperties() {
    $this->dirty = array();
  }
}