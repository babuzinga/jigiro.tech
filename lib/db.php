<?
class DB {
  static $affected_rows;
  static $insert_id;

  public static function connect($host, $user, $password, $database, $collation = 'UTF-8') {
    global $gen_time_sql;
    $st = microtime(true);
    $res = mysql_connect($host, $user, $password);
    $gen_time_sql[] = round((microtime(true) - $st), 4);

    if ($res) $res = mysql_select_db($database);
    if (!$res) {
      self::logConnectionError(mysql_error());
      throw new ExceptionNotAvailable();
    }
    mysql_query("SET NAMES " . $collation);
  }

  public static function query($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    global $gen_time_sql;
    $st = microtime(true);
    $result = mysql_query($sql);
    $gen_time_sql[] = round((microtime(true) - $st), 4);

    if (mysql_error()) DB::error($sql);
    self::$affected_rows = mysql_affected_rows();
    self::$insert_id = mysql_insert_id();

    /*
    if(microtime(true) - $st > 1)
    {
      $_q = preg_replace('/[\d,]+/', 'N', $sql);
      $_uri = Request::escape( $_SERVER['REQUEST_URI'] );
      $_long_query = DB::prepare("insert into long_query (q,uri,st) values (?,?,?i)", $_q, $_uri, microtime(true)-$st);
      mysql_query($_long_query); // служедбный запрос, чтобы не сбил нам insert_id и affected_rows
    }
    */

    // echo $sql."<br>\n";

    if (microtime(true) - $st > 0.1 && $_SERVER['REMOTE_ADDR'] == '93.125.42.170') $GLOBALS['queries'][] = Array("time" => (microtime(true) - $st), "sql" => $sql);

    return $result;
  }

  static function getCursor($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    return mysql_query($sql, self::$link);
  }

  static function fetch($cursor) {
    $row = mysql_fetch_assoc($cursor);
    if (!$row) return false;

    if (count($row) == 1) return current($row);

    return $row;
  }

  static function getAffectedRows() {
    return self::$affected_rows;
  }

  static function getInsertId() {
    return self::$insert_id;
  }

  static function delete($table, $condition) {
    DB::query("DELETE FROM $table WHERE $condition");
  }

  static function clearTableCache($table) {
    // deprecated
  }

  static function singleRow($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    $res = DB::query($sql);
    if ($row = mysql_fetch_assoc($res)) {
      return $row;
    }

    return false;
  }

  static function scalarSelect($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    $res = DB::query($sql);
    if ($row = mysql_fetch_row($res)) {
      return $row[0];
    }

    return false;
  }

  static function getRows($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    $ret = Array();
    $res = DB::query($sql);
    while ($row = mysql_fetch_assoc($res)) {
      $ret[] = $row;
    }
    mysql_free_result($res);

    return $ret;
  }

  static function hashedSelect($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    $ret = Array();
    $res = DB::query($sql);
    while ($row = mysql_fetch_row($res)) {
      $ret[$row[0]] = $row[1];
    }
    mysql_free_result($res);

    return $ret;
  }

  static function hashedRows($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    $ret = Array();
    $res = DB::query($sql);
    while ($row = mysql_fetch_assoc($res)) {
      $first = current($row);
      $ret[$first] = $row;
    }
    mysql_free_result($res);

    return $ret;
  }

  static function getArray($sql) {
    if (func_num_args() > 1) $sql = self::prepare($args = func_get_args());

    $ret = Array();
    $res = DB::query($sql);
    while ($row = mysql_fetch_array($res)) {
      $ret[] = $row[0];
    }
    mysql_free_result($res);

    return $ret;
  }

  static function insert($table, $values = Array()) {
    $fields = array_keys($values);
    $fields_str = implode(',', $fields);
    $values_str = "'" . implode("', '", self::escape($values)) . "'";

    $sql = "insert into $table ($fields_str) values ($values_str)";
    DB::query($sql);

    return self::getInsertId();
  }

  static function update($table, $id, $values = Array()) {
    $id = intval($id);
    unset($values['id']);

    $fields = array_keys($values);
    $fields_str = '';
    foreach ($fields as $field) {
      if ($fields_str) $fields_str .= ',';
      $fields_str .= $field . "='" . self::escape($values[$field]) . "'";
    }

    $sql = "update $table set $fields_str where id={$id}";
    DB::query($sql);

    return self::getAffectedRows();
  }

  static function escape($value) {
    if (is_array($value)) {
      return array_map("mysql_real_escape_string", $value);
    } else {
      return mysql_real_escape_string($value);
    }
  }


  // usage: prepare($sql, $args)
  //    or: prepare($args)
  static function prepare() {
    if (func_num_args() == 1) $args = func_get_arg(0); // это надо если параметры передаем массивом
    else $args = func_get_args();

    $sql = array_shift($args);
    $sql .= ' ';

    $i = 0;
    $shift = 0;
    $pos = strpos($sql, '?', $shift);
    while (is_int($pos)) {
      $pos2 = $pos + 1;

      $key = '';
      $next_char = substr($sql, $pos + 1, 1);
      if ($next_char === 'i' || $next_char === 'l' || $next_char === 'f') {
        $key = $next_char;
        $pos2++;
      }

      if ($key == 'i') {
        $subst = intval($args[$i]);
      } elseif ($key == 'f') {
        $subst = floatval($args[$i]);
      } elseif ($key == 'l') {
        $subst = "'%" . mysql_real_escape_string(str_replace('%', '', $args[$i])) . "%'";
      } else {
        $subst = "'" . mysql_real_escape_string($args[$i]) . "'";
      }

      //  выполн¤ем подстановку
      $sql = substr($sql, 0, $pos) . $subst . substr($sql, $pos2);

      // ищем следующий placeholder
      $i++;
      $shift = $pos + strlen($subst) + 1;
      $pos = strpos($sql, '?', $shift);
    }

    return $sql;
  }

  function logConnectionError($error = '') {
    date_default_timezone_set("Europe/Moscow");

    $str = date("H:i:s") . " " . $error . "\n";

    $fp = fopen(BASE_DIR . '/tmp/mysql.' . date("d.m.Y") . '.log', 'a');
    fwrite($fp, $str);
    fclose($fp);
  }

  static function error($sql) {
    $info = mysql_error();

    if (is_int(strpos($info, 'Lost connection')) || is_int(strpos($info, 'of memory')) || is_int(strpos($info, 'has gone'))) {
      self::logConnectionError();
      throw new ExceptionNotAvailable();
    }

    $str = "Ошибка при выполнении SQL запроса: " . $sql . "\r\n<br>";
    $str .= "Описание ошибки: " . $info . "\r\n";
    $str .= "Адрес по которому произошла ошибка: " . $_SERVER['REQUEST_URI'] . "\r\n";
    $str .= "\r\n";

    date_default_timezone_set("Europe/Moscow");
    $fp = fopen(BASE_DIR . '/tmp/error.' . date("d.m.Y") . '.log', 'a');
    fwrite($fp, $str);
    fclose($fp);

    //debug_print_backtrace();			exit;

    throw new ExceptionDB($sql, $info);
  }
}

class ExceptionDB extends Exception {
  public $sql;
  public $info;

  public function __construct($sql, $info) {
    $this->sql = $sql;
    $this->info = $info;
    parent::__construct("Ошибка при выполнении SQL запроса.");
  }
}

class BulkInsert {
  public $pre;
  public $str;
  public $maxlen = 30000; // DB max_allowed_packet

  public function __construct($pre) {
    $this->pre = $pre;
    $this->str = '';
  }

  function add($sql) {
    if (func_num_args() > 1) $sql = DB::prepare($args = func_get_args());
    if ($this->str != '') $this->str = $this->str . ',';
    $this->str .= '(' . $sql . ')';
    if (strlen($this->str) > $this->maxlen) $this->commit();
  }

  function commit() {
    if ($this->str != '') {
      DB::query($this->pre . ' values ' . $this->str);
      $this->str = '';
    }
  }
}