<?php


array_push($success, 'Daten wurden abgefragt');
$state = 'success';
$status_code = 200;

function ToObject($array) {

    // Create new stdClass object
    $object = new stdClass();

    // Use loop to convert array into
    // stdClass object
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = ToObject($value);
        }
        $object->$key = $value;
    }
    return $object;
}

function test() {
    return fn($data) => $data['state'] == 'UNAVAILABLE';

}

$monitors = $status->getMonitors($user->getDataById($userid, 'language'));

foreach ($monitors as $api) {
    $state = $api->data;

    var_dump($state);
}


/*
$i = 0;
foreach ($response as $state => $monitor) {

    echo "\$response[$state] \n";


    if ($status == 'UNAVAILABLE') {
        $name = $api->name;
        $sla = $api->sla;

        $text = '- ' . $name . ' (Verfügbarkeit: ' . $sla . '%)<br>';
    } else {
        $name = 'fehler';
        $sla = '0.000';

        $text = '- ' . $name . ' (Verfügbarkeit: ' . $sla . '%)<br>';
    }


    $name_api = array_push($name_array, $name);
    $sla_api = array_push($sla_array, $sla);
    $text_api = array_push($text_array, json_encode($text));


    $i++;
}**/



