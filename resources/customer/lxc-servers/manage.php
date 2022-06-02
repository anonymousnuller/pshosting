<?php
$currPage = 'back_LXC V-Server verwalten';
include BASE_PATH.'software/controller/PageController.php';
include BASE_PATH.'software/managing/customer/vserver/manage.php';
?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content"><!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">

            <div class="d-flex flex-column-fluid">
                <div class="container">
                    <div class="row">

                        <?php if($serverInfos['curr_traffic'] >= $available_traffic){ ?>
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
                                            <option value="512">512GB extra Traffic (5.00€)</option>
                                            <option value="1024">1024GB extra Traffic (10.00€)</option>
                                        </select>
                                        <br>
                                        <button type="submit" name="buyTraffic" class="btn btn-outline-primary font-weight-bolder">Extra Traffic kostenpflichtig bestellen</button>
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

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#nav-command">
                                Command Presets
                            </a>
                        </li>
                    </ul>

                    <br>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-main" role="tabpanel" aria-labelledby="nav-main-tab">
                                    <div class="card shadow mb-5">
                                        <div class="card-header"><h1 class="card-title">Informationen</h1></div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Node-ID:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $serverInfos['node_id']; ?></span>
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
                                                        <span class="ml-2"><?= $lxc->getOS($serverInfos['template_id']); ?></span>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <strong>Hostname:</strong>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="text-muted mb-2 font-13">
                                                        <span class="ml-2"><?= $serverInfos['hostname']; ?></span> <i class="fas fa-question-circle" style="cursor: help" data-toggle="tooltip" data-placement="top" title="" aria-hidden="true" data-original-title="Der Hostname kann nicht zum verbinden per SSH-Client genutzt werden, nutzen kannst du stattdessen die IP unter 'Netzwerk'"></i>
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

                                                            <i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $root_password; ?>" data-toggle="tooltip" title="Passwort kopieren"></i>
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
                                                        <span class="ml-2"><span id="disk_text"></span> SSD</span>
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
                                                <div id="cpu_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                            <br>

                                            <label id="memory_lable">Arbeitsspeicher</label>
                                            <div class="progress">
                                                <div id="memory_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                            <br>

                                            <label id="disk_lable">Festplatte</label>
                                            <div class="progress">
                                                <div id="disk_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                            <br>

                                            <label id="swap_lable">SWAP Speicher</label>
                                            <div class="progress">
                                                <div id="swap_progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                            <script>

                                                function refreshStatus() {

                                                    <?php if($serverStatus == 'ONLINE'){ ?>
                                                    $.getJSON('<?= $helper->url(); ?>ajax/getload/lxc/<?= $serverInfos["id"]; ?>/', function(data) {
                                                        //$('#cpu_progress_bar').html(parseFloat((data.data.cpu/data.data.cpus)*100).toFixed(2) + '%');
                                                        $('#cpu_lable').html('<b>CPU</b> ' + parseFloat((data.data.cpu)*100).toFixed(2) + '%');
                                                        $('#cpu_progress_bar').css('width', parseFloat((data.data.cpu)*100).toFixed(0)+'%');

                                                        //$('#memory_progress_bar').html(parseFloat((data.data.mem/data.data.maxmem)*100).toFixed(2) + '%');
                                                        $('#memory_lable').html('<b>Arbeitsspeicher</b> ' + parseFloat((data.data.mem/data.data.maxmem)*100).toFixed(2) + '%');
                                                        $('#memory_text').html(humanFileSize(data.data.mem) + ' von ' + humanFileSize(data.data.maxmem));
                                                        //$('#memory_progress_bar').css('width', parseFloat((data.data.mem/data.data.maxmem)*100).toFixed(0)+'%');
                                                        $('#memory_progress_bar').css('width', parseFloat((data.data.mem/data.data.maxmem)*100).toFixed(0)+'%');

                                                        //$('#disk_progress_bar').html(parseFloat((data.data.disk/data.data.maxdisk)*100).toFixed(2) + '%');
                                                        $('#disk_lable').html('<b>Festplatte</b> ' + parseFloat((data.data.disk/data.data.maxdisk)*100).toFixed(2) + '%');
                                                        $('#disk_text').html(humanFileSize(data.data.disk) + ' von ' + humanFileSize(data.data.maxdisk));
                                                        $('#disk_progress_bar').css('width', parseFloat((data.data.disk/data.data.maxdisk)*100).toFixed(0)+'%');

                                                        //$('#swap_progress_bar').html(parseFloat((data.data.swap/data.data.maxswap)*100).toFixed(2) + '%');
                                                        $('#swap_lable').html('<b>SWAP Speicher</b> ' + parseFloat((data.data.swap/data.data.maxswap)*100).toFixed(2) + '%');
                                                        $('#swap_progress_bar').css('width', parseFloat((data.data.swap/data.data.maxswap)*100).toFixed(0)+'%');
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
                                                            });
                                                            <?php } else { ?>
                                                            $('#net_out').html('Keine Info.');
                                                            $('#net_in').html('Keine Info.');
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

                                <div class="tab-pane fade" id="nav-command" role="tabpanel" aria-labelledby="nav-command-tab">
                                    <div class="card shadow mb-5">
                                        <div class="card-header">
                                            <h1 class="card-title">Command Presets erstellen</h1>
                                        </div>
                                        <div class="card-body">

                                            <form method="post">
                                                <label>Icon</label>
                                                <select class="form-control" name="icon">
                                                    <option value="fas fa-server"><i class="fas fa-server"></i> Server</option>
                                                    <option value="fas fa-hdd"><i class="fas fa-hdd"></i> HDD</option>
                                                    <option value="fas fa-play"><i class="fas fa-play"></i> Start</option>
                                                    <option value="fas fa-stop"><i class="fas fa-stop"></i> Stop</option>
                                                    <option value="fas fa-trash"><i class="fas fa-trash"></i> Trash</option>
                                                    <option value="fas fa-gamepad"><i class="fas fa-gamepad"></i> Controller</option>
                                                    <option value="fas fa-trophy"><i class="fas fa-trophy"></i> Trophy</option>
                                                    <option value="fas fa-gem"><i class="fas fa-gem"></i> Diamond</option>
                                                </select>

                                                <br>

                                                <label>Beschreibung</label>
                                                <textarea class="form-control" name="desc" placeholder="Mein Command Preset fürs Updaten"></textarea>

                                                <br>

                                                <label>Befehl</label>
                                                <textarea class="form-control" name="command" placeholder="apt update && apt upgrade -y"></textarea>

                                                <br>

                                                <button type="submit" name="createPreset" class="btn btn-outline-primary text-uppercase font-weight-bolder"><i class="fas fa-plus-square"></i> Preset erstellen</button>
                                            </form>

                                        </div>
                                    </div>

                                    <div class="card shadow mb-5">
                                        <div class="card-header">
                                            <h1 class="card-title">Meine Command Presets</h1>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <?php
                                                $SQL = $db->prepare("SELECT * FROM `lxc_servers_command_presets` WHERE `server_id` = :server_id");
                                                $SQL->execute(array(":server_id" => $id));
                                                if ($SQL->rowCount() != 0) {
                                                while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){ ?>
                                                <div class="col-md-1">
                                                    <form method="post">
                                                        <input hidden name="preset" value="<?= $row['id']; ?>">
                                                        <button data-toggle="tooltip" title="<?= $helper->xssFix($row['desc']); ?>" class="btn btn-outline-primary" type="submit" name="execPreset"> <i class="<?= $helper->xssFix($row['icon']); ?>"></i> </button>
                                                    </form>
                                                </div>
                                                <?php } } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card shadow mb-5">
                                        <div class="card-header">
                                            <h1 class="card-title">Command Presets löschen</h1>
                                        </div>
                                        <div class="card-body">

                                            <form method="post">
                                                <label>Preset</label>
                                                <select class="form-control" name="preset">
                                                    <option disabled selected>Bitte auswählen</option>
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_command_presets` WHERE `server_id` = :server_id");
                                                    $SQL->execute(array(":server_id" => $id));
                                                    if ($SQL->rowCount() != 0) {
                                                    while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){ ?>
                                                    <option value="<?= $row['id']; ?>"><?= $row['desc']; ?></option>
                                                    <?php } } ?>
                                                </select>

                                                <br>

                                                <button type="submit" name="deletePreset" class="btn btn-outline-primary text-uppercase font-weight-bolder"><i class="fas fa-trash"></i> Preset löschen</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-4">

                            <?php if($vmsoftware->getOpenInstalls($serverInfos['id'])) { ?>
                                <div class="card card-custom gutter-b" id="kt_blockui_card">
                            <?php } ?>
                            <div class="card shadow mb-5">
                            <div class="card-header"><h1>Server steuern</h1></div>
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

                                    <a class="btn btn-block btn-outline-warning" href="<?= $helper->url(); ?>renew/vserver/<?= $id; ?>"><b><i class="fas fa-history"></i>&nbsp; Verlängern</b></a>

                                    <?php if(is_null($serverInfos['pack_name'])){ ?>
                                    <a class="btn btn-block btn-outline-info" href="<?= $helper->url(); ?>reconfigure/vserver/<?= $id; ?>"><b><i class="fas fa-exchange-alt"></i>&nbsp; Up / Downgrade</b></a>
                                    <?php } ?>

                                    <br><hr><br>

                                    <button data-bs-toggle="modal" data-bs-target="#reinstallServerModal" type="button" class="btn btn-outline-warning btn-block"><b><i class="fas fa-redo-alt"></i>&nbsp; Server neuinstallieren</b></button>
                                    <button data-bs-toggle="modal" data-bs-target="#newRootpasswordModal" type="button" class="btn btn-outline-info btn-block"><b><i class="fas fa-key"></i>&nbsp; Neues Rootpasswort</b></button>
                                    <button data-bs-toggle="modal" data-bs-target="#softwareInstallerModal" type="button" class="btn btn-outline-primary btn-block"><b><i class="fas fa-gamepad"></i>&nbsp; Software installieren</b></button><br>
                                    <div style="text-align: center;">
                                        Informationen zum Software Installer <a class="text-hover-primary" style="font-weight: bold; text-decoration: underline; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#oneclickreadme">hier</a>
                                    </div>

                                </div>
                            </div>
                            <?php if ($vmsoftware->getOpenInstalls($serverInfos['id'])) { ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reinstallServerModal" tabindex="-1" role="dialog" aria-labelledby="reinstallServerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reinstallServerModalLabel">LXC V-Server neuinstallieren</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" id="reinstallServer">

                    <label for="serverOS">Betriebssystem</label>
                    <br>
                    <select class="form-control" name="serverOS" id="serverOS">
                        <?php
                        $SQL = $db->prepare("SELECT * FROM `lxc_servers_os` ORDER BY `id` DESC");
                        $SQL->execute();
                        if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                        <?php } } ?>
                    </select>

                    <br>
                    <input hidden name="reinstallServer">
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary text-uppercase font-weight-bolder" data-bs-dismiss="modal"><i class="fas fa-ban"></i> Nein lieber doch nicht</button>
                <button type="button" onclick="reinstall();" class="btn btn-outline-success text-uppercase font-weight-bolder"><i class="fas fa-share-square"></i> Server neuinstallieren</button>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="newRootpasswordModal" tabindex="-1" role="dialog" aria-labelledby="newRootpasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newRootpasswordModalLabel">Neues Rootpasswort setzen</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form method="post" id="resetPassword">

                        <label for="root_password">Rootpasswort</label>
                        <input disabled class="form-control" name="root_password" id="root_password" value="Es wird ein neues Passwort generiert">


                        <br>
                        <input hidden name="setNewRootpassword">
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary text-uppercase font-weight-bolder" data-bs-dismiss="modal"><i class="fas fa-ban"></i> Nein lieber doch nicht</button>
                    <button type="button" onclick="resetPassword();" class="btn btn-outline-success text-uppercase font-weight-bolder"><i class="fas fa-share-square"></i> Passwort ändern</button>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="softwareInstallerModal" tabindex="-1" role="dialog" aria-labelledby="softwareInstallerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="softwareInstallerModalLabel">Software Installieren</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form method="post" id="softwareInstall">

                    <label for="serverOS">Software</label>
                    <br>
                    <select class="form-control" name="software" id="software">
                        <?php
                        $SQL = $db->prepare("SELECT * FROM `lxc_servers_software` ORDER BY `id` DESC");
                        $SQL->execute();
                        if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                        <?php } } ?>
                    </select>

                    <br>
                    <input hidden name="installSoftware">
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary text-uppercase font-weight-bolder" data-bs-dismiss="modal"><i class="fas fa-ban"></i> Nein lieber doch nicht</button>
                <button type="button" onclick="installSoftware();" class="btn btn-outline-success text-uppercase font-weight-bolder"><i class="fas fa-share-square"></i> Software installieren</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="oneclickreadme" tabindex="-1" role="dialog" aria-labelledby="oneclickreadme" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title noselect" id="oneclickreadme">Software Installer: FAQ</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
				    <i aria-hidden="true" class="ki ki-close"></i>
				</button>
            </div>
            <div class="modal-body">
            <h5>Sinusbot</h5>
            <hr>
            Der Sinusbot wird in <code>/opt/sinusbot/</code> installiert.<br>
            Gestartet und Gestoppt kann der Bot über die <code>start.sh</code> / <code>stop.sh</code><br>
            Diese befinden sich im <code>/root/sinusbot/</code> Verzeichnis.<br>
            Das Webinterface des Sinusbot kann über folgende URL erreicht werden:
            <a target="_blank" href="http://<?= $ip['ip']; ?>:8087">http://<?= $ip['ip']; ?>:8087</a><br>
            Das Passwort kann über die <code>ChangePassword.sh</code> geändert werden,
            Diese befindet sich im <code>/root/sinusbot/ChangePassword.sh</code> Verzeichnis.<br>
            <br>
            <h5>Minecraft-Server</h5>
            <hr>
            Der Minecraft-Server wird in je nach Version entweder in <code>/home/minecraft/1.16.3/</code> oder in <code>/home/minecraft/1.8.9/</code> installiert.<br>
            Gestartet und Gestoppt kann der Server über die <code>start.sh</code><br>
            Diese befinden sich im <code>/home/minecraft/1.16.3/</code> oder <code>/home/minecraft/1.8.9/</code> Verzeichnis.<br>
            <br>
            <h5>TS3AudioBot</h5>
            <hr>
            Der TS3AudioBot wird in <code>/home/ts3audiobot/</code> installiert.<br>
            <br>
            <h5>System-Tools</h5>
            <hr>
            Es werden folgende System Tools installiert:<br>
            <code>htop</code> <code>iftop</code> <code>nload</code> <code>screen</code> <code>curl</code> <code>nano</code> <code>git</code> <code>unzip</code> <code>software-properties-common</code> <code>net-tools</code> <code>sudo</code> <code>ethtool</code><br>
            <br>
            <h5>GTA FiveM-Server</h5>
            <hr>
            Der FiveM-Server wird in <code>/home/fivem/</code> installiert.<br>
            Gestartet und Gestoppt kann der Server über die <code>fivem.sh</code><br>
            Diese befinden sich im <code>/home/fivem/serverfiles/</code> Verzeichnis.<br>
            Beachte das du beim ersten Start per <code>fivem.sh</code> das TXAdmin Passwort kopieren musst,
            dazu gehst du nach dem ausführen der <code>fivem.sh</code> per <code>screen -r</code> in den screen des FiveM Servers,<br>
            dort sollte ein 4-Stelliger Code (<a target="_blank" href="https://i.imgur.com/E2cRPaJ.png">Bild</a>) angezeigt werden,<br>
            diesen kannst du anschließend im TXAdmin Interface eintragen (<a target="_blank" href="http://<?= $ip['ip']; ?>:40125">http://<?= $ip['ip']; ?>:40125</a>)<br>
            <br>
            <h5>Nextcloud</h5>
            <hr>
            Die Nextcloud wird in <code>/var/www/html/nextcloud/</code> installiert.<br>
            Die Datenbank Login Daten sind in folgender Datei zu finden: <code>/var/www/html/nextcloud/installation-data.txt</code><br>
            Das Webinterface der Nextcloud kann nun unter <a target="_blank" href="http://<?= $ip['ip']; ?>/nextcloud">http://<?= $ip['ip']; ?>/nextcloud</a> aufgerufen werden.<br>
            <br>
            <h5>Teamspeak</h5>
            <hr>
            Der Teamspeak-Server wird in <code>/home/teamspeak3/</code> installiert.<br>
            Gestartet und Gestoppt kann der Server per Service<br>
            Start: <code>service ts3server start</code><br>
            Stop: <code>service ts3server stop</code><br>
            Restart: <code>service ts3server restart</code><br>
            Die Query-Daten und der Token befinden sich in der <code>install.txt</code> (<code>/home/teamspeak3/install.txt</code>) <br>
            <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary text-uppercase font-weight-bolder" data-bs-dismiss="modal"><i class="fas fa-ban"></i> Schliessen</button>
            </div>
        </div>
    </div>
</div>

<script>
    function reinstall() {
        Swal.fire({
            title: 'Server neuinstallieren?',
            text: "Wenn du auf 'Ja' klickst werden alle Daten auf dem Server unwiederruflich gelöscht",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ja',
            cancelButtonText: 'Nein'
        }).then((result) => {
            if (result.value) {
                document.getElementById('reinstallServer').submit();
            }
        })
    }

    function resetPassword() {
        Swal.fire({
            title: 'Neues Rootpasswort setzen?',
            text: "Wenn du auf 'Ja' klickst wird dein Rootpasswort geändert",
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

    function installSoftware() {
        Swal.fire({
            title: 'Software wirklich installieren?',
            text: "Wenn du auf 'Ja' klickst wird dein Server für ca 1 Minute nicht verwaltbar sein",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ja',
            cancelButtonText: 'Nein'
        }).then((result) => {
            if (result.value) {
                document.getElementById('softwareInstall').submit();
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
                $('#root_password').html("<?= $root_password; ?>");
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

<?php if($vmsoftware->getOpenInstalls($serverInfos['id'])) { ?>
    <script>
        function blockpageload(){
            KTApp.block('#kt_blockui_card', {
                overlayColor: '#000000',
                state: 'primary',
                message: 'Bitte warten...'
            });

            setTimeout(function() {
                KTApp.unblock('#kt_blockui_card');
            }, 120000);
        }

        blockpageload();
    </script>
<?php } ?>