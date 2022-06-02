<?php

// set env function
function env($key, $default = null) {
    $var = getenv($key);

    if(isset($var)) {
        return $var;
    }

    return $default;
}

// create dd function, when not exists
if (!function_exists('dd')) {
    function dd()
    {
        array_map(function($x) {
            dump($x);
        }, func_get_args());
        die;
    }
}

// create number_format function

function format_number($number){
    return floor($number * 100) / 100;
}