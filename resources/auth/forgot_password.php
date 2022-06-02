<?php

$currPage = 'front_Passwort vergessen_auth';
$pageDescription = '';
include BASE_PATH . 'software/controller/PageController.php';

// include managing file
include BASE_PATH . 'software/managing/auth/forgot_password.php';

?>

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

                <?php if(isset($_GET['key']) && !empty($_GET['key'])) { $key = $_GET['key']; ?>

                    <div class="login-signin">
                        <div class="mb-20">
                            <h3>Passwort vergessen?</h3>
                            <p class="opacity-60">Setze dein Passwort zurück.</p>
                        </div>
                        <form class="form" method="post" id="kt_login_forgot_form">
                            <input name="key" hidden="hidden" value="<?= $_GET['key']; ?>">

                            <div class="form-group">
                                <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="password" placeholder="Dein neues Passwort" name="new_password" autocomplete="off">
                            </div>

                            <div class="form-group mb-10">
                                <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="password" placeholder="Neues Passwort wiederholen" name="new_password_repeat" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <div class="h-captcha" data-sitekey="<?= env('H_CAPTCHA_SITE_KEY'); ?>"></div>
                            </div>

                            <div class="form-group">
                                <button id="kt_login_forgot_submit" type="submit" name="new_password_submit" class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3 m-2">Zurücksetzen</button>
                            </div>
                        </form>
                    </div>

                <?php } else { ?>

                    <div class="login-signin">
                        <div class="mb-20">
                            <h3>Passwort vergessen?</h3>
                            <p class="opacity-60">Setze dein Passwort zurück.</p>
                        </div>
                        <form class="form" method="post" id="kt_login_forgot_form">
                            <div class="form-group mb-10">
                                <input class="form-control h-auto text-white placeholder-white opacity-70 bg-dark-o-70 rounded-pill border-0 py-4 px-8" type="text" placeholder="Benutzername / E-Mail Adresse" name="user_info" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <button id="kt_login_forgot_submit" type="submit" name="password_submit" class="btn btn-pill btn-outline-white font-weight-bold opacity-90 px-15 py-3 m-2">Anfragen</button>

                                <a href="<?= env('URL'); ?>auth/login/" class="btn btn-pill btn-outline-white font-weight-bold opacity-70 px-15 py-3 m-2">
                                   zurück
                                </a>
                            </div>
                        </form>
                    </div>

                <?php } ?>


            </div>
        </div>
    </div>
</div>

<script src="<?= $helper->styleUrl(); ?>js/pages/custom/login/login-general.js"></script>