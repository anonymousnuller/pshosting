<?php
$currPage = 'team_Systemverwaltung_admin';
include BASE_PATH.'software/controller/PageController.php';

if(isset($_POST['updateLegal'])){

    $SQL = $db->prepare("UPDATE `users` SET `legal_accepted` = '0'");
    $SQL->execute();

    echo sendSuccess('Alle Benutzer müssen nun die neuen AGBs & Datenschutzerklärungen akzeptieren');
}

if(isset($_POST['activateLogin'])){
    $SQL = $db->prepare("UPDATE `settings` SET `login` = '1'");
    $SQL->execute();

    echo sendSuccess('Der Login wurde aktiviert');
}
if(isset($_POST['deactivateLogin'])){
    $SQL = $db->prepare("UPDATE `settings` SET `login` = '0'");
    $SQL->execute();

    echo sendSuccess('Der Login wurde deaktiviert');
}

if(isset($_POST['activateRegister'])){
    $SQL = $db->prepare("UPDATE `settings` SET `register` = '1'");
    $SQL->execute();

    echo sendSuccess('Der Register wurde aktiviert');
}
if(isset($_POST['deactivateRegister'])){
    $SQL = $db->prepare("UPDATE `settings` SET `register` = '0'");
    $SQL->execute();

    echo sendSuccess('Der Register wurde deaktiviert');
}

if(isset($_POST['activateWebspace'])){
    $SQL = $db->prepare("UPDATE `settings` SET `webspace` = '1'");
    $SQL->execute();

    echo sendSuccess('Die Webspace bestellung wurde aktiviert');
}
if(isset($_POST['deactivateWebspace'])){
    $SQL = $db->prepare("UPDATE `settings` SET `webspace` = '0'");
    $SQL->execute();

    echo sendSuccess('Die Webspace bestellung wurde deaktiviert');
}

if(isset($_POST['activateVPS'])){
    $SQL = $db->prepare("UPDATE `settings` SET `vps` = '1'");
    $SQL->execute();

    echo sendSuccess('Die VPS bestellung wurde aktiviert');
}
if(isset($_POST['deactivateVPS'])){
    $SQL = $db->prepare("UPDATE `settings` SET `vps` = '0'");
    $SQL->execute();

    echo sendSuccess('Die VPS bestellung wurde deaktiviert');
}

if(isset($_POST['activateTeamspeak'])){
    $SQL = $db->prepare("UPDATE `settings` SET `teamspeak` = '1'");
    $SQL->execute();

    echo sendSuccess('Die Teamspeak bestellung wurde aktiviert');
}
if(isset($_POST['deactivateTeamspeak'])){
    $SQL = $db->prepare("UPDATE `settings` SET `teamspeak` = '0'");
    $SQL->execute();

    echo sendSuccess('Die Teamspeak bestellung wurde deaktiviert');
}

if(isset($_POST['activateDedicated'])){
    $SQL = $db->prepare("UPDATE `settings` SET `dedicated` = '1'");
    $SQL->execute();

    echo sendSuccess('Die Dedicated-Bestellung wurde aktiviert');
}
if(isset($_POST['deactivateDedicated'])){
    $SQL = $db->prepare("UPDATE `settings` SET `dedicated` = '0'");
    $SQL->execute();

    echo sendSuccess('Die Dedicated-Bestellung wurde deaktiviert');
}

if(isset($_POST['activateDomains'])){
    $SQL = $db->prepare("UPDATE `settings` SET `domains` = '1'");
    $SQL->execute();

    echo sendSuccess('Die Domain-Bestellung wurde aktiviert');
}
if(isset($_POST['deactivateDomains'])){
    $SQL = $db->prepare("UPDATE `settings` SET `domains` = '0'");
    $SQL->execute();

    echo sendSuccess('Die Domain-Bestellung wurde deaktiviert');
}

if(isset($_POST['activatePlesk'])){
    $SQL = $db->prepare("UPDATE `settings` SET `plesk` = '1'");
    $SQL->execute();

    echo sendSuccess('Die Plesk-Lizenz-Bestellung wurde aktiviert');
}
if(isset($_POST['deactivatePlesk'])){
    $SQL = $db->prepare("UPDATE `settings` SET `plesk` = '0'");
    $SQL->execute();

    echo sendSuccess('Die Plesk-Lizenz-Bestellung wurde deaktiviert');
}

if(isset($_POST['activateGameserver'])){
    $SQL = $db->prepare("UPDATE `settings` SET `gameserver` = '1'");
    $SQL->execute();

    echo sendSuccess('Die Gameserver-Bestellung wurde aktiviert');
}
if(isset($_POST['deactivateGameserver'])){
    $SQL = $db->prepare("UPDATE `settings` SET `gameserver` = '0'");
    $SQL->execute();

    echo sendSuccess('Die Gameserver-Bestellung wurde deaktiviert');
}

if(isset($_POST['activateWhmcs'])){
    $SQL = $db->prepare("UPDATE `settings` SET `whmcs` = '1'");
    $SQL->execute();

    echo sendSuccess('Die WHMCS-Bestellung wurde aktiviert');
}
if(isset($_POST['deactivateWhmcs'])){
    $SQL = $db->prepare("UPDATE `settings` SET `whmcs` = '0'");
    $SQL->execute();

    echo sendSuccess('Die WHMCS-Bestellung wurde deaktiviert');
}

if(isset($_POST['setPaymentFees'])){
    $SQL = $db->prepare("UPDATE `settings` SET `psc_fees` = :psc_fees");
    $SQL->execute(array(":psc_fees" => $_POST['psc_fees']));

    echo sendSuccess('Die Zahlungsgebühren wurden gespeichert');
}

if(isset($_POST['setTrafficLimit'])){
    $SQL = $db->prepare("UPDATE `settings` SET `default_traffic_limit` = :default_traffic_limit");
    $SQL->execute(array(":default_traffic_limit" => $_POST['default_traffic_limit']));

    echo sendSuccess('Das Traffic Limit wurde gespeichert');
}

if(isset($_POST['activateCloud'])){
    $SQL = $db->prepare("UPDATE `settings` SET `rootserver` = :rootserver");
    $SQL->execute(array(":rootserver" => 'venocix'));

    echo sendSuccess('Die Venocix api wurde aktiviert');
}
if(isset($_POST['activateManual'])){
    $SQL = $db->prepare("UPDATE `settings` SET `rootserver` = :rootserver");
    $SQL->execute(array(":rootserver" => 'own'));

    echo sendSuccess('Die Manuelle Bestellung wurde aktiviert');
}

?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-center">
                                    Allgemeine Einstellungen
                                </h4>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="row">
                                    <div class="col-4">
                                        <form method="post">
                                            <label>Login-Einstellungen</label>
                                            <?php if($helper->getSetting('login') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateLogin"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateLogin"><b>deaktivieren</b></button>
                                            <?php } ?>

                                            <br>

                                            <label>Registrierungs-Einstellungen</label>
                                            <?php if($helper->getSetting('register') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateRegister"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateRegister"><b>deaktivieren</b></button>
                                            <?php } ?>
                                        </form>
                                    </div>

                                    <div class="col-4">
                                        <form method="post">
                                            <label>Standard Traffic Limit</label>
                                            <input class="form-control" required type="number" name="default_traffic_limit" value="<?= $helper->getSetting('default_traffic_limit'); ?>">

                                            <br>

                                            <button type="submit" class="btn btn-outline-warning btn-block btn-sm" name="setTrafficLimit"><b>Speichern</b></button>
                                        </form>

                                        <br>

                                        <form method="post">
                                            <label>PaySafe-Card Gebühren</label>
                                            <input class="form-control" required type="number" name="psc_fees" value="<?= $helper->getSetting('psc_fees'); ?>">

                                            <br>

                                            <button type="submit" class="btn btn-outline-warning btn-block btn-sm" name="setPaymentFees"><b>Speichern</b></button>
                                        </form>

                                    </div>

                                    <div class="col-4">

                                        <form method="post">
                                            <label>AGBs & Datenschutzerklärung zurücksetzen</label><br><br>
                                            <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="updateLegal"><b>Jetzt zurücksetzen</b></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title text-center">Produktverwaltung</h4>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="row">
                                    <div class="col-4">
                                        <p>Root-Server</p>
                                        <form method="post">
                                            <?php if($helper->getSetting('rootserver') == 'own'){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateCloud"><b>Venocix Cloud aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="activateManual"><b>Manuelle Bestellung aktivieren</b></button>
                                            <?php } ?>

                                            <br>

                                            <p>Webspace</p>
                                            <?php if($helper->getSetting('webspace') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateWebspace"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateWebspace"><b>deaktivieren</b></button>
                                            <?php } ?>

                                            <br>

                                            <p>Domains</p>
                                            <?php if($helper->getSetting('domains') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateDomains"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateDomains"><b>deaktivieren</b></button>
                                            <?php } ?>

                                        </form>
                                    </div>

                                    <div class="col-4">
                                        <form method="post">

                                            <p>LXC V-Server</p>
                                            <?php if($helper->getSetting('vps') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateVPS"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateVPS"><b>deaktivieren</b></button>
                                            <?php } ?>

                                            <br>

                                            <p>TeamSpeak-Server</p>
                                            <?php if($helper->getSetting('teamspeak') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateTeamspeak"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateTeamspeak"><b>deaktivieren</b></button>
                                            <?php } ?>

                                            <br>

                                            <p>Plesk-Lizenzen</p>
                                            <?php if($helper->getSetting('plesk') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activatePlesk"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivatePlesk"><b>deaktivieren</b></button>
                                            <?php } ?>

                                        </form>
                                    </div>

                                    <div class="col-4">
                                        <form method="post">

                                            <p>Dedicated Server</p>
                                            <?php if($helper->getSetting('dedicated') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateDedicated"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateDedicated"><b>deaktivieren</b></button>
                                            <?php } ?>

                                            <br>

                                            <p>WHMCS-Lizenzen</p>
                                            <?php if($helper->getSetting('whmcs') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateWhmcs"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateWhmcs"><b>deaktivieren</b></button>
                                            <?php } ?>

                                            <br>

                                            <p>Game-Server</p>
                                            <?php if($helper->getSetting('gameserver') == 0){ ?>
                                                <button type="submit" class="btn btn-outline-success btn-block btn-sm" name="activateGameserver"><b>aktivieren</b></button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-outline-danger btn-block btn-sm" name="deactivateGameserver"><b>deaktivieren</b></button>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>