<?php

define('DEV_MODE',          false);
define('PROTOCOL',          'http://');
define('HOST_NAME',         'jigiro.tech');
define('HOST_TYPE',         'production');
define('PROJECT_NAME',      'JIGIRO');
define('PROJECT_NAME_FULL', 'JIGIRO.TECH [ sercices project ]');
define('SALT',              '!9vf80-');
define('COOKIE_DOMAIN',     '.jigiro.tech');
define('BASE_DIR',          dirname(__FILE__));

define('DB_DATABASE',       'cm27499_jigiro');
define('DB_USER',           'cm27499_jigiro');
define('DB_PASSWORD',       'WgV6HbpL');
define('DB_HOST',           'localhost');



ini_set('display_errors',   HOST_TYPE == 'local' ? 'On' : 'Off');