<?php
$currPage = 'team_Benutzerverwaltung';
include BASE_PATH.'software/controller/PageController.php';

$spin = base64_decode($helper->protect($_GET['id']).'=');

$SQL = $db->prepare("SELECT * FROM `customers` WHERE `support_pin` = :s_pin");
$SQL->execute(array(":s_pin" => $spin));
$userInfos = $SQL -> fetch(PDO::FETCH_ASSOC);


$id = $userInfos['id'];

if(isset($_POST['login'])){

    if($user->getDataById($id,'role') == 'admin' || $user->getDataById($id,'role') == 'support'){
        $error = 'Du kannst dich nicht in diesen Account einloggen';
    }

    if(empty($error)){
        setcookie('old_session_token', $_COOKIE['session_token'],time()+864000,'/', env('COOKIE_DOMAIN'));
        setcookie('session_token', $userInfos['session_token'],time()+864000,'/', env('COOKIE_DOMAIN'));
        die(header('Location: '.$helper->url() . 'index/'));
    } else {
        echo sendError($error);
    }

}

if(isset($_POST['changePassword'])){
    $error = null;

    if(empty($_POST['password'])){
        $error = 'Bitte gebe ein Passwort ein';
    }

    if($_POST['password'] != $_POST['password_repeat']){
        $error = 'Die Passwörter sind nicht gleich';
    }

    if(empty($error)){

        $cost = 10;
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => $cost]);

        $SQL = $db->prepare("UPDATE `customers` SET `password` = :password WHERE `id` = :id");
        $SQL->execute(array(":password" => $password, ":id" => $id));
        echo sendSuccess('Password wurde geändert');

    } else {
        echo sendError($error);
    }
}

if(isset($_POST['changeEmail'])){
    $error = null;

    if(empty($_POST['email'])){
        $error = 'Bitte gebe eine E-Mail ein';
    }

    if($userInfos['email'] == $_POST['email']){
        $error = 'Die E-Mail ist gleich';
    }

    if(empty($error)){

        $SQL = $db->prepare("UPDATE `customers` SET `email` = :email WHERE `id` = :id");
        $SQL->execute(array(":email" => $_POST['email'], ":id" => $id));
        echo sendSuccess('E-Mail wurde geändert');

    } else {
        echo sendError($error);
    }
}

if(isset($_POST['removeMoney'])){
    $error = null;

    if(!$user->isAdmin($_COOKIE['session_token'])){
        $error = 'Auf diese funktion haben nur Admins Zugriff';
    }

    if(empty($_POST['amount'])){
        $error = 'Bitte gebe einen Betrag an';
    }

    if(empty($_POST['desc'])){
        $desc = 'Kein Grund angegeben';
    } else {
        $desc = $_POST['desc'];
    }

    if(empty($error)){
        $user->removeMoney($_POST['amount'], $id);
        $user->addOrder($id, $_POST['amount'], $_POST['desc']);
        echo sendSuccess('Guthaben wurde abgezogen');
    } else {
        echo sendError($error);
    }
}

if(isset($_POST['addMoney'])){
    $error = null;

    if(!$user->isAdmin($_COOKIE['session_token'])){
        $error = 'Auf diese funktion haben nur Admins Zugriff';
    }

    if(empty($_POST['amount'])){
        $error = 'Bitte gebe einen Betrag an';
    }

    if(empty($_POST['desc'])){
        $desc = 'Kein Grund angegeben';
    } else {
        $desc = $_POST['desc'];
    }

    if(empty($error)){
        $user->addMoney($_POST['amount'], $id);
        $user->addOrder($id, $_POST['amount'], $_POST['desc']);
        echo sendSuccess('Guthaben wurde hinzugefügt');
    } else {
        echo sendError($error);
    }
}

if(isset($_POST['setRole'])){
    $error = null;

    if(empty($error)){
        $SQL = $db->prepare("UPDATE `customers` SET `role` = :role WHERE `id` = :id");
        $SQL->execute(array(":role" => $_POST['role'], ":id" => $id));
        echo sendSuccess('Rang wurde geändert');
    } else {
        echo sendError($error);
    }
}

if(isset($_POST['setState'])){
    $error = null;

    if(empty($error)){

        if($_POST['state'] == 'banned'){
            $SQL = $db->prepare("UPDATE `customers` SET `state` = :state WHERE `id` = :id");
            $SQL->execute(array(":state" => $_POST['state'], ":id" => $id));

            $user->generateSessionToken($user->getDataById($id,'email'));

            echo sendSuccess('Der Benutzer wurde gesperrt');
        } else {
            $SQL = $db->prepare("UPDATE `customers` SET `state` = :state WHERE `id` = :id");
            $SQL->execute(array(":state" => $_POST['state'], ":id" => $id));

            echo sendSuccess('Status wurde geändert');
        }
    } else {
        echo sendError($error);
    }
}

if(isset($_POST['saveReason'])){

    if($_POST['product_type'] == 'webspace'){
        if(empty($_POST['reason'])){
            $reason = null;
        } else {
            $reason = $_POST['reason'];
        }

        $SQL = $db->prepare("UPDATE `webspaces` SET `locked` = :locked WHERE `id` = :id");
        $SQL->execute(array(":locked" => $reason, ":id" => $_POST['product_id']));
    }

    if($_POST['product_type'] == 'vps'){
        if(empty($_POST['reason'])){
            $reason = null;

            try {
                $lxc->startServer($_POST['node_id'], $_POST['product_id']);
            }catch (Exception $e){
                //echo $e->getMessage();
            }
        } else {
            $reason = $_POST['reason'];

            try {
                $lxc->shutdown($_POST['node_id'], $_POST['product_id']);
            }catch (Exception $e){
                //echo $e->getMessage();
            }
        }

        $SQL = $db->prepare("UPDATE `kvm_servers` SET `locked` = :locked WHERE `id` = :id");
        $SQL->execute(array(":locked" => $reason, ":id" => $_POST['product_id']));
    }

    if($_POST['product_type'] == 'kvm'){
        if(empty($_POST['reason'])){
            $reason = null;

            try {
                $kvm->startServer($_POST['node_id'], $_POST['product_id']);
            }catch (Exception $e){
                //echo $e->getMessage();
            }
        } else {
            $reason = $_POST['reason'];

            try {
                $kvm->shutdown($_POST['node_id'], $_POST['product_id']);
            }catch (Exception $e){
                //echo $e->getMessage();
            }
        }

        $SQL = $db->prepare("UPDATE `kvm_servers` SET `locked` = :locked WHERE `id` = :id");
        $SQL->execute(array(":locked" => $reason, ":id" => $_POST['product_id']));
    }

    if($_POST['product_type'] == 'teamspeak'){
        if(empty($_POST['reason'])){
            $reason = null;

            try {
                $ts3->startServer($_POST['node_id'], $_POST['teamspeak_port'], $_POST['sid']);
            }catch (Exception $e){
                //echo $e->getMessage();
            }
        } else {
            $reason = $_POST['reason'];

            try {
                $ts3->stopServer($_POST['node_id'], $_POST['teamspeak_port'], $_POST['sid']);
            }catch (Exception $e){
                //echo $e->getMessage();
            }
        }

        $SQL = $db->prepare("UPDATE `teamspeak_servers` SET `locked` = :locked WHERE `id` = :id");
        $SQL->execute(array(":locked" => $reason, ":id" => $_POST['product_id']));
    }

    if($_POST['product_type'] == 'domains'){
        if(empty($_POST['reason'])){
            $reason = null;
        } else {
            $reason = $_POST['reason'];
        }

        $SQL = $db->prepare("UPDATE `domains` SET `locked` = :locked WHERE `id` = :id");
        $SQL->execute(array(":locked" => $reason, ":id" => $_POST['product_id']));
    }

    if($_POST['product_type'] == 'license'){
        if(empty($_POST['reason'])){
            $reason = null;
        } else {
            $reason = $_POST['reason'];
        }

        $SQL = $db->prepare("UPDATE `plesk_licenses` SET `locked` = :locked WHERE `id` = :id");
        $SQL->execute(array(":locked" => $reason, ":id" => $_POST['product_id']));
    }


    echo sendSuccess('Aktion wurde ausgeführt');
}

?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="container">

            <?php if($user->isAdmin($_COOKIE['session_token'])){ ?>
            <div class="row">

                <div class="col-md-12">
                    <br>
                    <div class="card">
                        <div class="card-header">Informationen</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-1">
                                <div class="symbol symbol-100 mr-5">
                                    <div class="symbol-label" style="background-image:url('https://api.cookiemc.de/200/<?= $userInfos['username'] ?>.png?ssl=1')"></div>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>
                                        <br>
                                        &nbsp;&nbsp;<i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= env('CUSTOMER_ID'); ?>-<?= $userInfos['id'] ?>" data-toggle="tooltip" data-placement="left" title="Kopieren"></i> Kundennummer: <?= env('CUSTOMER_ID'); ?>-<?= $userInfos['id'] ?>
                                        <br>
                                        &nbsp;&nbsp;<i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $userInfos['username'] ?>" data-toggle="tooltip" data-placement="left" title="Kopieren"></i> Nutzername: <?= $userInfos['username'] ?>
                                        <br>
                                        &nbsp;&nbsp;<i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $userInfos['email'] ?>" data-toggle="tooltip" data-placement="left" title="Kopieren"></i> E-Mail: <?= $userInfos['email']; ?>
                                        <br>
                                        &nbsp;&nbsp;<i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $userInfos['amount'] ?>" data-toggle="tooltip" data-placement="left" title="Kopieren"></i> Guthaben: <?= $userInfos['amount'] ?>€
                                        <br>
                                        &nbsp;&nbsp;<i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $userInfos['s_pin'] ?>" data-toggle="tooltip" data-placement="left" title="Kopieren"></i> Support-PIN: <?= $userInfos['s_pin'] ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                <br>
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <form method="post">
                                        <input class="form-control" name="amount" autocomplete="off" placeholder="Aktuelles Guthaben: <?= $userInfos['amount']; ?>€">
                                        <br>
                                        <input class="form-control" name="desc" autocomplete="off" placeholder="Kein Grund angegeben">
                                        <br>
                                        <button class="btn btn-outline-success" type="submit" name="addMoney"><b>Guthaben hinzufügen</b></button>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <form method="post">
                                        <input class="form-control" name="amount" autocomplete="off" placeholder="Aktuelles Guthaben: <?= $userInfos['amount']; ?>€">
                                        <br>
                                        <input class="form-control" name="desc" autocomplete="off" placeholder="Kein Grund angegeben">
                                        <br>
                                        <button class="btn btn-outline-danger" type="submit" name="removeMoney"><b>Guthaben abziehen</b></button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                <br>
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <form method="post">
                                        <select class="form-control" name="role">
                                            <option <?php if($user->getDataById($id,'role') == 'customer'){ echo 'selected'; } ?> value="customer">Kunde</option>
                                            <option <?php if($user->getDataById($id,'role') == 'support'){ echo 'selected'; } ?> value="support">Supporter</option>
                                            <option <?php if($user->getDataById($id,'role') == 'admin'){ echo 'selected'; } ?> value="admin">Admin</option>
                                        </select>
                                        <br>
                                        <button class="btn btn-outline-success" type="submit" name="setRole"><b>Rang setzen</b></button>
                                        <br><br><br><br>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <form method="post">
                                        <select class="form-control" name="state">
                                            <option <?php if($user->getDataById($id,'state') == 'pending'){ echo 'selected'; } ?> value="pending">Warte auf Freischaltung</option>
                                            <option <?php if($user->getDataById($id,'state') == 'active'){ echo 'selected'; } ?> value="active">Aktiv</option>
                                            <option <?php if($user->getDataById($id,'state') == 'banned'){ echo 'selected'; } ?> value="banned">Gesperrt</option>
                                        </select>
                                        <br>
                                        <button class="btn btn-outline-success" type="submit" name="setState"><b>Status setzen</b></button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <?php } ?>

            <br>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Passwort</label>
                                        <input name="password" autocomplete="off" placeholder="Passwort eingeben" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Passwort wiederholen</label>
                                        <input name="password_repeat" autocomplete="off" placeholder="Passwort eingeben" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <br>
                                        <button class="btn btn-outline-success" type="submit" name="changePassword"><b>Passwort ändern</b></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <form method="post">
                                <label>E-Mail</label>
                                <input name="email" autocomplete="off" value="<?= $userInfos['email']; ?>" class="form-control">
                                <br>
                                <button class="btn btn-outline-success" type="submit" name="changeEmail"><b>E-Mail ändern</b></button>
								&nbsp;
								<button type="submit" name="login" class="btn btn-outline-danger"><b>Als Kunde einloggen</b></button> 
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">

                            <form method="post">

                                <label for="transactions">Alle Transaktionen</label>
                                <br>
                                <a href="<?= env('URL'); ?>team/user/<?= $userInfos['id']; ?>/transactions">
                                    <button type="button" class="btn btn-outline-success">
                                        Transaktionen ansehen
                                    </button>
                                </a>

                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">

                            <form method="post">

                                <label for="invoices">Rechnungen</label>
                                <br>
                                <a href="<?= env('URL'); ?>team/user/<?= $userInfos['id']; ?>/invoices">
                                    <button type="button" class="btn btn-outline-success">
                                        Rechnungen ansehen
                                    </button>
                                </a>

                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">

                            <form method="post">
                                <label for="">
                                    <br>
                                </label>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">

                            <form method="post">
                                <label for="">
                                    <br>
                                </label>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <br>
                    <div class="card">
                        <div class="card-header">Webspace</div>
                        <div class="card-body">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            #
                                        </th>
                                        <th scope="col">
                                            domainName
                                        </th>
                                        <th scope="col">
                                            Laufzeit
                                        </th>
                                        <th scope="col">
                                            Preis
                                        </th>
                                        <th scope="col">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                <?php
                                $SQL = $db -> prepare("SELECT * FROM `webspaces` WHERE `user_id` = :id AND `deleted_at` IS NULL");
                                $SQL->execute(array(":id" => $id));
                                if ($SQL->rowCount() != 0) {
                                while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){?>
                                    <tr>
                                        <th scope="row"><?= $row['id']; ?></th>
                                        <td><?= $row['domainName']; ?></td>
                                        <td><?= $helper->formatDate($row['expire_at']); ?></td>
                                        <td><?= $row['price']; ?>€</td>
                                        <td><?= $row['state']; ?></td>
                                        <td>
                                            <?php if(is_null($row['locked'])){ ?>
                                                <button type="button" class="btn btn-outline-danger font-weight-bolder" data-toggle="modal" data-target="#webspace<?= $row['id']; ?>">
                                                    Produkt sperren
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-outline-success font-weight-bolder" data-toggle="modal" data-target="#webspace<?= $row['id']; ?>">
                                                    Produkt entsperren
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="webspace<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                        <form method="post">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Produkt sperren (Webspace #<?= $row['id']; ?>)</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <input hidden name="product_id" value="<?= $row['id']; ?>">
                                                        <input hidden name="product_type" value="webspace">
                                                        <textarea name="reason" class="form-control"><?= $row['locked']; ?></textarea>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-primary font-weight-bolder" data-dismiss="modal"><i class="fas fa-times"></i> Abbrechen</button>
                                                        <button type="submit" name="saveReason" class="btn btn-outline-success font-weight-bolder"><i class="fas fa-share-square"></i> Speichern</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php } } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <br>
                    <div class="card">
                        <div class="card-header">vServer</div>
                        <div class="card-body">

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">
                                        #
                                    </th>
                                    <th scope="col">
                                        Hostname
                                    </th>
                                    <th scope="col">
                                        Laufzeit
                                    </th>
                                    <th scope="col">
                                        Preis
                                    </th>
                                    <th scope="col">
                                        Status
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                <?php
                                $SQL = $db -> prepare("SELECT * FROM `kvm_servers` WHERE `user_id` = :id AND `deleted_at` IS NULL");
                                $SQL->execute(array(":id" => $id));
                                if ($SQL->rowCount() != 0) {
                                    while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){?>
                                        <tr>
                                            <th scope="row"><?= $row['id']; ?></th>
                                            <td><?= $row['hostname']; ?></td>
                                            <td><?= $helper->formatDate($row['expire_at']); ?></td>
                                            <td><?= $row['price']; ?>€</td>
                                            <td><?= $row['state']; ?></td>
                                            <td>
                                                <?php if(is_null($row['locked'])){ ?>
                                                    <button type="button" class="btn btn-outline-danger font-weight-bolder" data-toggle="modal" data-target="#vps<?= $row['id']; ?>">
                                                        Produkt sperren
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-outline-success font-weight-bolder" data-toggle="modal" data-target="#vps<?= $row['id']; ?>">
                                                        Produkt entsperren
                                                    </button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="vps<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <form method="post">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Produkt sperren (vServer #<?= $row['id']; ?>)</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <input hidden name="product_id" value="<?= $row['id']; ?>">
                                                            <input hidden name="product_type" value="vps">
                                                            <input hidden name="node_id" value="<?= $row['node_id']; ?>">
                                                            <textarea name="reason" class="form-control"><?= $row['locked']; ?></textarea>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-primary font-weight-bolder" data-dismiss="modal"><i class="fas fa-times"></i> Abbrechen</button>
                                                            <button type="submit" name="saveReason" class="btn btn-outline-success font-weight-bolder"><i class="fas fa-share-square"></i> Speichern</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php } } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <br>
                    <div class="card">
                        <div class="card-header">Teamspeak</div>
                        <div class="card-body">

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">
                                        #
                                    </th>
                                    <th scope="col">
                                        IP:Port
                                    </th>
                                    <th scope="col">
                                        Laufzeit
                                    </th>
                                    <th scope="col">
                                        Preis
                                    </th>
                                    <th scope="col">
                                        Status
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                <?php
                                $SQL = $db -> prepare("SELECT * FROM `teamspeak_servers` WHERE `user_id` = :id AND `deleted_at` IS NULL");
                                $SQL->execute(array(":id" => $id));
                                if ($SQL->rowCount() != 0) {
                                    while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){?>
                                        <tr>
                                            <th scope="row"><?= $row['id']; ?></th>
                                            <td><?= $row['teamspeak_ip']; ?>:<?= $row['teamspeak_port']; ?></td>
                                            <td><?= $helper->formatDate($row['expire_at']); ?></td>
                                            <td><?= $row['price']; ?>€</td>
                                            <td><?= $row['state']; ?></td>
                                            <td>
                                                <?php if(is_null($row['locked'])){ ?>
                                                    <button type="button" class="btn btn-outline-danger font-weight-bolder" data-toggle="modal" data-target="#teamspeak<?= $row['id']; ?>">
                                                        Produkt sperren
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-outline-success font-weight-bolder" data-toggle="modal" data-target="#teamspeak<?= $row['id']; ?>">
                                                        Produkt entsperren
                                                    </button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="teamspeak<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <form method="post">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Produkt sperren (Teamspeak #<?= $row['id']; ?>)</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <input hidden name="product_id" value="<?= $row['id']; ?>">
                                                            <input hidden name="product_type" value="teamspeak">
                                                            <input hidden name="node_id" value="<?= $row['node_id']; ?>">
                                                            <input hidden name="teamspeak_port" value="<?= $row['teamspeak_port']; ?>">
                                                            <input hidden name="sid" value="<?= $row['sid']; ?>">
                                                            <textarea name="reason" class="form-control"><?= $row['locked']; ?></textarea>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-primary font-weight-bolder" data-dismiss="modal"><i class="fas fa-times"></i> Abbrechen</button>
                                                            <button type="submit" name="saveReason" class="btn btn-outline-success font-weight-bolder"><i class="fas fa-share-square"></i> Speichern</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php } } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <br>
                    <div class="card">
                        <div class="card-header">Domains</div>
                        <div class="card-body">

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">
                                        #
                                    </th>
                                    <th scope="col">
                                        Domain-Name
                                    </th>
                                    <th scope="col">
                                        REG-Nummer
                                    </th>
                                    <th scope="col">
                                        Preis
                                    </th>
                                    <th scope="col">
                                        Status
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                <?php
                                $SQL = $db -> prepare("SELECT * FROM `domains` WHERE `user_id` = :id AND `deleted_at` IS NULL");
                                $SQL->execute(array(":id" => $id));
                                if ($SQL->rowCount() != 0) {
                                    while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){

                                        if($row['reg_number'] == '4') {
                                            $reg_number = 'SEDV-D-4';
                                        } elseif($row['reg_number'] == '3') {
                                            $reg_number = 'SEDV-D-3';
                                        } elseif($row['reg_number'] == '2') {
                                            $reg_number = 'SEDV-D-2';
                                        } elseif($row['reg_number'] == '1') {
                                            $reg_number = 'SEDV-D-1';
                                        } else {
                                            $reg_number = 'Fehler bei API-Abruf';
                                        }
                                        ?>
                                        <tr>
                                            <th scope="row"><?= $row['id']; ?></th>
                                            <td><?= $row['domainName']; ?></td>
                                            <td><?= $reg_number ?></td>
                                            <td><?= $row['price']; ?>€</td>
                                            <td><?= $row['state']; ?></td>
                                            <td>
                                                <?php if(is_null($row['locked'])){ ?>
                                                    <button type="button" class="btn btn-outline-danger font-weight-bolder" data-toggle="modal" data-target="#domain<?= $row['id']; ?>">
                                                        Produkt sperren
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-outline-success font-weight-bolder" data-toggle="modal" data-target="#domain<?= $row['id']; ?>">
                                                        Produkt entsperren
                                                    </button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="domain<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <form method="post">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Produkt sperren (Domain #<?= $row['id']; ?>)</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <input hidden name="product_id" value="<?= $row['id']; ?>">
                                                            <input hidden name="product_type" value="domains">
                                                            <textarea name="reason" class="form-control"><?= $row['locked']; ?></textarea>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-primary font-weight-bolder" data-dismiss="modal"><i class="fas fa-times"></i> Abbrechen</button>
                                                            <button type="submit" name="saveReason" class="btn btn-outline-success font-weight-bolder"><i class="fas fa-share-square"></i> Speichern</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php } } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <br>
                    <div class="card">
                        <div class="card-header">Plesk-Lizenzen</div>
                        <div class="card-body">

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">
                                        #
                                    </th>
                                    <th scope="col">
                                        Lizenz-ID
                                    </th>
                                    <th scope="col">
                                        Lizenz-Typ
                                    </th>
                                    <th scope="col">
                                        IP-Binding
                                    </th>
                                    <th scope="col">
                                        Preis
                                    </th>
                                    <th scope="col">
                                        Status
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                <?php
                                $SQL = $db -> prepare("SELECT * FROM `plesk_licenses` WHERE `user_id` = :id AND `deleted_at` IS NULL");
                                $SQL->execute(array(":id" => $id));
                                if ($SQL->rowCount() != 0) {
                                    while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){

                                        if($license_type == 'PLSK_12_ADMIN_VPS') {
                                            $license_type = 'Plesk Admin-Lizenz (VPS)';
                                        } elseif($license_type == 'PLSK_12_PRO_VPS') {
                                            $license_type = 'Plesk Pro-Lizenz (VPS)';
                                        } else {
                                            $license_type = 'Plesk Host-Lizenz (VPS)';
                                        }
                                        ?>
                                        <tr>
                                            <th scope="row"><?= $row['id']; ?></th>
                                            <td><?= $row['license_id']; ?></td>
                                            <td><?= $license_type; ?></td>
                                            <td><?= $row['binding_ip']; ?></td>
                                            <td><?= $row['price']; ?>€</td>
                                            <td><?= $row['state']; ?></td>
                                            <td>
                                                <?php if(is_null($row['locked'])){ ?>
                                                    <button type="button" class="btn btn-outline-danger font-weight-bolder" data-toggle="modal" data-target="#license<?= $row['id']; ?>">
                                                        Produkt sperren
                                                    </button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-outline-success font-weight-bolder" data-toggle="modal" data-target="#license<?= $row['id']; ?>">
                                                        Produkt entsperren
                                                    </button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="license<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                            <form method="post">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Produkt sperren (Plesk-Lizenz #<?= $row['id']; ?>)</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <input hidden name="product_id" value="<?= $row['id']; ?>">
                                                            <input hidden name="product_type" value="license">
                                                            <textarea name="reason" class="form-control"><?= $row['locked']; ?></textarea>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-primary font-weight-bolder" data-dismiss="modal"><i class="fas fa-times"></i> Abbrechen</button>
                                                            <button type="submit" name="saveReason" class="btn btn-outline-success font-weight-bolder"><i class="fas fa-share-square"></i> Speichern</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php } } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
</div>