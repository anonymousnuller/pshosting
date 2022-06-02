<?php

$order_success = false;

if(isset($_POST['order'])){

    $error = null;

    if($helper->getSetting('webspace') == 0){
        $error = 'Die Bestellung ist derzeit deaktiviert';
    }

    if(!$user->sessionExists($_COOKIE['session_token'])){
        $error = 'Bitte logge dich erst ein';
    }

    if(empty($_POST['domainName'])){
        $error = 'Bitte gebe einen Domain Name an';
    }

    if($validate->is_domain_name($_POST['domainName']) == false){
        $error = 'Bitte gebe einen gültigen Domain Name an';
    }

    if(!isset($_POST['wiederruf'])){
        $error = 'Du musst Unsere Wiederrufsbestimmungen akzeptieren';
    }

    if(!isset($_POST['agb'])){
        $error = 'Du musst Unsere AGB und Datenschutzbestimmungen akzeptieren';
    }

//    if(empty($_POST['runtime'])){
//        $error = 'runtime not found';
//    }
    $runtime = 30;
    if($validate->duration($runtime) != true){
        $error = 'Bitte gebe eine gültige Laufzeit an';
    }

    if(empty($_POST['planName'])){
        $error = 'Es konnte kein Webspace Paket gefunden werden';
    }

    if($plesk->getPrice($_POST['planName']) == false){
        $error = 'Es konnte kein Webspace Paket mit diesem Namen gefunden werden';
    }

    $db_price = $plesk->getPrice($_POST['planName']);
    $price = $db_price * $validate->getIntervalFactor($runtime);
    $price = number_format($price,2);

    if($amount < $price){
        $error = 'Du hast leider nicht genügend Guthaben';
        $_SESSION['error_msg'] = 'Du hast leider nicht genügend Guthaben';
        header('Location: '.env('URL').'payment/charge');
        die();
    }

    if($price == 0){
        $error = 'Ungültige Anfrage bitte versuche es erneut (1001)';
    }

    if(empty($error)){

        $discord_webhook->callWebhook('Soeben wurde ein neuer Webspace (' . $_POST['planName'] . ') bestellt von '.$username . '.');

        $SQL = $db->prepare("SELECT * FROM `webspaces_packs_normal` WHERE `plesk_id` = :plesk_id");
        $SQL->execute(array(":plesk_id" => $_POST['planName']));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);

        $node_id = $plesk->getHosts()['id'];

        $queue = [
            "action" => "WEBSPACE_ORDER",
            "data" => [
                "user_id" => $userid,
                "username" => $username.$userid,
                "email" => $email,
                "price" => $db_price,
                "planName" => $_POST['planName'],
                "domainName" => $_POST['domainName'],
                "node_id" => $node_id,
                "runtime" => 30
            ]
        ];

        $queue = json_encode($queue);
        $insert = $db->prepare("INSERT INTO `queue`(`user_id`, `payload`) VALUES (?,?)");
        $insert->execute(array($userid, $queue));

        $user->removeMoney($price, $userid);
        $user->addOrder($userid,'-'.$price,'Webspace Bestellung');

        $_SESSION['success_msg'] = 'Vielen Dank! Dein Produkt wird gleich eingerichtet';
        header('Location: '.env('URL').'manage/webspaces/');

    } else {
        $_SESSION['error_msg'] = $error;
        header('Location: '.env('URL').'order/webspace/');
    }

}