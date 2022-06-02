<?php
$currPage = 'back_Spenden_hidehead';
include BASE_PATH.'app/controller/PageController.php';

$userid = $_GET['id'];

$SQL = $db->prepare("SELECT * FROM `users` WHERE `id` = :id");
$SQL->execute(array(':id' => $userid));
$userData = $SQL -> fetch(PDO::FETCH_ASSOC);

if($userData['cashbox'] == 'active'){
    if(!$user->sessionExists($_COOKIE['session_token'])){
        header('Location: '.env('URL'));
        die();
    }
}

if($SQL->rowCount() != 1){
    header('Location: '.env('URL'));
    die();
}

$cashbox->click($userid, $user->getIP());

include BASE_PATH.'app/manager/customer/payment/init.php';
include BASE_PATH.'app/manager/customer/payment/check_payments.php';
?>

<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bank-Überweisung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="font-size: 100%; align-content: center;">
                    Bitte überweise deinen gewählten Betrag an folgendes Bankkonto:

                    <br>

                <ul>
                    <li>Bank: N26 Online GmbH</li>
                    <li>Kontoinhaber: Schleyer-EDV / Björn Schleyer</li>
                    <li>IBAN: DE30 1001 1001 2625 5892 60</li>
                    <li>BIC/SWIFT: NTSBDEB1XXX</li>
                    <li>Verwendungszweck: <?= env('CUSTOMER_ID') ?>-<?= $user->getDataById($userid, 'id') ?>-<?= $user->bankCharge($tokenTwo); ?></li>
                </ul>

                <br>

                <strong>Die Gutschrift auf deinem Kundenkonto erfolgt nach spätestens drei Werktagen. Überweisungen am Wochenende, Feiertagen etc. werden erst nach Buchung berücksichtigt.</strong>
                </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><i class="fas fa-times"></i> <b>Schließen</b></button>
            </div>
        </div>
    </div>
</div>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="row">

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">

                            <?php if(!is_null($user->getDataById($userid,'projectlogo'))){ ?>
                                <img src="<?= $user->getDataById($userid,'projectlogo'); ?>" width="250">
                            <?php } ?>

                            <?php if(!is_null($user->getDataById($userid,'projectname'))){ ?>
                                <br>
                                <br>
                                <h1><?= $user->getDataById($userid,'projectname'); ?></h1>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="text-center">Guthaben spenden an <?= $userData['username']; ?></h3>

                            <br>

                            <form method="post">
                                <label for="amount">Betrag <small id="payment_fees"></small></label>
                                <input id="amount" class="form-control" value="1.00" name="amount" onkeyup="update();">

                                <br>

                                <label for="payment_method">Zahlungsmethode</label>
                                <select class="form-control" id="payment_method" name="payment_method" onchange="update();">
                                    <option>Bitte auswählen.</option>
                                    <option data-method="bank_transfer" value="bank_transfer" onchange="update();">Bank-Überweisung</option>
                                    <option data-method="paypal" value="paypalDonate">PayPal</option>
                                    <option data-method="paysafecard" value="paysafecard">PaySafe-Card <?php $psc_fees = $helper->getSetting('psc_fees'); if($psc_fees != 0){ echo '('.$psc_fees.'% Zahlungsgebühren)'; } ?></option>
                                    <option data-method="SOFORT" value="SOFORT">Sofort-Überweisung</option>
                                    <option data-method="GIROPAY" value="GIROPAY">GiroPay</option>
                                    <option data-method="CREDITCARD" value="CREDITCARD">Kreditkarte</option>
                                    <option data-method="APPLEPAY" value="APPLEPAY">Apple Pay</option>
                                </select>

                                <div id="psc_code"></div>

                                <br>
                                <button type="submit" name="startPayment" class="btn btn-outline-primary"><b>Guthaben spenden</b></button><br><br>
                                <center>
                                    <font size="2">
                                        <p>
                                        <b>Hinweis:</b> Es ist kein Abo. Der Betrag wird nur einmalig fällig,<br>
                                        es entstehen <u>keine</u> weiteren Kosten. Keine Kündigung notwendig!<br>
                                        Mit dieser Zahlung wird nur das Guthaben des Kundenkontos aufgeladen.<br>
                                        Guthaben kann <u>nicht</u> wieder ausgezahlt werden. (siehe <a target="_blank" href="https://cp.red-host.eu/agb">AGBs</a> §3.3)
                                        </p>
                                    </font>
                                </center>

                                <script>
                                    function update() {
                                        var payment_method = $('#payment_method').val();
                                        var amount = $('#amount').val();
                                        if(payment_method == 'paysafecard'){
                                            var new_amount = (amount / 100 * (100 - <?= $psc_fees; ?>)).toFixed(2);
                                            $('#payment_fees').html('(Der User erhält: '+new_amount+'€)');
                                        } else {
                                            $('#payment_fees').html('(Der User erhält: '+amount+'€)');
                                        }

                                        var payment_method = $('#payment_method').val();
                                        if(payment_method == 'bank_transfer'){

                                            $('#exampleModal').modal("show");

                                        }
                                    }
                                    update();
                                </script>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>