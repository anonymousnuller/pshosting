<?php
$currPage = 'customer_Guthaben aufladen';
include BASE_PATH.'software/controller/PageController.php';
include BASE_PATH.'software/managing/customer/payment/init.php';

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
                    <li>Verwendungszweck: <?= env('CUSTOMER_ID') ?>-<?= $user->getDataById($userid, 'id') ?>-<?= $user->bankCharge($token); ?></li>
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


<script>
    /*$(document).ready(function(){ //Make script DOM ready
        $('#exampleModal').change(function() { //jQuery Change Function
            var opval = $(this).val(); //Get value from select element
            if(opval=="secondoption"){ //Compare it and if true
                $('#exampleModal').modal("show"); //Open Modal
            }
        });
    });*/
    $(document).ready(function(){
        $("#myBtn").click(function(){
            $("#exampleModal").modal();
        });
    });
</script>


<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <div class="row">

                    <div id="methodSelection">
                        <div class="col-md-12">
                            <?php
                            if(!is_null($helper->getSetting('payment_bonus'))) { ?>

                                <div class="alert bg-info d-flex flex-column flex-sm-row p-5 mb-10">
                                    <!--begin::Icon-->
                                    <span class="svg-icon svg-icon-2hx svg-icon-warning me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M5.78001 21.115L3.28001 21.949C3.10897 22.0059 2.92548 22.0141 2.75004 21.9727C2.57461 21.9312 2.41416 21.8418 2.28669 21.7144C2.15923 21.5869 2.06975 21.4264 2.0283 21.251C1.98685 21.0755 1.99507 20.892 2.05201 20.7209L2.886 18.2209L7.22801 13.879L10.128 16.774L5.78001 21.115Z" fill="black"/>
                                            <path d="M21.7 8.08899L15.911 2.30005C15.8161 2.2049 15.7033 2.12939 15.5792 2.07788C15.455 2.02637 15.3219 1.99988 15.1875 1.99988C15.0531 1.99988 14.92 2.02637 14.7958 2.07788C14.6717 2.12939 14.5589 2.2049 14.464 2.30005L13.74 3.02295C13.548 3.21498 13.4402 3.4754 13.4402 3.74695C13.4402 4.01849 13.548 4.27892 13.74 4.47095L14.464 5.19397L11.303 8.35498C10.1615 7.80702 8.87825 7.62639 7.62985 7.83789C6.38145 8.04939 5.2293 8.64265 4.332 9.53601C4.14026 9.72817 4.03256 9.98855 4.03256 10.26C4.03256 10.5315 4.14026 10.7918 4.332 10.984L13.016 19.667C13.208 19.859 13.4684 19.9668 13.74 19.9668C14.0115 19.9668 14.272 19.859 14.464 19.667C15.3575 18.77 15.9509 17.618 16.1624 16.3698C16.374 15.1215 16.1932 13.8383 15.645 12.697L18.806 9.53601L19.529 10.26C19.721 10.452 19.9814 10.5598 20.253 10.5598C20.5245 10.5598 20.785 10.452 20.977 10.26L21.7 9.53601C21.7952 9.44108 21.8706 9.32825 21.9221 9.2041C21.9737 9.07995 22.0002 8.94691 22.0002 8.8125C22.0002 8.67809 21.9737 8.54505 21.9221 8.4209C21.8706 8.29675 21.7952 8.18392 21.7 8.08899Z" fill="black"/>
                                        </svg>
                                    </span>
                                    <!--end::Icon-->

                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Title-->
                                        <h4 class="mb-1 <?php if(!$darkmode) { echo 'text-dark'; } else { echo 'text-white'; } ?>">Aufladebonus verfügbar!</h4>
                                        <!--end::Title-->
                                        <!--begin::Content-->
                                        <span>Sichere dir bis zu <?= $helper->getSetting('payment_bonus'); ?>% zusätzliches Guthaben zu deiner nächsten Aufladung.</span>
                                        <?php if(!is_null($helper->getSetting('payment_bonus_end'))) { ?>

                                            <br>
                                            <span>
                                                <strong>
                                                    <em>
                                                        Nur noch bis zum <?= $helper->formatDateNormal($helper->getSetting('payment_bonus_end')); ?> Uhr möglich. Also ab geht's. ;)
                                                    </em>
                                                </strong>
                                            </span>
                                        <?php } ?>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                            <?php } ?>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title text-center">
                                        Guthaben aufladen
                                    </h4>
                                </div>

                                <div class="card-body">
                                    <form method="post">
                                        <div id="amountSelection">
                                            <div class="row text-center justify-content-center align-items-center">
                                                <div class="col-md-12">
                                                    <h4>Wähle deinen gewünschten Betrag aus</h4>

                                                    <div class="btn-group amount" style="margin-bottom: 10px;">
                                                        <button type="button" class="btn btn-primary active" onload="selectAmount('1.00', $(this))" onclick="selectAmount('1.00', $(this))">1.00€</button>
                                                        <button type="button" class="btn btn-primary" onclick="selectAmount('5.00', $(this))">5.00€</button>
                                                        <button type="button" class="btn btn-primary" onclick="selectAmount('10.00', $(this))">10.00€</button>
                                                        <button type="button" class="btn btn-primary" onclick="selectAmount('20.00', $(this))">20.00€</button>
                                                        <button type="button" class="btn btn-primary" onclick="selectAmount('own', $(this))">Eigener Betrag</button>
                                                    </div>
                                                </div>

                                                <div id="own" class="col-md-12" style="display: none;">
                                                    <div class="container">

                                                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css" integrity="sha256-nv5vSBJAzPy+07+FvRvhV2UPpH87H/UnWMrA6nbEg7U=" crossorigin="anonymous" />
                                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js" integrity="sha256-eXdxIh/sjKTNi5WyC8cKHekwPywORiomyiMFyZsowWw=" crossorigin="anonymous"></script>

                                                        <style>
                                                            .slidecontainer {
                                                                width: 100%;
                                                            }

                                                            .js-range-slider {
                                                                color: #fff;
                                                            }
                                                        </style>

                                                        <div class="col-12">
                                                            <div class="slidecontainer">
                                                                <label for="own_input"> Betrag (<span id="amount_count"></span> ausgewählt)</label>
                                                                <input type="number" class="js-range-slider form-control" id="own_input" name="own_input" value="20" min="1" step="0.50" max="200" onkeyup="amountVal();" onchange="amountVal()">
                                                            </div>
                                                        </div>

                                                        <br><br>

                                                        <div class="col-12">

                                                            <script>
                                                                $(".js-range-slider").ionRangeSlider({
                                                                    type: "single",
                                                                    min: 1,
                                                                    max: 200,
                                                                    step: 0.50,
                                                                    grid: true,
                                                                    skin: "round"
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <br>
                                        </div>

                                        <div id="methodSelection">
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <h4>Wähle deine gewünschte Zahlungsmethode aus</h4>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="radio" class="btn-check method" name="payment_method" value="bank_transfer" onclick="selectMethod('bank_transfer')" id="kt_radio_buttons_2_option_1"/>
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_1">
                                                        <span class="svg-icon svg-icon-3x me-3 <?php if($darkmode) { echo 'svg-icon-white'; } else { echo ''; } ?>">
                                                            <i class="pf pf-bank-transfer"></i>
                                                        </span>

                                                        <span class="d-block fw-bold text-start">
                                                            <span class="text-dark fw-bolder d-block fs-3">
                                                                Überweisung
                                                            </span>

                                                            <span class="text-muted fw-bold fs-6">
                                                                Provider: manuell
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="radio" class="btn-check method" name="payment_method" value="paypal" onclick="selectMethod('paypal')" id="kt_radio_buttons_2_option_2"/>
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_2">
                                                        <span class="svg-icon svg-icon-3x me-3 <?php if($darkmode) { echo 'svg-icon-white'; } else { echo ''; } ?>">
                                                            <i class="pf pf-paypal"></i>
                                                        </span>

                                                        <span class="d-block fw-bold text-start">
                                                            <span class="text-dark fw-bolder d-block fs-3">
                                                                PayPal
                                                            </span>

                                                            <span class="text-muted fw-bold fs-6">
                                                                Provider: Direkt
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="radio" class="btn-check" name="payment_method" value="paysafecard" onclick="selectMethod('paysafecard')" id="kt_radio_buttons_2_option_3"/>
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_3">
                                                        <span class="svg-icon svg-icon-3x me-3 <?php if($darkmode) { echo 'svg-icon-white'; } else { echo ''; } ?>">
                                                            <i class="pf pf-paysafecard"></i>
                                                        </span>

                                                        <span class="d-block fw-bold text-start">
                                                            <span class="text-dark fw-bolder d-block fs-3">
                                                                PaySafeCard
                                                            </span>

                                                            <span class="text-muted fw-bold fs-6">
                                                                Provider: Direkt
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="radio" class="btn-check" name="payment_method" value="SOFORT" onclick="selectMethod('SOFORT')" id="kt_radio_buttons_2_option_4"/>
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_4">
                                                        <span class="svg-icon svg-icon-3x me-3 <?php if($darkmode) { echo 'svg-icon-white'; } else { echo ''; } ?>">
                                                            <i class="pf pf-sofort"></i>
                                                        </span>

                                                        <span class="d-block fw-bold text-start">
                                                            <span class="text-dark fw-bolder d-block fs-3">
                                                                Sofort
                                                            </span>

                                                            <span class="text-muted fw-bold fs-6">
                                                                Provider: Mollie
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="radio" class="btn-check method" name="payment_method" value="CREDITCARD" onclick="selectMethod('CREDITCARD')" id="kt_radio_buttons_2_option_5"/>
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_5">
                                                        <span class="svg-icon svg-icon-3x me-3 <?php if($darkmode) { echo 'svg-icon-white'; } else { echo ''; } ?>">
                                                            <i class="pf pf-credit-card"></i>
                                                        </span>

                                                        <span class="d-block fw-bold text-start">
                                                            <span class="text-dark fw-bolder d-block fs-3">
                                                                Kreditkarte
                                                            </span>

                                                            <span class="text-muted fw-bold fs-6">
                                                                Provider: Mollie
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <div class="col-md-3 btn-check-method selected">
                                                    <input type="radio" class="btn-check" name="payment_method" value="APPLEPAY" onclick="selectMethod('APPLEPAY')" id="kt_radio_buttons_2_option_6"/>
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_6">
                                                        <span class="svg-icon svg-icon-3x me-3 <?php if($darkmode) { echo 'svg-icon-white'; } else { echo ''; } ?>">
                                                            <i class="pf pf-apple-pay"></i>
                                                        </span>

                                                        <span class="d-block fw-bold text-start">
                                                            <span class="text-dark fw-bolder d-block fs-3">
                                                                Apple-Pay
                                                            </span>

                                                            <span class="text-muted fw-bold fs-6">
                                                                Provider: Mollie
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <div class="col-md-3">
                                                    <input type="radio" class="btn-check" name="payment_method" value="GIROPAY" onclick="selectMethod('GIROPAY')" id="kt_radio_buttons_2_option_7"/>
                                                    <label class="btn btn-outline btn-outline-dashed btn-outline-default p-7 d-flex align-items-center mb-5" for="kt_radio_buttons_2_option_7">
                                                        <span class="svg-icon svg-icon-3x me-3 <?php if($darkmode) { echo 'svg-icon-white'; } else { echo ''; } ?>">
                                                            <i class="pf pf-giropay"></i>
                                                        </span>

                                                        <span class="d-block fw-bold text-start">
                                                            <span class="text-dark fw-bolder d-block fs-3">
                                                                GiroPay
                                                            </span>

                                                            <span class="text-muted fw-bold fs-6">
                                                                Provider: Mollie
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>

                                                <input type="hidden" id="payment_method" name="payment_method" value="">
                                                <input type="hidden" id="amount" name="amount" value="1.00">

                                                <button type="submit" name="startPayment" class="btn btn-outline-primary font-weight-bolder">
                                                    <i class="fas fa-wallet"></i> Aufladung starten *
                                                </button>
                                              

                                                <div style="margin-top: 15px;">
                                                    <small>
                                                        * - Nachdem Klick auf "Aufladung starten" wirst Du dem von dir ausgewählten Zahlungsprovider weitergeleitet. Sobald Du deine Zahlung getätigt hast, wirst Du auf einer von unseren Bestätigungsseiten weitergeleitet, binnen Sekunden und maximal fünf Minuten wird dein Guthaben auf dein Kundenkonto gutgeschrieben.
                                                    </small>

                                                    <br><br>
                                                    <font size="2">
                                                        <p>
                                                            <b>Hinweis:</b> Es ist kein Abo. Der Betrag wird nur einmalig fällig,
                                                            es entstehen <u>keine</u> weiteren Kosten. Keine Kündigung notwendig!
                                                            Mit dieser Zahlung wird nur das Guthaben des Kundenkontos aufgeladen.
                                                            Guthaben kann <u>nicht</u> wieder ausgezahlt werden. (siehe <a target="_blank" href="<?= env('LEGAL_URL'); ?>conditions/">AGBs</a> §3.3)
                                                        </p>
                                                    </font>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <script type="text/javascript">
                                        function selectMethod(gateway) {

                                            var methodSelection = $('#methodSelection');
                                            switch(gateway) {
                                                case 'bank_transfer':
                                                    $('#payment_method').val('bank_transfer');
                                                    break;
                                                case 'paypal':
                                                    $('#payment_method').val('paypal');
                                                    break;
                                                case 'paysafecard':
                                                    $('#payment_method').val('paysafecard');
                                                    break;
                                                case 'SOFORT':
                                                    $('#payment_method').val('SOFORT');
                                                    break;
                                                case 'CREDITCARD':
                                                    $('#payment_method').val('CREDITCARD');
                                                    break;
                                                case "APPLEPAY":
                                                    $('#payment_method').val('APPLEPAY');
                                                    break;
                                                case "GIROPAY":
                                                    $('#payment_method').val('GIROPAY');
                                                    break;
                                            }

                                            setAmount();
                                        }
										
										$('#own_input').on('input', function() {update();});

                                        function selectAmount(amount, selected) {
                                            $('.amount button').removeClass('active');
                                            selected.addClass('active');

                                            if(amount == 'own') {
                                                $('#own').css('display', 'table');
                                            } else {
                                                $('#own').css('display', 'none');
                                                $('#own_input').val(amount);
                                            }

                                            amountVal();
                                        }

                                        function setAmount() {
                                            $('#amount').val();
                                        }

                                        function amountVal() {
                                            $('#amount').val($('#own_input').val());
                                        }
										
										function update() {
                                            var amount_count = $("#own_input").val();
                                            var end_amount = Number(parseFloat(amount_count)).toFixed(2);
                                            $('#amount_count').html(end_amount + "€");
                                        }
										
										$(document).ready(function(){
                                            update();
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <br>
                </div>

                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center">
                                Meine Aufladungen
                            </h4>
                        </div>
                        <div class="card-body pt-0">
                            <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <div class="table-responsive">
                                    <table class="table table-striped table-row-bordered gy-5 gs-7" id="kt_datatable_example_1">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Beschreibung</th>
                                            <th>Betrag</th>
                                            <th>Typ</th>
                                            <th>Status</th>
                                            <th>Datum</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center">
                                        <?php
                                        $SQL = $db->prepare("SELECT * FROM `customers_charge_transactions` WHERE `user_id` = :user_id  ORDER BY `id` DESC");
                                        $SQL->execute(array(":user_id" => $userid));
                                        if ($SQL->rowCount() != 0) {
                                            while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){

                                                if($row['state'] == 'pending') {
                                                    $status = '<span class="badge badge-pill badge-danger">Offen</span>';
                                                } elseif($row['state'] == 'success') {
                                                    $status = '<span class="badge badge-pill badge-success">Erfolgreich</span>';
                                                } elseif($row['state'] == 'expired') {
                                                    $status = '<span class="badge badge-pill badge-secondary">Verfallen</span>';
                                                } elseif($row['state'] == 'canceled') {
                                                    $status = '<span class="badge badge-pill badge-secondary">Abgebrochen</span>';
                                                } elseif($row['state'] == 'failed') {
                                                    $status = '<span class="badge badge-pill badge-warning">Fehlgeschlagen</span>';
                                                } elseif($row['state'] == 'abort') {
                                                    $status = '<span class="badge badge-pill badge-info">Abgebrochen</span>';
                                                }
                                                ?>

                                                    <tr>
                                                        <td><?= $row['id']; ?></td>
                                                        <td><?= $row['desc']; ?></td>
                                                        <td><?= $row['amount']; ?>€</td>
                                                        <td><?= $row['gateway']; ?></td>
                                                        <td><?= $status; ?></td>
                                                        <td><?= $helper->formatDateNormal($row['created_at']); ?> Uhr</td>
                                                    </tr>

                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <script type="text/javascript">
                                $("#kt_datatable_example_1").DataTable({
                                    "paging": true,
                                    "order": [[ 0, "desc" ]],
                                    "language": {
                                        "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/German.json"
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>