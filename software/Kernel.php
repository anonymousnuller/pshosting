<?php

// start php session
ob_start();
session_start();

// set default datetime
$date = new DateTime(null, new DateTimeZone('Europe/Berlin'));
$date->format('d.m.Y H:i:s');

// create immutable with dotenv
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// set globals and other
include_once BASE_PATH . 'software/Globals.php';
if(env('debug','false') == 'true'){
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
}

// require database connection with dotenv
$dotenv->required(['DATABASE_HOST', 'DATABASE_NAME', 'DATABASE_USERNAME', 'DATABASE_PASSWORD']);

\Sentry\init(['dsn' => 'https://abdab99902df47dcb9aeecc8e76d7fc0@o1078844.ingest.sentry.io/6437882']);