<?php

$currPage = 'front_Kundenkonto bestätigen';
include BASE_PATH . 'software/controller/PageController.php';

$key = $_GET['key'];
if(isset($key) && !empty($key)) {

    $SQL = $db->prepare("SELECT COUNT(*) FROM `customers` WHERE `verify_code` = :key");
    $SQL->execute(array(":key" => $key));

    $countKey = $SQL->fetchColumn(0);

    if($countKey == 1) {
        $update = $db->prepare("UPDATE `customers` SET `state` = :state, `verify_code` = :newKey WHERE `verify_code` = :key");
        $update->execute(array(":key" => $key, ":state" => 'active', ":newKey" => NULL));

        $_SESSION['success_msg'] = 'Dein Kundenkonto wurde aktiviert.';
        header('Location: ' . env('URL') . 'auth/login/');
        die();
    } else {
        $_SESSION['error_msg'] = 'Dein Kundenkonto ist bereits bestätigt.';
        header('Location: ' . env('URL') . 'auth/login/');
        die();
    }

} else {
    $_SESSION['error_msg'] = 'Bitte gebe einen gültigen Verifizierungs-Code ein.';
    header('Location: ' . env('URL') . 'auth/login/');
    die();
}

?>