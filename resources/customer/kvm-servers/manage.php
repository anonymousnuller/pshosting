<?php
$currPage = 'customer_KVM Rootserver verwalten';
include BASE_PATH . 'software/controller/PageController.php';

include BASE_PATH . 'software/managing/customer/rootserver/manage.php';

?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content"><!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            <div class="d-flex flex-column-fluid">
                <div class="container">
                    <div class="row">

                        <?php if($serverInfos['curr_traffic'] >= $serverInfos['traffic']){ ?>
                            <div class="col-md-12">
                                <div class="card shadow mb-5">
                                    <div class="card-header"><h1>Traffic Kontingent aufgebraucht 😲</h1></div>
                                    <div class="card-body">
                                        <p style="font-size: 120%;">
                                            Du hast dein aktuelles Traffic Kontingent von <b><?= $available_traffic; ?>GB</b> aufgebraucht.
                                            <br>
                                            Wenn du deinen Server weiterhin verwenden möchtest,
                                            erweitere dein Traffic Kontingent und dein Server wird sofort wieder freigeschaltet.
                                        </p>

                                        <form method="post">
                                            <select class="form-control" name="traffic_amount">
                                                <option value="512">512GB extra Traffic (7.00€)</option>
                                                <option value="1024">1024GB extra Traffic (14.00€)</option>
                                            </select>
                                            <br>
                                            <button type="submit" name="buyTraffic" class="btn btn-outline-primary font-weight-bolder pulse-red">Extra Traffic kostenpflichtig bestellen</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                    </div>

                    <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#nav-main">
                                Übersicht
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#nav-load">
                                Serverauslastung
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#nav-network">
                                Netzwerk
                            </a>
                        </li>
                    </ul>

                    <br>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-main" role="tabpanel" aria-labelledby="nav-main-tab">
                                    <div class="card shadow mb-5">
                                        <div class="card-header">
                                            <h1 class="card-title">Informationen</h1>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Node-ID:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $site->getNodeName($serverInfos['node_id']); ?></span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Server-ID:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $serverInfos['id']; ?></span>
                                                    </p>
                                                </div>

                                                <?php if(is_null($serverInfos['custom_name'])){ ?>
                                                <?php } else { ?>
                                                    <div class="col-md-6">
                                                        <p class="text-muted mb-2 font-13">
                                                            <strong>Produkt Name:</strong>
                                                        </p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p class="text-muted mb-2 font-13">
                                                            <span class="ml-2"><?= $helper->xssFix($serverInfos['custom_name']); ?></span>
                                                        </p>
                                                    </div>
                                                <?php } ?>

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

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Betriebssystem:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $site->getNameOfOS($serverInfos['template_id'], 'name'); ?></span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Hostname:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $serverInfos['hostname']; ?></span> <i class="fas fa-question-circle" style="cursor: help" data-bs-toggle="tooltip" data-bs-placement="top" title="" aria-hidden="true" data-original-title="Der Hostname kann nicht zum verbinden per SSH-Client genutzt werden, nutzen kannst du stattdessen die IP unter 'Netzwerk'"></i>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Status: </strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $state; ?></span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Passwort:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                <span class="ml-2">
                                                    <span id="root_password">************************</span>
                                                    <span style="cursor: pointer;" id="root_icon" onclick="passwordEye('root');">
                                                        <i class="far fa-eye"></i>
                                                    </span>

                                                    <i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?=$serverInfos['password']?>" data-toggle="tooltip" title="Passwort kopieren"></i>
                                                </span>
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
                                                        <strong>Kerne:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $serverInfos['cores']; ?></span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>IP-Adressen:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $serverInfos['addresses']; ?></span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Arbeitsspeicher:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><span id="memory_text"></span></span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Festplatte:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $serverInfos['disc']; ?> GB SSD</span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Uptime</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-5">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2" id="uptime">Lädt...</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-load" role="tabpanel" aria-labelledby="nav-load-tab">
                                    <div class="card shadow mb-5">
                                        <div class="card-header">
                                            <h1 class="card-title">Serverauslastung</h1>
                                        </div>
                                        <div class="card-body">

                                            <label id="cpu_lable"><b>CPU</b></label>
                                            <div class="progress">
                                                <div id="cpu_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                            <br>

                                            <label id="memory_lable">Arbeitsspeicher</label>
                                            <div class="progress">
                                                <div id="memory_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                                <script>
                                                    function refreshStatus() {
                                                        <?php if($serverStatus == 'ONLINE'){ ?>
                                                        $.getJSON('<?= $helper->url(); ?>ajax/getload/<?= $serverInfos["id"]; ?>/', function(data) {
                                                            $('#cpu_lable').html('<b>CPU</b> ' + parseFloat((data.data.cpu)*100).toFixed(2) + '%');
                                                            $('#cpu_progress_bar').css('width', parseFloat((data.data.cpu)*100).toFixed(0)+'%');

                                                            $('#memory_lable').html('<b>Arbeitsspeicher</b> ' + parseFloat((data.data.mem/data.data.maxmem)*100).toFixed(2) + '%');
                                                            $('#memory_text').html(humanFileSize(data.data.mem) + ' von ' + humanFileSize(data.data.maxmem));
                                                            $('#memory_progress_bar').css('width', parseFloat((data.data.mem/data.data.maxmem)*100).toFixed(0)+'%');
                                                        });
                                                        <?php } else { ?>
                                                        $('#memory_text').html('0 MiB von <?= $serverInfos[memory]; ?>MiB');
                                                        $('#disk_text').html('0 MiB von <?= $serverInfos[disc]; ?>GB');
                                                        <?php } ?>
                                                    }
                                                    refreshStatus();
                                                    setInterval(function () {
                                                        refreshStatus();
                                                    }, 5000);
                                                </script>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="nav-network" role="tabpanel" aria-labelledby="nav-network-tab">
                                    <div class="card shadow mb-5">
                                        <div class="card-header">
                                            <h1 class="card-title">Netzwerk</h1>
                                        </div>
                                        <div class="card-body">
                                            <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">IPv4 Adresse</th>
                                                        <th scope="col">Gateway</th>
                                                        <th scope="col">eingehender Traffic</th>
                                                        <th scope="col">ausgehender Traffic</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($site->getAddressesFromServer($id) as $ip) { ?>

                                                        <tr>
                                                            <td id="ip_address"><?= $ip['ip']; ?> &nbsp;<i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $ip['ip']; ?>" data-toggle="tooltip" title="IPv4 kopieren"></i></td>
                                                            <td><?= $ip['gateway']; ?> &nbsp;<i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $ip['gateway']; ?>" data-toggle="tooltip" title="Gateway kopieren"></i></td>
                                                            <td id="net_in"></td>
                                                            <td id="net_out"></td>
                                                        </tr>

                                                        <script>
                                                            function refreshNetworkInfo() {
                                                                <?php if($serverStatus == 'ONLINE'){ ?>
                                                                $.getJSON('<?= $helper->url(); ?>ajax/getload/<?= $serverInfos["id"]; ?>/', function(response) {

                                                                    $('#net_in').html(humanFileSize(response.data.netin));
                                                                    $('#net_out').html(humanFileSize(response.data.netout));
                                                                    //$('#uptime').html(data.result.uptime);

                                                                    var distance = Number(response.data.uptime);

                                                                    var days = Math.floor(distance / (3600 * 24));
                                                                    var hours = Math.floor(distance % (3600*  24) / 3600);
                                                                    var minutes = Math.floor(distance % 3600 / 60);
                                                                    var seconds = Math.floor(distance % 60);

                                                                    if(days == 1){ var days_text = ' Tag' } else { var days_text = ' Tage'; }
                                                                    if(hours == 1){ var hours_text = ' Stunde' } else { var hours_text = ' Stunden'; }
                                                                    if(minutes == 1){ var minutes_text = ' Minute' } else { var minutes_text = ' Minuten'; }
                                                                    if(seconds == 1){ var seconds_text = ' Sekunde' } else { var seconds_text = ' Sekunden'; }

                                                                    if(days == 0 && !(hours == 0 && minutes == 0 && seconds == 0)){
                                                                        $('#uptime').html(hours+hours_text+', '+  minutes+minutes_text+' und ' +  seconds+seconds_text);
                                                                        if(days == 0 && hours == 0 && !(minutes == 0 && seconds == 0)){
                                                                            $('#uptime').html(minutes+minutes_text+' und '+  seconds+seconds_text);
                                                                            if(days == 0 && hours == 0 && minutes == 0 && !(seconds == 0)){
                                                                                $('#uptime').html(seconds+seconds_text);
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $('#uptime').html(days+days_text+', '+  hours+hours_text+', '+  minutes+minutes_text+' und '+  seconds+seconds_text);
                                                                    }
                                                                });
                                                                <?php } else { ?>
                                                                $('#net_out').html('Keine Info.');
                                                                $('#net_in').html('Keine Info.');
                                                                $('#uptime').html('Dein Server ist gestoppt.');
                                                                <?php } ?>
                                                            }

                                                            refreshNetworkInfo();
                                                            setInterval(function () {
                                                                refreshNetworkInfo();
                                                            }, 1000);
                                                        </script>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>


                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow mb-5">
                                <div class="card-header">
                                    <h1 class="card-title">Server steuern</h1>
                                </div>
                                <!--div class="card-header"><h3>Server steuern</h3></div-->
                                <div class="card-body">

                                    <?php if($serverStatus == 'ONLINE'){ ?>
                                        <form method="post" id="stopServer">
                                            <input name="sendStop" hidden>
                                            <button type="button" style="cursor: not-allowed;" disabled class="btn btn-outline-success btn-block">
                                                <b><i class="fas fa-play"></i>&nbsp;  Starten </b>
                                            </button>
                                            <button type="button" onclick="stop();" class="btn btn-outline-primary btn-block">
                                                <b><i class="fas fa-stop"></i>&nbsp; Stoppen </b>
                                            </button>
                                        </form>
                                        <br>
                                        <form method="post" id="restartServer">
                                            <input name="sendRestart" hidden>
                                            <button type="button" onclick="restart();" class="btn btn-outline-warning btn-block">
                                                <b><i class="fas fa-power-off"></i>&nbsp; Neustarten </b>
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <form method="post">
                                            <button type="submit" name="sendStart" class="btn btn-outline-success btn-block">
                                                <b><i class="fas fa-play"></i>&nbsp; Starten </b>
                                            </button>
                                            <button type="submit" style="cursor: not-allowed;" disabled class="btn btn-outline-primary btn-block" data-toggle="tooltip" title="Der Server ist bereits gestoppt">
                                                <b><i class="fas fa-stop"></i>&nbsp; Stoppen </b>
                                            </button>
                                            <br>
                                            <button type="submit" style="cursor: not-allowed;" disabled class="btn btn-outline-warning btn-block" data-toggle="tooltip" title="Der Server ist nicht gestartet">
                                                <b><i class="fas fa-power-off"></i>&nbsp; Neustarten </b>
                                            </button>
                                        </form>
                                    <?php } ?>

                                    <br><hr><br>

                                    <a class="btn btn-block btn-outline-warning" href="<?= $helper->url(); ?>renew/rootserver/<?= $id; ?>/"><b><i class="fas fa-history"></i>&nbsp; Verlängern</b></a>
                                    <br><hr><br>

                                        <button type="button" onclick="resetPassword();" class="btn btn-outline-info btn-block">
                                            <i class="fas fa-key"></i>&nbsp; Root-Passwort zurücksetzen
                                        </button>


                                    <button data-bs-toggle="modal" data-bs-target="#reinstallModal" type="button" class="btn btn-outline-warning btn-block">
                                        <i class="fas fa-redo-alt"></i>&nbsp; Neuinstallieren
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form method="post" id="resetPassword">
    <input hidden="hidden" name="resetPassword">
</form>


<div class="modal fade" id="reinstallModal" tabindex="-1" role="dialog" aria-labelledby="reinstallModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="reinstallModalLabel">Neuinstallation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" id="reinstallServer">

                    <label for="serverOS">Wähle dein neues Betriebssystem aus</label>
                    <select class="form-control" name="serverOS" id="serverOS">
                        <?php
                        $SQL = $db->prepare("SELECT * FROM `kvm_servers_os` WHERE `type` = :type");
                        $SQL->execute(array(":type" => 'PROXMOX'));
                        if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                        <?php } } ?>
                    </select>


                    <br>
                    <input hidden name="reinstallServer">
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary text-uppercase font-weight-bolder" data-dismiss="modal"><i class="fas fa-ban"></i> Nein lieber doch nicht</button>
                <button type="button" onclick="reinstallServer();" class="btn btn-outline-success text-uppercase font-weight-bolder"><i class="fas fa-share-square"></i> Neuinstallation starten</button>
                <!--onclick="resetPassword();" -->
            </div>
        </div>
    </div>
</div>

        <script>
            function resetPassword() {
                Swal.fire({
                    title: 'Neues Root-Passwort setzen?',
                    text: "Wenn du auf 'Ja' klickst wird dein Root-Passwort geändert und der Server neugestartet.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ja',
                    cancelButtonText: 'Nein'
                }).then((result) => {
                    if (result.value) {
                        document.getElementById('resetPassword').submit();
                    }
                })
            }

            function reinstallServer() {
                Swal.fire({
                    title: 'Server wirklich neuinstallieren?',
                    text: "Wenn Du auf 'Ja' klickst, wird dein Server neuinstalliet und alle aktuellen Daten werden unwiderruflich gelöscht!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ja',
                    cancelButtonText: 'Nein'
                }).then((result) => {
                    if(result.value) {
                        document.getElementById('reinstallServer').submit();
                    }
                })
            }

            function stop() {
                Swal.fire({
                    title: 'Server wirklich stoppen?',
                    text: "Wenn du auf 'Ja' klickst wird der Server gestoppt",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ja',
                    cancelButtonText: 'Nein'
                }).then((result) => {
                    if (result.value) {
                        document.getElementById('stopServer').submit();
                    }
                })
            }

            function restart() {
                Swal.fire({
                    title: 'Server wirklich neustarten?',
                    text: "Wenn du auf 'Ja' klickst wird der Server neugestartet",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ja',
                    cancelButtonText: 'Nein'
                }).then((result) => {
                    if (result.value) {
                        document.getElementById('restartServer').submit();
                    }
                })
            }

            let rootserver = true;

            function passwordEye(type) {
                if(type == 'root'){
                    if(rootserver){
                        $('#root_password').html("<?=$serverInfos['password']?>");
                        $('#root_icon').html('<i class="far fa-eye-slash"></i>');
                        rootserver = false;
                    } else {
                        $('#root_password').html('************************');
                        $('#root_icon').html('<i class="far fa-eye"></i>');
                        rootserver = true;
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