<?php

if(isset($_POST['darkmode'])) {
    if (isset($_POST['darkmode'])) {
        $darkmode = true;
    } else {
        $darkmode = false;
    }

    $SQL = $db->prepare("UPDATE `customers` SET `darkmode` = :darkmode WHERE `id` = :id");
    $SQL->execute(array(":darkmode" => $darkmode, ":id" => $userid));

    $_SESSION['success_msg'] = 'Design-Settings gespeichert.';

}