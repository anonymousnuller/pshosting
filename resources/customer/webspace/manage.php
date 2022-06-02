<?php
$currPage = 'customer_Webspace verwalten';
include BASE_PATH . 'software/controller/PageController.php';
include BASE_PATH . 'software/managing/customer/webspace/manage.php';
?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow mb-5">
                            <div class="card-header">
                                <h4 class="card-title">Übersicht</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Hostsystem</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $plesk->getHostInfo($serverInfos['node_id'], 'node_id'); ?></span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Hostsystem-URL</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><a href="<?= $plesk->getHostInfo($serverInfos['node_id'], 'url'); ?>"><?= $plesk->getHostInfo($serverInfos['node_id'], 'url'); ?></a></span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Hostsystem IPv4-Adresse</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $webhostInfos['ip']; ?></span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Hostsystem IPv6-Adresse</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $webhostInfos['ipv6']; ?></span>
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="text-muted">
                                            <hr>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Plesk-User</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $user->getDataBySession($_COOKIE['session_token'],'username'); ?><?= $userid; ?></span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Plesk-Passwort</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2">
                                                <span class="noselect" id="plesk_password">*********************************</span>
                                                <span style="cursor: pointer;" id="plesk_icon" onclick="passwordEye('plesk');">
                                                    <i class="far fa-eye"></i>
                                                </span>

                                                <i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $serverInfos['plesk_password']; ?>" data-toggle="tooltip" title="Passwort kopieren"></i>
                                            </span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>FTP-User</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $serverInfos['ftp_name']; ?></span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>FTP-Passwort</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2">
                                                <span class="noselect" id="ftp_password">*********************************</span>
                                                <span style="cursor: pointer;" id="ftp_icon" onclick="passwordEye('ftp');">
                                                    <i class="far fa-eye"></i>
                                                </span>

                                                <i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $serverInfos['ftp_password']; ?>" data-toggle="tooltip" title="Passwort kopieren"></i>
                                            </span>
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="text-muted">
                                        <hr>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Paket</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $plesk->getName($serverInfos['plan_id']); ?></span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Domain</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $serverInfos['domainName']; ?></span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Webspeicher</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"> / <?= $plesk->getPlan($serverInfos['plan_id'], 'disc'); ?>GB</span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Preis:</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2"><?= $serverInfos['price']; ?>€ / Monat</span>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <strong>Laufzeit:</strong>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="text-muted mb-2 font-13">
                                            <span class="ml-2">
                                                <span id="countdown">Lädt...</span>
                                            </span>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow mb-5">
                            <div class="card-header">
                                <h4 class="card-title">Verwaltung</h4>
                            </div>
                            <div class="card-body">

                                <form method="post">
                                    <button type="submit" class="btn btn-block btn-outline-primary" name="login"><b><i class="fas fa-sign-in-alt"></i> Einloggen</b></button>
                                </form>
                                <br>
                                <a class="btn btn-block btn-outline-warning" href="<?= $helper->url(); ?>renew/webspace/<?= $id; ?>"><b><i class="fas fa-history"></i> Verlängern</b></a>
                                <br><br>
                                <center>
                                    <font size="3">
                                        Beachte dass du um den "Einloggen"-Button nutzen zu können,<br>
                                        Popups für das Kundecenter erlauben musst.
                                    </font>
                                </center>
                                <br>
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>

                    <!--div class="col-md-12">
                        <div class="card shadow mb-5">
                            <div class="card-header">
                                <h4 class="card-title">Bewerten</h4>
                            </div>

                            <div class="card-body">
                                <p>Du bist mit deinem Webspace-Paket zufrieden? Dann lass uns doch eine Bewertung da!</p>

                                <button class="btn btn-outline-primary" style="text-align: center;">
                                    <a href="https://de.trustpilot.com/review/www.german-host.eu" target="_blank" rel="noopener">Zu Trustpilot</a>
                                </button>
                            </div>
                        </div>
                    </div-->

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let plesk = true;
    let ftp = true;

    function passwordEye(type) {
        if(type == 'plesk'){
            if(plesk){
                $('#plesk_password').html("<?= $serverInfos['plesk_password']; ?>");
                $('#plesk_icon').html('<i class="far fa-eye-slash"></i>');
                plesk = false;
            } else {
                $('#plesk_password').html('*********************************');
                $('#plesk_icon').html('<i class="far fa-eye"></i>');
                plesk = true;
            }
        }

        if(type == 'ftp'){
            if(ftp){
                $('#ftp_password').html("<?= $serverInfos['ftp_password']; ?>");
                $('#ftp_icon').html('<i class="far fa-eye-slash"></i>');
                ftp = false;
            } else {
                $('#ftp_password').html('*********************************');
                $('#ftp_icon').html('<i class="far fa-eye"></i>');
                ftp = true;
            }
        }
    }
</script>
<script>
    var countDownDate = new Date("<?= $serverInfos['expire_at']; ?>").getTime();
    var x = setInterval(function() {

        var now = new Date().getTime();
        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        if(days == 1){ var days_text = ' Tag' } else { var days_text = ' Tage'; }
        if(hours == 1){ var hours_text = ' Stunde' } else { var hours_text = ' Stunden'; }
        if(minutes == 1){ var minutes_text = ' Minute' } else { var minutes_text = ' Minuten'; }
        if(seconds == 1){ var seconds_text = ' Sekunde' } else { var seconds_text = ' Sekunden'; }

        if(days == 0 && !(hours == 0 && minutes == 0 && seconds == 0)){
            $('#countdown<?= $row["id"]; ?>').html(hours+hours_text+', '+  minutes+minutes_text+' und ' +  seconds+seconds_text);
            if(days == 0 && hours == 0 && !(minutes == 0 && seconds == 0)){
                $('#countdown<?= $row["id"]; ?>').html(minutes+minutes_text+' und '+  seconds+seconds_text);
                if(days == 0 && hours == 0 && minutes == 0 && !(seconds == 0)){
                    $('#countdown<?= $row["id"]; ?>').html(seconds+seconds_text);
                }
            }
        } else {
            $('#countdown').html(days+days_text+', '+  hours+hours_text+', '+  minutes+minutes_text+' und '+  seconds+seconds_text);
        }

        if (distance <= 0) {
            clearInterval(x);
        }
    }, 1000);
</script>