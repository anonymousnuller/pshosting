<?php

if(isset($_POST['activeAffiliate'])) {
    $error = null;

    if(empty($error)) {

        $aff_id = $helper->generateRandomString('3', '01234567890') . '' . $helper->generateRandomString('2', '0123456789abcdefghijklmnopqrstuvwxyz');

        $affiliate->setActive($userid, $aff_id, $helper->url() . 'aff/id/' . $aff_id . '/', $username);

        echo sendSuccess('');
        header('refresh: 0.5');
    } else {
        echo sendError($error);
    }
}