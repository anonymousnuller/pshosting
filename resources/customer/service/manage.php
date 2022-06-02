<?php
$currPage = 'customer_Service verwalten';
include BASE_PATH . 'software/controller/PageController.php';

# include manage file
include BASE_PATH . 'software/managing/customer/service/manage.php';

?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#nav-main">
                            Übersicht
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#nav-description">
                            Beschreibung
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="mytabContent">
                    <div class="tab-pane fade show active" id="nav-main" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h1 class="card-title">Übersicht</h1>
                                    </div>
                                    <div class="card-body">

                                        <table class="table">
                                            <tbody>
                                            <tr>
                                                <td>Status</td>
                                                <td><?= $status_msg; ?></td>
                                            </tr>

                                            <tr>
                                                <td>Typ</td>
                                                <?php
                                                if($serverInfos['type'] == 'dedicated_server') {
                                                    $type = 'Dedizierter Server';
                                                } elseif($serverInfos['type'] == 'install') {
                                                    $type = 'Installationauftrag';
                                                } elseif($serverInfos['type'] == 'service') {
                                                    $type = 'Service';
                                                } else {
                                                    $type = 'Anderes / Sonstiges';
                                                }
                                                ?>
                                                <td>
                                                    <?= $type; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Preis</td>
                                                <td><?= number_format($serverInfos['price'],2); ?>€ / Monat</td>
                                            </tr>
                                            <tr>
                                                <td>Laufzeit</td>
                                                <td><span id="countdown">Lädt...</span></td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow mb-5">
                                    <div class="card-header">
                                        <h4 class="card-title">Verwaltung</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="<?= env('URL') . 'renew/service/' . $id . '/'; ?>" class="btn btn-warning btn-block">
                                                    <i class="fas fa-history"></i> Verlängern
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <br>
                        </div>

                        <!--div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Bewerten</h4>
                                    </div>

                                    <div class="card-body">
                                        <p>Du bist mit deinem Service zufrieden? Dann lass uns doch eine Bewertung da!</p>

                                        <button class="btn btn-outline-primary" style="text-align: center;">
                                            <a href="https://de.trustpilot.com/review/www.german-host.eu" target="_blank" rel="noopener">Zu Trustpilot</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div-->
                    </div>

                    <div class="tab-pane fade" id="nav-description" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h1 class="card-title">Beschreibung</h1>
                                    </div>
                                    <div class="card-body">
                                        <?= $helper->nl2br2($serverInfos['description']); ?>
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