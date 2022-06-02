<?php
$currPage = 'team_Support-PIN Login';
include BASE_PATH.'software/controller/PageController.php';

if(isset($_POST['login'])){
    $error = null;

    if(empty($_POST['spin'])){
        $error = 'Bitte gebe eine Support-PIN ein';
    }

    $validateSpin = $user->validateSpin($_POST['spin']);
    if($validateSpin == 0){
        $error = 'Die Support-PIN ist ungültig';
    }

    if($user->getDataById($validateSpin,'role') == 'admin' || $user->getDataById($validateSpin,'role') == 'support'){
        $error = 'Du kannst dich nicht in diesen Account einloggen';
    }

    if(empty($error)){

        $discord_webhook->callWebhook('Soeben hat sich '.$username.' mit eine Support-PIN in den Account von '.$user->getDataById($validateSpin,'username').' eingeloggt!');

        $uspin = $user->renewSupportPin($validateSpin);
        $uspin = str_replace('=','',base64_encode($uspin));

        header('Location: '.env('URL').'team/user/'.$uspin . '/');
    } else {
        echo sendError($error);
    }
}
?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="container">

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-header-title">
                                    Support-PIN Login (Benutzerlogin)
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post">

                            <label>Support-PIN</label>
                            <input class="form-control" name="spin">
                            <br>
                            <button type="submit" name="login" class="btn btn-primary btn-block">Einloggen</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>