<?php

$currPage = 'front_Kundenkonto anlegen_auth';
$pageDescription = 'Erstelle dir noch heute einen Account bei uns und profitiere von unseren günstigen Preisen, den super Support und den Angeboten!';
include BASE_PATH . 'software/controller/PageController.php';

// include login auth file
include BASE_PATH . 'software/managing/auth/register.php';
?>

<link href="<?= $helper->styleUrl(); ?>css/pages/login/classic/login-3.css" rel="stylesheet" type="text/css" />

<meta name="description" content="<?= $pageDescription; ?>">

<div class="d-flex flex-column flex-root">
    <div class="login login-3 login-signin-on d-flex flex-row-fluid" id="kt_login">
        <div class="d-flex flex-center bgi-size-cover bgi-no-repeat flex-row-fluid" style="background-image: url(<?= $helper->imageUrl(); ?>bg/bg-1.jpg);">
            <div class="login-form text-center text-white p-7 position-relative overflow-hidden">

                <div class="d-flex flex-center mb-15">
                    <a href="#">
                        <img src="<?= $helper->imageUrl(); ?>logos/logo.png" class="max-h-140px" width="285" alt="" />
                    </a>
                </div>

                <div class="login-signin">
                    <div class="mb-20">
                        <h3>Kundenkonto anlegen</h3>
                        <p class="opacity-60">Lege dir hier ein Kundenkonto an.</p>
                    </div>
                    <form class="form text-center" method="post" id="kt_login_signup_form">
                        <div class="form-group">
                            <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="text" placeholder="Benutzername" name="username" />
                        </div>
                        <div class="form-group">
                            <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="text" placeholder="E-Mail Adresse" name="email" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="password" placeholder="Dein Wunschpasswort" name="password" />
                        </div>
                        <div class="form-group">
                            <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="password" placeholder="Wunschpasswort wiederholen" name="password_repeat" />
                        </div>
                        <div class="form-group text-left px-8">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-white text-white m-0">
                                    <input type="checkbox" name="agreement" />
                                    <span></span>Ich akzeptiere die <a href="<?= env('URL'); ?>legal/conditions/" class="text-white font-weight-bold ml-1">AGB</a> & <a href="<?= env('URL'); ?>legal/privacy/" class="text-white font-weight-bold ml-1">Datenschutzerklärung</a>.</label>
                            </div>
                            <div class="form-text text-muted text-center"></div>
                        </div>
                        <div class="form-group">
                            <button id="kt_login_signup_submit" name="register_submit" type="submit" class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3 m-2">Anlegen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= $helper->styleUrl(); ?>js/pages/custom/login/login-general.js"></script>