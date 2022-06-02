<?php

$currPage = 'team_Newsverwaltung_admin';
include BASE_PATH . 'app/controller/PageController.php';

if(isset($_POST['createNews'])){
    $error = null;

    if(empty($_POST['title'])){
        $error = 'Bitte gebe einen Titel an';
    }
    if(empty($_POST['text'])){
        $error = 'Bitte gebe eine Nachricht an';
    }

    if($_POST['csrf_token'] != $_SESSION['csrf_token']){
        $error = 'Ungültige Anfrage bitte versuche es erneut!';
    }

    if(empty($error)){

        $DB_SQL = $db;
        $SQL = $DB_SQL->prepare("INSERT INTO `news`(`title`, `text`) VALUES (:title,:text)");
        $SQL->execute(array(":title" => $_POST['title'], ":text" => $_POST['text']));

        $_SESSION['success_msg'] = 'News wurde angelegt';
        header('Location: '.env('URL').'team/news');
        die();
    } else {
        echo sendError($error);
    }
}

?>

<form method="post">
    <input name="csrf_token" value="<?php $csrf_token = $site->generateCSRF(); echo $csrf_token; $_SESSION['csrf_token'] = $csrf_token; ?>" type="hidden">

    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Neues Ticket erstellen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Titel:</label>
                    <input class="form-control" name="title" required="required">

                    <br>

                    <label>Beschreibung:</label>
                    <textarea class="form-control" name="text" rows="5" required="required"></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><i class="fas fa-times"></i> <b>Schließen</b></button>
                    <button type="submit" class="btn btn-outline-success" name="createNews"><i class="fas fa-share-square"></i> <b>News erstellen</b></button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-2">
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"><?= env('APP_NAME'); ?></h5>
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4"><?= $currPageName; ?></span>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column-fluid">
        <div class="container">

            <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-outline-primary font-weight-bolder">
                <i class="fas fa-share-square"></i> Neue News erstellen
            </a>

            <br>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-body d-flex flex-column">
                            <table class="table" id="table1">
                                <thead>
                                <tr>
                                    <th scope="col">
                                        #
                                    </th>
                                    <th scope="col">
                                        Titel
                                    </th>
                                    <th scope="col">
                                        Erstellt am
                                    </th>
                                    <th cope="col">
                                        Läuft ab am
                                    </th>
                                    <th scope="col">

                                    </th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                <?php
                                $SQL = $db -> prepare("SELECT * FROM `news` ORDER BY `id` DESC");
                                $SQL->execute();
                                if ($SQL->rowCount() != 0) {
                                    while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){
                                        ?>
                                        <tr>
                                            <td scope="row"><?= $row['id']; ?></td>
                                            <td><?= $helper->xssFix($row['title']); ?></td>
                                            <td><?= $helper->formatDate($row['created_at']); ?></td>
                                            <td><?php if(is_null($helper->formatDate($row['deleted_at']))) { echo 'Kein Datum hinterlegt'; } else { echo $helper->formatDate($row['deleted_at']); } ?></td>
                                            <td><a href="<?= $helper->url(); ?>team/new/<?= $row['id']; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Anschauen</a></td>
                                        </tr>
                                    <?php } } else { echo '<tr><td scope="col">Keine News angelegt.</td></tr>'; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>