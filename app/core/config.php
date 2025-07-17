<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__, 2));
define('APPS', ROOT . DS . 'app');
define('CORE', APPS . DS . 'core');
define('CONTROLLERS', APPS . DS . 'controllers');
define('MODELS', APPS . DS . 'models');
define('VIEWS', APPS . DS . 'views');

define('DB_HOST',     'c0tme.h.filess.io');
define('DB_USER',     'COSC4806001JS2_figurewhom');
define('DB_PASS',     $_ENV['DB_PASS']);
define('DB_DATABASE', 'COSC4806001JS2_figurewhom');
define('DB_PORT',     '61000');
