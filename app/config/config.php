<?php

// DB Params
define('DB_HOST', '172.18.0.2');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_PORT', 3306);
define('DB_NAME', 'camagru');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// Public Root
define('PUBROOT', dirname(dirname(dirname(__FILE__))).'/public');

// Url Root
define('URL', 'http://localhost');
define('URLROOT', 'http://localhost/camagru');

// Site Name
define('SITENAME', 'Camagru');

//App Version
define('APPVERSION', '1.0.0');

?>