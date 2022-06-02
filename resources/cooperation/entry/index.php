<?php

$currPage = 'partner_Projekteintrag anschauen';
include BASE_PATH . 'software/controller/PageController.php';

?>

<style>
    .btn-website {
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }

    .btn-instagram {
        color: #fbad50;
        background-color: #fff;
        border-color: #ccc;
    }

    .btn-twitter {
        color: #1dcaff;
        background-color: #fff;
        border-color: #ccc;
    }
    .btn-discord {
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }

    .btn-youtube {
        color: #FF0000;
        background-color: #fff;
        border-color: #ccc;
    }

    .btn-twitch {
        color: #6441a5;
        background-color: #fff;
        border-color: #ccc;
    }

    .btn-facebook {
        color: #3b5998;
        background-color: #fff;
        border-color: #ccc;
    }

    .btn-tiktok {
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }
</style>

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
            <div class="row">

                <?php if(!$user->checkPartnerEntry($userid)) { ?>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title" style="margin-bottom: 0px;">
                                    Projekteintrag vornehmen
                                </h3>
                            </div>

                            <div class="card-body">
                                Leider hast Du noch keinen Projekteintrag für deine Partnerschaft festgelegt. Bitte lege zunächst ein Projekteintrag vor, bevor Du weitere Einstellungen, Konfigurationen oder anderes, vornehmen kannst.
                                <br><br>
                                Beim Projekteintrag kannst Du wesentlich relevante Informationen angeben, die dein Projekt betreffen. Bspw. kann der Name, eine simple Beschreibung oder sämtliche Social-Media Links angegeben werden. Dieser Eintrag wird automatisch auf unserer Homepage übernommen.
                                <br>
                                <strong>Zusätzlich</strong> bieten wir dir die Möglichkeit eigene Rabattcodes bis zu einem vordefinierten Prozentsatz anzulegen. Natürlich kannst Du auch hier eine Darstellungsweise festlegen, bspw. kann der Rabattcode bei deinem Eintrag angezeigt werden oder eben nicht.

                                <br><br>
                                Wir möchten unser Partnersystem so aktuell und optimiert, wie möglich gestalten - auch Verbesserungsvorschläge oder Integrationswünsche sind sehr gerne gesehen.

                                <br><br>

                                <div style="text-align: center;">
                                    <a href="<?= env('URL'); ?>cooperation/entry/create/" class="btn btn-outline-success btn-sm">
                                        Jetzt Eintrag vornehmen
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                <?php } else { ?>

                    <div class="col-lg-12">

                        <script type="text/javascript">
                            function loadInformations() {
                                $.getJSON('<?= env('URL'); ?>api/v1/cooperations/entry/<?= $userid; ?>', function(response) {
                                    document.getElementById('cooperation-img').src = response.informations.art;

                                    $('#title').html(response.informations.partner.title);
                                    $('#description').html(response.informations.partner.description);


                                });

                                setInterval(function () {
                                    loadInformations();
                                }, 5000);
                            }
                        </script>
                        <div class="card" style="border-radius: 15px 10px;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <img src="<?= env('HOME_URL'); ?>assets/images/partner/school-office.png"  style="border-radius: 5px;" class="img-fluid" alt="School-Office Partner-Logo">
                                    </div>
                                    <div class="col-8">
                                        <h4 class="iq-title" style="margin-bottom: 10px;">
                                            School-Office
                                        </h4>

                                        <p class="iq-title-desc" style="margin-bottom: 15px;">
                                            <i>
                                                ... ist ein Projekt, welches sich um die Digitialisierung alle deutschen Schulen spezialisiert hat.
                                                Nebenbei werden noch optionale Dienste, wie Cloud-Pakete, Webspace und einige weitere Sachen kostengünstig oder auch kostenlos zur Verfügung gestellt.
                                            </i>
                                        </p>

                                        <a href="https://school-office.eu" class="btn btn-website" target="_blank">
                                            <i class="fas fa-globe"></i> Webseite
                                        </a>

                                        <a href="https://twitter.com/SchoolOfficeTM" class="btn btn-twitter" target="_blank">
                                            <i class="fab fa-instagram"></i> Twitter
                                        </a>

                                        <a href="https://discord.gg/nD3EmJtaz3" class="btn btn-discord" target="_blank">
                                            <i class="fab fa-discord"></i> Discord
                                        </a>
                                    </div>

                                    <div class="col-2">
                                        <h5 class="iq-title">
                                            Rabattcodes:
                                        </h5>

                                        <ul>
                                            <li>
                                                school2GO (30%)
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</div>