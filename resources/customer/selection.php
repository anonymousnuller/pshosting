<?php

$currPage = 'customer_Verwaltungsauswahl';
include BASE_PATH . 'software/controller/PageController.php';

if($user->getDataBySession($_COOKIE['session_token'], 'selection_option') == 1) {
    header('Location: ' . env('PANEL_URL') . 'customer/index/');
} elseif($user->getDataBySession($_COOKIE['session_token'], 'selection_option') == 2) {
    header('Location: ' . env('URL') . 'customer/index/');
}

?>


<div class=" main-bg" >
    <div class="container-fluid p-0">
        <div class="text-left iq-breadcrumb-one iq-bg-over black">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <nav aria-label="breadcrumb" class="text-center iq-breadcrumb-two">
                            <h2 class="title">
                                <?= $currPageName; ?>
                            </h2>
                            <ol class="breadcrumb main-bg">
                                <li class="breadcrumb-item">
                                    <a href="<?= $helper->url(); ?>home/">
                                        <i class="fa fa-home mr-2"></i>Startseite
                                    </a>
                                </li>

                                <li class="breadcrumb-item" style="color: #888787">
                                    Authentifizierung
                                </li>

                                <li class="breadcrumb-item" style="color: #888787">
                                    Einloggen
                                </li>

                                <li class="breadcrumb-item active">
                                    <?= $currPageName; ?>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="iq-counter-section iq-pb-7 text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="iq-title">
                    W??hle deine Verwaltungoberfl??che aus
                </h2>
                <p class="iq-title-desc">
                    Du hast die M??glichkeit, zwei Verwaltungsoberfl??chen auszuw??hlen. Alle Funktionen sind gleich, es unterscheidet sich nur das Design.
                </p>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <img src="<?= $helper->imageUrl(); ?>management/homepage.png" alt="Picture - HP">
                        <h4 class="iq-title" style="margin-bottom: 8px;">
                            Homepage
                        </h4>

                        <hr class="text-center">

                        <p style="margin-top: 10px;">
                            Ich m??chte die Verwaltung ??ber diese Seite t??tigen.
                        </p>

                        <a href="<?= $helper->url(); ?>customer/index/" class="btn btn-primary" style="margin-top: -18px;">
                            Zum Dashboard
                        </a>

                        <br><br>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="selection_option_2" id="selection_option_2">
                            <label class="custom-control-label" for="customSwitch1"> Als Standard speichern</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <img src="<?= $helper->imageUrl(); ?>management/customer_panel.png" alt="Picture - CP">
                        <h4 class="iq-title" style="margin-bottom: 8px;">
                            Kunden-Portal
                        </h4>

                        <hr class="text-center">

                        <p style="margin-top: 10px;">
                            Ich m??chte die Verwaltung ??ber das Kunden-Portal t??tigen.
                        </p>

                        <a href="<?= $helper->panelUrl(); ?>" class="btn btn-primary" style="margin-top: -18px;">
                            Zum Kunden-Portal
                        </a>

                        <br><br>

                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="selection_option_2" id="selection_option_2">
                            <label class="custom-control-label" for="customSwitch1"> Als Standard speichern</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>