<?php
array_push($success, 'Daten wurden abgefragt');
$state = 'success';
$status_code = 200;

$state = $status->getState($user->getDataById($userid, 'language'));

if($state->state == 'ONLINE') {
    $online = 'ONLINE';
    $title = $state->subtitle;

    $offline_items = $state->offline;
    $online_items = $state->online;
    $all_items = $state->all;
} else {
    $online = 'OFFLINE';
    $title = $state->subtitle;

    $offline_items = $state->offline;
    $online_items = $state->online;
    $all_items = $state->all;


}

if($offline_items == 1) {
    $items_text = 'ist'; } else { $items_text = 'sind'; }

if($offline_items == 1) { $dienst = 'Dienst'; } else { $dienst = 'Dienste'; }

$res->data->status->state = $online;
$res->data->result->title = $title;
$res->data->result->offline_items = $offline_items;
$res->data->result->online_items = $online_items;
$res->data->result->all_items = $all_items;
if($online) {
    $res->data->result->status_text = '<span>Laut unserem Monitoring-System sind ' . $online_items . ' von ' . $all_items . ' Diensten verfügbar.<br><br>
                                        Link zu unserer Status-Seite: <a href="'. env('STATUS_URL') . '">hier klicken</a>
</span>';

    $res->data->result->svg_image = '<span class="svg-icon svg-icon-2hx svg-icon-success me-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M10.3 14.3L11 13.6L7.70002 10.3C7.30002 9.9 6.7 9.9 6.3 10.3C5.9 10.7 5.9 11.3 6.3 11.7L10.3 15.7C9.9 15.3 9.9 14.7 10.3 14.3Z" fill="black"/>
                                                <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM11.7 15.7L17.7 9.70001C18.1 9.30001 18.1 8.69999 17.7 8.29999C17.3 7.89999 16.7 7.89999 16.3 8.29999L11 13.6L7.70001 10.3C7.30001 9.89999 6.69999 9.89999 6.29999 10.3C5.89999 10.7 5.89999 11.3 6.29999 11.7L10.3 15.7C10.5 15.9 10.8 16 11 16C11.2 16 11.5 15.9 11.7 15.7Z" fill="black"/>
                                                </svg></span';

} else {
    $res->data->result->status_text = '<span>
                                                Laut unserem Monitoring-System sind ' . $online_items . ' von ' . $all_items . ' Dienste verfügbar. Damit ' . $items_text . ' ' . $offline_items .' ' . $dienst . ' nicht erreichbar.<br><br>

                                                    Folgende Dienste sind nicht verfügbar:<br>
                                                    <span id="monitor_text"></span>
                                                <br>
                                                    Sollte dieses Problem für fünf Minuten dauerhaft bestehen, schaltet sich unser automatisches Monitoring an und schickt unserer Notfallbesatzung ein Notfall-Ticket!
                                                    <br>
                                                    Link zu unserer Status-Seite: <a href="'. env('STATUS_URL') . '">hier klicken</a>
                                                </span>';

    $res->data->result->svg_image = '<span class="svg-icon svg-icon-2hx svg-icon-danger me-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M12 10.6L14.8 7.8C15.2 7.4 15.8 7.4 16.2 7.8C16.6 8.2 16.6 8.80002 16.2 9.20002L13.4 12L12 10.6ZM10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 8.99999 16.4 9.19999 16.2L12 13.4L10.6 12Z" fill="black"/>
                                        <path d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM13.4 12L16.2 9.20001C16.6 8.80001 16.6 8.19999 16.2 7.79999C15.8 7.39999 15.2 7.39999 14.8 7.79999L12 10.6L9.2 7.79999C8.8 7.39999 8.2 7.39999 7.8 7.79999C7.4 8.19999 7.4 8.80001 7.8 9.20001L10.6 12L7.8 14.8C7.4 15.2 7.4 15.8 7.8 16.2C8 16.4 8.3 16.5 8.5 16.5C8.7 16.5 9 16.4 9.2 16.2L12 13.4L14.8 16.2C15 16.4 15.3 16.5 15.5 16.5C15.7 16.5 16 16.4 16.2 16.2C16.6 15.8 16.6 15.2 16.2 14.8L13.4 12Z" fill="black"/>
                                        </svg></span>';
}

