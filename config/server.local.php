<?php

define('DEV_MODE',          true);
define('PROTOCOL',          'http://');
define('HOST_NAME',         'www.jt1.local');
define('HOST_TYPE',         'local');
define('PROJECT_NAME',      'JIGIRO');
define('PROJECT_NAME_FULL', 'JIGIRO.TECH [ sercices project 2018 ]');

define('DB_DATABASE',       'jigiro');
define('DB_USER',           'root');
define('DB_PASSWORD',       '');
define('DB_HOST',           'localhost');



ini_set('display_errors',   HOST_TYPE == 'local' ? 'On' : 'Off');