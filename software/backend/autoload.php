<?php

// include managing controller
include_once BASE_PATH . 'software/controller/Controller.php';

// include backend file
foreach (glob('../software/backend/*.php') as $filename)
{
    if($filename != 'autoload.php'){
        include_once $filename;
    }
}
