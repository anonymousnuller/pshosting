<?php

$currPage = 'customer_Affiliate-Einstellungen';
include BASE_PATH . 'software/controller/PageController.php';

// include manage file
include BASE_PATH . 'software/managing/customer/settings/affiliate.php';

?>

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

                <?php if(!$affiliate->getActive($userid)) { ?>

                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title" style="margin-bottom: 0px;">
                                    Dein Affiliateprogramm - Empfehlungsmarketing
                                </h6>
                            </div>

                            <div class="card-body">
                                <p>Das sogenannte Affiliateprogramm dient als Empfehlungsmarketing, welches dir die Möglichkeit bietet - durch Werbung oder Empfehlung - eigenes Geld zu verdienen. Du erhältst von uns einen Link, mit dem Du deine Empfehlungen tätigen kannst. Sofern jemand auf diesen Link klickt, wird ein Cookie für ein Monat gesetzt. Alleine für den Klick auf diesem Link, bekommst Du einen kleinen festen Betrag auf deinem Programmkontostand. Auch bei Bestellungen oder Guthabenaufladungen erhältst Du eine feste Provision von bis zu 15% des jeweiligen Betrages.</p>

                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Welche Provisionsraten gibt es?</strong>
                                        <p>
                                            - Guthabenaufladungen: 12% vom Aufladebetrag<br>
                                            - Bestellungen: 15% vom Bestellpreis<br>
                                        </p>
                                    </div>

                                    <div class="col-md-6">
                                        <strong>213</strong>

                                        <p>
                                            123
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <br>
                                </div>

                                <form method="post">
                                    <div style="text-align: center;">
                                        <button type="submit" name="activeAffiliate" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-check"></i> Affiliate aktivieren
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                <?php } else { ?>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="margin-bottom: 0px;">
                                    <h6 class="card-title"></h6>
                                </div>

                                <div class="card-body">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header" style="margin-bottom: 0px;">
                                    <h6 class="card-title"></h6>
                                </div>

                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <br>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title" style="margin-bottom: 0px;">
                                Gutscheincodes
                            </h6>
                        </div>

                        <div class="card-body">
                            <?php if($affiliate->getDetails($userid, 'discount_codes') == 0) { ?>

                                <div class="alert alert-danger text-center" role="alert">
                                    <b>Aktivierung erforderlich!</b>
                                    <br>
                                    <br>
                                    Um diese Funktion nutzen zu können, musst Du unser Support-Team kontaktieren.<br>Bitte füge eine einfach Beschreibung bei, weshalb Du diese Funktion gerne nutzen möchtest. :)
                                </div>

                            <?php } else { ?>

                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>