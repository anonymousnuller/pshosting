<?php

// set error reporting
error_reporting(E_ALL ^ E_WARNING && E_NOTICE);

// include other files
include_once '../system.php';
include_once BASE_PATH . 'vendor/autoload.php';
include_once BASE_PATH . 'software/Kernel.php';
include_once BASE_PATH . 'software/backend/autoload.php';
include_once BASE_PATH . 'software/notify/sendMail.php';

// define system end
define('SYSTEM_END', round(microtime(true) - SYSTEM_START,4));

// include router app
include_once BASE_PATH . 'router/app.php';