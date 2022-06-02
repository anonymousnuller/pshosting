<?php

global $url;

abstract class Controller {

    // set siteName
    public function siteName() {
        return env('APP_NAME');
    }

    // set db
    public static function db() {
        $db = new PDO('mysql:host=' . env('DATABASE_HOST') . ';charset=utf8;dbname=' . env('DATABASE_NAME'), env('DATABASE_USERNAME'), env('DATABASE_PASSWORD'));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $db;
    }

    // set url
    public function url() {
        return env('URL');
    }

    // set style url
    public function styleUrl() {
        return env('STYLE_URL');
    }

    // set image url
    public function imageUrl() {
        return env('IMAGE_URL');
    }
    // set nl2br2
    public function nl2br2($string) {
        $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);

        return $string;
    }

    // set cookie
    public function setcookie($name, $variable, $time = '777600', $path = '/', $domain, $secure = 0) {
        setcookie($name, $variable, time()+$time, $path, $domain, $secure);
    }
}