<?php

define('DEV_MODE',          true);
define('PROTOCOL',          'http://');
define('HOST_NAME',         'www.basic.local');
define('HOST_TYPE',         'local');
define('PROJECT_NAME',      'BASIC');
define('PROJECT_NAME_FULL', 'BASIC Template');

define('DB_DATABASE',       'project');
define('DB_USER',           'root');
define('DB_PASSWORD',       '');
define('DB_HOST',           'localhost');



ini_set('display_errors',   HOST_TYPE == 'local' ? 'On' : 'Off');