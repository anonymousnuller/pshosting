<?php
$currPage = 'front_KVM Root-Server bestellen';
include BASE_PATH.'app/controller/PageController.php';
?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <?php if($user->sessionExists($_COOKIE['session_token'])){ ?>
        <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"><?= env('APP_NAME'); ?></h5>
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                    <span class="text-muted font-weight-bold mr-4"><?= $currPageName; ?></span>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-4">

                    <!--<div class="row" style="text-align: center;">
                        <div class="col-12">
                            <div class="alert alert-primary" role="alert">
                                <h2>Rabatt verfügbar</h2> <hr>
                                <p>Bis zu -30% Rabatt sichern.</p>
                            </div>
                        </div>
                    </div>-->

                    <div class="card shadow mb-5">
                        <div class="card-body" style="text-align: center;">
                            <h3 class="mb-0">
                            <span class="svg-icon svg-icon-primary svg-icon-8x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect opacity="0.200000003" x="0" y="0" width="24" height="24"/>
                                        <path d="M4.5,7 L9.5,7 C10.3284271,7 11,7.67157288 11,8.5 C11,9.32842712 10.3284271,10 9.5,10 L4.5,10 C3.67157288,10 3,9.32842712 3,8.5 C3,7.67157288 3.67157288,7 4.5,7 Z M13.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L13.5,18 C12.6715729,18 12,17.3284271 12,16.5 C12,15.6715729 12.6715729,15 13.5,15 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M17,11 C15.3431458,11 14,9.65685425 14,8 C14,6.34314575 15.3431458,5 17,5 C18.6568542,5 20,6.34314575 20,8 C20,9.65685425 18.6568542,11 17,11 Z M6,19 C4.34314575,19 3,17.6568542 3,16 C3,14.3431458 4.34314575,13 6,13 C7.65685425,13 9,14.3431458 9,16 C9,17.6568542 7.65685425,19 6,19 Z" fill="#000000"/>
                                    </g>
                                </svg>
                            </span>

                                <br><br>
                                KVM-Konfigurator
                                <br>
                                <hr>
                            </h3>
                            <span style="font-size: 110%;">
                                Hier findest du den KVM Root-Server Konfigurator
                                <br>
                                aus dem Hause Skylink.

                                <br>
                                <br>

                                <a href="<?= $helper->url(); ?>order/rootserver/configurator" class="btn btn-block btn-outline-primary mb-4">
                                    <i class="fas fa-share-square"></i> <b>Zu dem Konfigurator</b>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!--<div class="row" style="text-align: center;">
                        <div class="col-12">
                            <div class="alert alert-primary" role="alert">
                                <h2>Rabatt verfügbar</h2> <hr>
                                <p>Bis zu -30% Rabatt sichern.</p>
                            </div>
                        </div>
                    </div>-->
                    <div class="card shadow mb-5">
                        <div class="card-body" style="text-align: center;">
                            <h3 class="mb-0">
                            <span class="svg-icon svg-icon-primary svg-icon-8x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M20.4061385,6.73606154 C20.7672665,6.89656288 21,7.25468437 21,7.64987309 L21,16.4115967 C21,16.7747638 20.8031081,17.1093844 20.4856429,17.2857539 L12.4856429,21.7301984 C12.1836204,21.8979887 11.8163796,21.8979887 11.5143571,21.7301984 L3.51435707,17.2857539 C3.19689188,17.1093844 3,16.7747638 3,16.4115967 L3,7.64987309 C3,7.25468437 3.23273352,6.89656288 3.59386153,6.73606154 L11.5938615,3.18050598 C11.8524269,3.06558805 12.1475731,3.06558805 12.4061385,3.18050598 L20.4061385,6.73606154 Z" fill="#000000" opacity="0.3"/>
                                        <polygon fill="#000000" points="14.9671522 4.22441676 7.5999999 8.31727912 7.5999999 12.9056825 9.5999999 13.9056825 9.5999999 9.49408582 17.25507 5.24126912"/>
                                    </g>
                                </svg>
                            </span>

                                <br><br>
                                KVM Pakete
                                <br>
                                <hr>
                            </h3>
                            <span style="font-size: 110%;">
                                Hier findest du die KVM Root-Server Pakete
                                <br>
                                aus dem Hause Skylink.

                                <br>
                                <br>

                                <a href="<?= $helper->url(); ?>order/rootserver/packets" class="btn btn-block btn-outline-primary mb-4">
                                    <i class="fas fa-share-square"></i> <b>Zu den Paketen</b>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>