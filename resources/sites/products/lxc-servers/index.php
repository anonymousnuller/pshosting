<?php

$currPage = 'customer_LXC V-Server bestellen';
include BASE_PATH . 'software/controller/PageController.php';

include BASE_PATH . 'software/managing/customer/vserver/order.php';

?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div class="container">


                <?php if(isset($_POST['step2_conf'])) {?>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">
                                    <h1 class="card-title">Prepaid LXC V-Server</h1>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <i class="fas fa-microchip"></i> bis zu 16 Kerne<br>
                                            <i class="fas fa-memory"></i> bis zu 64 GB DDR4 reg. ECC RAM<br>
                                            <i class="fas fa-hdd"></i> bis zu 250 GB SSD Speicher<br>
                                            <i class="fas fa-map-marked-alt"></i> Skylink Data Center NL
                                        </div>

                                        <div class="col-6">
                                            <i class="fas fa-server"></i> 2x 10 GBit/s (Shared) Uplink<br>
                                            <i class="fas fa-tachometer-alt"></i> Fair Use Traffic<br>
                                            <i class="fas fa-shield-alt"></i> DDoS-Schutz<br>
                                            <i class="fas fa-file-contract"></i> Keine Vertragsbindung
                                        </div>
                                    </div>
                                    <hr>

                                    <form method="post" id="orderFormConf">

                                        <div class="row">
                                            <div class="col-6">
                                                <i class="fas fa-microchip"></i> <b><label style="font-weight: bold;" for="cores">Kerne</label></b>
                                                <select class="form-select form-select-solid" tabindex="-1" data-control="select2" data-placeholder="Wähle deine Kerne aus." data-allow-clear="1" id="cores" name="cores">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_option_entries` WHERE `option_id` = :option_id");
                                                    $SQL->execute(array(":option_id" => '1'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option data-price="<?= $row['price']; ?>" value="<?= $row['value']; ?>"><?= $row['name']; ?> (+ <?= $row['price']; ?>€)</option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <i class="fas fa-memory"></i> <b><label style="font-weight: bold;" for="memory">Arbeitsspeicher</label></b>
                                                <select class="form-select form-select-solid" tabindex="-1" data-control="select2" data-placeholder="Wähle dein Arbeitsspeicher aus." data-allow-clear="1" id="memory" name="memory">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_option_entries` WHERE `option_id` = :option_id");
                                                    $SQL->execute(array(":option_id" => '2'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option data-price="<?= $row['price']; ?>" value="<?= $row['value']; ?>"><?= $row['name']; ?> (+ <?= $row['price']; ?>€)</option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <br>
                                            </div>

                                            <div class="col-12">
                                                <i class="fas fa-hdd"></i> <b><label style="font-weight: bold;" for="disk">Festplatte</label></b>
                                                <select class="form-select form-select-solid" tabindex="-1" data-control="select2" data-placeholder="Wähle dein Speicherplatz aus." data-allow-clear="1" id="disk" name="disk">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_option_entries` WHERE `option_id` = :option_id");
                                                    $SQL->execute(array(":option_id" => '3'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option data-price="<?= $row['price']; ?>" value="<?= $row['value']; ?>"><?= $row['name']; ?> (+ <?= $row['price']; ?>€)</option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <br>
                                            </div>

                                            <div class="col-6">
                                                <i class="fas fa-network-wired"></i> <b><label style="font-weight: bold;" for="addresses">IPv4-Adressen</label></b>
                                                <select class="form-select form-select-solid" tabindex="-1" data-control="select2" data-placeholder="Wähle die Anzahl der IPv4-Adressen aus." data-allow-clear="1" id="addresses" name="addresses">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_option_entries` WHERE `option_id` = :option_id");
                                                    $SQL->execute(array(":option_id" => '4'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option data-price="<?= $row['price']; ?>" value="<?= $row['value']; ?>"><?= $row['name']; ?> (+ <?= $row['price']; ?>€)</option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <i class="fas fa-network-wired"></i> <b><label style="font-weight: bold;" for="addresses">IPv6-Adressen</label></b>
                                                <select class="form-select form-select-solid" tabindex="-1" data-control="select2" data-placeholder="Wähle die Anzahl der IPv6-Adressen aus." data-allow-clear="1" id="ipv6addressess" name="ipv6addressess">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_option_entries` WHERE `option_id` = :option_id");
                                                    $SQL->execute(array(":option_id" => '5'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option data-price="<?= $row['price']; ?>" value="<?= $row['value']; ?>"><?= $row['name']; ?> (+ <?= $row['price']; ?>€)</option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <br>
                                            </div>

                                            <div class="col-12">
                                                <i class="fas fa-shield-alt"></i> <b><label style="font-weight: bold;">Bandbreite (Netzwerkgeschwindigkeit)</label></b>
                                                <select class="form-control" name="network">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_option_entries` WHERE `option_id` = :option_id");
                                                    $SQL->execute(array(":option_id" => '7'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option data-price="<?= $row['price']; ?>" value="<?= $row['value']; ?>"><?= $row['name']; ?> (+ <?= $row['price']; ?>€)</option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <br>
                                            </div>

                                            <div class="col-12">
                                                <i class="fas fa-shield-alt"></i> <b><label style="font-weight: bold;">DDoS Protection</label></b>
                                                <select class="form-control" name="ddos-protection">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_option_entries` WHERE `option_id` = :option_id");
                                                    $SQL->execute(array(":option_id" => '6'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option data-price="<?= $row['price']; ?>" value="<?= $row['value']; ?>"><?= $row['name']; ?> (+ <?= $row['price']; ?>€)</option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <br>
                                            </div>

                                            <div class="col-6">
                                                <i class="fab fa-linux"></i> <b><label style="font-weight: bold;" for="serverOS">Betriebssystem</label></b>
                                                <select class="form-select form-select-solid" tabindex="-1" data-control="select2" data-placeholder="Wähle das Betriebssystem aus." data-allow-clear="1" id="serverOS" name="serverOS">
                                                    <?php
                                                    $SQL = $db->prepare("SELECT * FROM `lxc_servers_os` WHERE `type` = :type");
                                                    $SQL->execute(array(":type" => 'PROXMOX'));
                                                    if ($SQL->rowCount() != 0) { while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <i class="fas fa-history"></i> <b><label style="font-weight: bold;" for="interval">Laufzeit</label></b>
                                                <select class="form-control form-control-solid" id="duration" name="duration">
                                                    <option value="30" data-factor="1">30 Tage</option>
                                                    <option value="60" data-factor="2">60 Tage</option>
                                                    <option value="90" data-factor="3">90 Tage</option>
                                                    <option value="180" data-factor="6">180 Tage</option>
                                                    <option value="365" data-factor="12">365 Tage</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <br>
                                        </div>

                                        <label for="agb" class="checkbox noselect">
                                            <input type="checkbox" name="agb" id="agb">
                                            <span></span>
                                            Ich habe die <a href="<?= env('LEGAL_URL') . 'conditions/'; ?>">AGB</a> und <a href="<?= env('LEGAL_URL') . 'privacy/'; ?>">Datenschutzerklärung</a> gelesen und akzeptiere diese.
                                        </label>
                                        <br>
                                        <label for="wiederruf" class="checkbox noselect">
                                            <input type="checkbox" name="wiederruf" id="wiederruf">
                                            <span></span>
                                            Ich wünsche die vollständige Ausführung der Dienstleistung vor Fristablauf des <a href="<?= env('LEGAL_URL') . 'withdrawal/'; ?>">Widerufsrechts</a> gemäß Fernabsatzgesetz. Die automatische Einrichtung und Erbringung der Dienstleistung führt zum Erlöschen des Widerrufsrechts.
                                        </label>

                                        <input hidden value="" name="order_conf">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow mb-5">
                                <div class="card-header text-center">
                                    <h3 class="card-title" style="margin-bottom: 0px;">Kostenübersicht</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <b class="mb-0">
                                                Gewählte Laufzeit
                                            </b>
                                        </div>
                                        <div class="col-auto">
                                            <a class="text-muted">
                                                <span id="duration_val"></span> Tage
                                            </a>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row align-items-center">
                                        <div class="col">
                                            <b class="mb-0" style="width: 70%;">
                                                Guthaben vorher:
                                            </b>
                                        </div>
                                        <div class="col-auto">
                                            <a class="text-muted" style="width: 70%;">
                                                <?= $amount; ?>€
                                            </a>
                                        </div>
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col">
                                            <b class="mb-0" style="width: 70%;">
                                                Guthaben nachher:
                                            </b>
                                        </div>
                                        <div class="col-auto">
                                            <a class="text-muted" style="width: 70%;">
                                                <span account_after="">0.00</span>
                                            </a>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row align-items-center">
                                        <div class="col">
                                            <b class="mb-0">
                                                Heute fällig:
                                            </b>
                                        </div>
                                        <div class="col-auto">
                                            <a class="text-muted">
                                                <span data-amount="">0.00</span>
                                            </a>
                                        </div>
                                    </div>
                                    <hr>
                                    <br>

                                    <a href="#" onclick="orderNowConf();" id="orderBtnConf" class="btn btn-block btn-outline-primary mb-4">
                                        <i class="fas fa-shopping-cart"></i> <b>Kostenpflichtig bestellen</b>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <script>

                            function orderNowConf() {
                                document.getElementById("orderFormConf").submit();
                                const button = document.getElementById('orderBtnConf');
                                button.disabled = true;
                                button.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> Bestellung wird ausgeführt...';
                            }


                            $("select, textarea").change(function() { update(); } ).trigger("change");

                            function update()
                            {

                                var ground = <?= $site->getProductPrice('LXC_VSERVER'); ?>;
                                sum = ground + parseFloat($("#cores").find("option:selected").data("price"))
                                    +parseFloat($("#memory").find("option:selected").data("price"))
                                    +parseFloat($("#disk").find("option:selected").data("price"))
                                    +parseFloat($("#addresses").find("option:selected").data("price"));
                                var price = Number(sum * $("#duration").find("option:selected").data("factor")).toFixed(2);
                                $("*[data-amount]").html(price + " €");

                                // calculate amount after order
                                var account_after = Number(parseFloat(<?= $amount; ?> - price)).toFixed(2);
                                $('*[account_after]').html(account_after + "€");

                                var duration = $("#duration").find("option:selected").val();
                                $('#duration_val').html(duration);
                            }

                            $(document).ready(function(){
                                update();
                            });
                        </script>
                    </div>

                <?php } elseif(isset($_POST['step2_packs'])) { include BASE_PATH . 'software/managing/customer/rootserver/hosterapi/order_packs.php'; ?>

                    <div class="row">

                        <?php
                        $SQL = $db->prepare("SELECT * FROM `lxc_servers_packs` WHERE `type` = :type");
                        $SQL->execute(array(":type" => 'AMD'));
                        if ($SQL->rowCount() != 0) {
                            while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){
                                ?>
                                <div class="col-md-4">
                                    <div class="card shadow mb-5">
                                        <div class="card-header text-center">
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
                                            <h1 style="font-size: 30px;"><?= $row['name']; ?></h1>
                                            <b><span style="font-size: 28px;">
                                        <?php
                                        if(is_null($row['old_price'])) {
                                            echo $row['price'].'€';
                                        } else {
                                            echo '<font style="color: red;">'.$row['price'].'€</font>';
                                        }
                                        ?>
                                                    <?php
                                                    if(is_null($row['old_price'])) {

                                                    } else {
                                                        echo '<small><s> '.$row['old_price'].'€</s></small></span><br>';
                                                    }
                                                    ?> / 30 Tage</b>
                                        </div>
                                        <div class="card-body">
                                            <form method="post">
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <rect fill="#000000" opacity="0.3" x="4" y="4" width="16" height="16" rx="2"/>
                                    <rect fill="#000000" opacity="0.3" x="9" y="9" width="6" height="6"/>
                                    <path d="M20,7 L21,7 C21.5522847,7 22,7.44771525 22,8 L22,8 C22,8.55228475 21.5522847,9 21,9 L20,9 L20,7 Z" fill="#000000"/>
                                    <path d="M20,11 L21,11 C21.5522847,11 22,11.4477153 22,12 L22,12 C22,12.5522847 21.5522847,13 21,13 L20,13 L20,11 Z" fill="#000000"/>
                                    <path d="M20,15 L21,15 C21.5522847,15 22,15.4477153 22,16 L22,16 C22,16.5522847 21.5522847,17 21,17 L20,17 L20,15 Z" fill="#000000"/>
                                    <path d="M3,7 L4,7 L4,9 L3,9 C2.44771525,9 2,8.55228475 2,8 L2,8 C2,7.44771525 2.44771525,7 3,7 Z" fill="#000000"/>
                                    <path d="M3,11 L4,11 L4,13 L3,13 C2.44771525,13 2,12.5522847 2,12 L2,12 C2,11.4477153 2.44771525,11 3,11 Z" fill="#000000"/>
                                    <path d="M3,15 L4,15 L4,17 L3,17 C2.44771525,17 2,16.5522847 2,16 L2,16 C2,15.4477153 2.44771525,15 3,15 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> <?= $row['cores']; ?> Kern(e)<br>
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Devices/SD-card.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5,5 C5,4.44771525 5.44771525,4 6,4 L15.75,4 C15.9073787,4 16.0555728,4.07409708 16.15,4.2 L18.9,7.86666667 C18.9649111,7.95321475 19,8.05848156 19,8.16666667 L19,19 C19,19.5522847 18.5522847,20 18,20 L6,20 C5.44771525,20 5,19.5522847 5,19 L5,5 Z M7,6 L7,9 L9,9 L9,6 L7,6 Z M10,6 L10,9 L12,9 L12,6 L10,6 Z M13,6 L13,9 L15,9 L15,6 L13,6 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> <?= $row['memory']/1024; ?>GB RAM<br>
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Devices/Hard-drive.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M2,13 L22,13 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,13 Z M18.5,18 C19.3284271,18 20,17.3284271 20,16.5 C20,15.6715729 19.3284271,15 18.5,15 C17.6715729,15 17,15.6715729 17,16.5 C17,17.3284271 17.6715729,18 18.5,18 Z M13.5,18 C14.3284271,18 15,17.3284271 15,16.5 C15,15.6715729 14.3284271,15 13.5,15 C12.6715729,15 12,15.6715729 12,16.5 C12,17.3284271 12.6715729,18 13.5,18 Z" fill="#000000"/>
                                    <path d="M5.79268604,8 L18.207314,8 C18.5457897,8 18.8612922,8.17121884 19.0457576,8.45501165 L22,13 L2,13 L4.95424243,8.45501165 C5.13870775,8.17121884 5.45421032,8 5.79268604,8 Z" fill="#000000" opacity="0.3"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> <?= $row['disk']; ?>GB SSD Speicher<br>
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Map/Marker1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> Frankfurt am Main, DE (maincubes) <i class="fas fa-check text-success"></i><br>
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Communication/RSS.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <circle fill="#000000" cx="6" cy="18" r="3"/>
                                    <path d="M16.5,21 L13.5,21 C13.5,15.2010101 8.79898987,10.5 3,10.5 L3,7.5 C10.4558441,7.5 16.5,13.5441559 16.5,21 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <path d="M22.5,21 L19.5,21 C19.5,12.163444 11.836556,4.5 3,4.5 L3,1.5 C13.4934102,1.5 22.5,10.5065898 22.5,21 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> 1 Gbit/s (Shared) Anbindung <i class="fas fa-check text-success"></i><br>
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-bar2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <rect fill="#000000" opacity="0.3" x="17" y="4" width="3" height="13" rx="1.5"/>
                                    <rect fill="#000000" opacity="0.3" x="12" y="9" width="3" height="8" rx="1.5"/>
                                    <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                    <rect fill="#000000" opacity="0.3" x="7" y="11" width="3" height="6" rx="1.5"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> Fair Use Traffic <i class="fas fa-check text-success"></i><br>
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Communication/Shield-thunder.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3"/>
                                    <polygon fill="#000000" opacity="0.3" points="11.3333333 18 16 11.4 13.6666667 11.4 13.6666667 7 9 13.6 11.3333333 13.6"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> bis zu 750GBit/s Arbor DDoS-Schutz <i class="fas fa-check text-success"></i><br>
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2020-10-29-133027/theme/html/demo1/dist/../src/media/svg/icons/Files/File.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                                    <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                                </g>
                            </svg><!--end::Svg Icon--></span> Keine Vertragsbindung	<i class="fas fa-check text-success"></i>

                                                <br>
                                                <br>
                                                <hr>
                                                <br>

                                                <i class="fab fa-linux"></i> <b><label style="font-weight: bold;" for="serverOS">Betriebssystem</label></b>
                                                <select class="form-control" id="serverOS" name="serverOS">
                                                    <?php
                                                    $SQL3 = $db->prepare("SELECT * FROM `kvm_servers_os` WHERE `type` = :type");
                                                    $SQL3->execute(array(":type" => 'INTEL'));
                                                    if ($SQL3->rowCount() != 0) { while ($row3 = $SQL3->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option value="<?= $row3['id']; ?>"><?= $row3['name']; ?></option>
                                                    <?php } } ?>
                                                </select>

                                                <br>
                                                <hr>
                                                <br>

                                                <input hidden value="<?= $row['id']; ?>" name="plan_id">

                                                <label for="agb<?= $row['id']; ?>" class="checkbox noselect">
                                                    <input type="checkbox" name="agb" id="agb<?= $row['id']; ?>">
                                                    <span></span>
                                                    Ich habe die <a href="<?= $helper->url(); ?>agb">AGB</a> und <a href="<?= $helper->url(); ?>datenschutz">Datenschutzerklärung</a> gelesen und akzeptiere diese.
                                                </label>
                                                <br>
                                                <br>
                                                <label for="wiederruf<?= $row['id']; ?>" class="checkbox noselect">
                                                    <input type="checkbox" name="wiederruf" id="wiederruf<?= $row['id']; ?>">
                                                    <span></span>
                                                    Ich wünsche die vollständige Ausführung der Dienstleistung vor Fristablauf des Widerufsrechts gemäß Fernabsatzgesetz. Die automatische Einrichtung und Erbringung der Dienstleistung führt zum Erlöschen des Widerrufsrechts.
                                                </label>

                                                <br>
                                                <br>

                                                <input hidden="hidden" name="order_packs">

                                                <button type="submit" name="order_packs" class="btn btn-block btn-outline-primary mb-4">
                                                    <i class="fas fa-shopping-cart"></i> <b>Kostenpflichtig bestellen</b>
                                                </button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } } else { ?>

                            <div class="col-md-12">
                                <div class="card shadow mb-5">
                                    <div class="card-body" style="text-align: center;">
                                        <h4 style="font-style: bold;">
                                            Momentan sind leider keine LXC V-Server Pakete verfügbar.
                                        </h4>
                                        <p style="font-size: 100%;">
                                            Wir sind bereits an der Nachbeschaffung, sodass wir dir schnellstmöglich wieder LXC V-Server Pakete anbieten können.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                    </div>

                <?php } else { ?>

                    <div class="row justify-content-center">
                        <div class="col-md-3">
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

                                        <br>
                                        Konfigurator

                                        <hr>
                                    </h3>
                                    <span style="font-size: 110%;">
                                        Stelle dir deinen eigenen Server zusammen.
                                    </span>

                                    <br><br>

                                    <form method="post">
                                        <button class="btn btn-outline-primary btn-block">
                                            <input name="step2_conf" value="<?= $_POST['step2_conf']; ?>" hidden>
                                            <i class="fas fa-share-square"></i> <b>Zu dem Konfigurator</b>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <!--<!div class="row" style="text-align: center;">
                                <div class="col-12">
                                    <div class="alert alert-primary" role="alert">
                                        <h2>Rabatt verfügbar</h2> <hr>
                                        <p>Bis zu -35% Rabatt sichern.</p>
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

                                        <br>
                                        Pakete

                                        <hr>
                                    </h3>
                                    <span style="font-size: 110%;">
                                       Vorgerfertigte Pakete für beliebige Anwendungen.
                                    </span>

                                    <br><br>

                                    <form method="post">
                                        <button class="btn btn-outline-primary btn-block">
                                            <input name="step2_packs" value="<?= $_POST['step2_packs']; ?>" hidden>
                                            <i class="fas fa-share-square"></i> <b>Zu den Paketen</b>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>