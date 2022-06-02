<?php

$currPage = 'customer_Webspace bestellen';
include BASE_PATH . 'software/controller/PageController.php';

// include manage file
include BASE_PATH . 'software/managing/customer/webspace/order.php';

?>

<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <!--begin::Pricing card-->
            <div class="card" id="kt_pricing">
                <!--begin::Card body-->
                <div class="card-body p-lg-17">
                    <!--begin::Plans-->
                    <div class="d-flex flex-column">
                        <!--begin::Heading-->
                        <div class="mb-13 text-center">
                            <h1 class="fs-2hx fw-bolder mb-5">Wähle aus verschiedenen Paketen aus</h1>
                            <div class="text-gray-400 fw-bold fs-5">
                                Hier findest Du jegliche Art von Webspace-Paketen, mit verschiedener Anzahl an Datenbanken, FTP-Accounts, etc und natürlich den Webspeicher.
                            </div>
                        </div>
                        <!--end::Heading-->
                        <!--begin::Row-->
                        <div class="row g-10">

                            <?php
                            $i = 0;

                            $disc = [];
                            $domains = [];
                            $subdomains = [];
                            $databases = [];
                            $ftp_accounts = [];
                            $emails = [];

                            $SQL = $db->prepare("SELECT * FROM `webspaces_packs_normal` WHERE `frontend` = :frontend");
                            $SQL->execute(array(":frontend" => '1'));
                            if($SQL->rowCount() != 0) {
                                while($row = $SQL->fetch(PDO::FETCH_ASSOC)) {

                                    array_push($disc, $row['disc']);
                                    array_push($domains, $row['domains']);
                                    array_push($subdomains, $row['subdomains']);
                                    array_push($databases, $row['databases']);
                                    array_push($ftp_accounts, $row['ftp_accounts']);
                                    array_push($emails, $row['emails']);
                                    ?>


                                    <div class="modal fade" id="webspaceModal<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="webspaceModal<?= $row['id']; ?>Label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="webspaceModal<?= $row['id']; ?>Label">Webspace mieten</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">



                                                    <div class="card-rounded d-flex flex-stack flex-wrap p-5" style="align-content: center; text-align: center;">
                                                        <ul class="nav flex-wrap border-transparent fw-bolder">
                                                            <li class="nav-item my-1">
                                                                <a class="btn btn-color-primary-600 btn-active-primary fw-boldest fs-8 fs-lg-base nav-link px-3 px-lg-8 mx-1 text-uppercase" data-bs-toggle="tab" href="#custom<?= $row['id']; ?>" aria-controls="custom<?= $row['id']; ?>" aria-selected="true">
                                                                    Eigene Domain
                                                                </a>
                                                            </li>

                                                            <li class="nav-item my-1">
                                                                <a class="btn btn-color-gray-600 btn-active-primary fw-boldest fs-8 fs-lg-base nav-link px-3 px-lg-8 mx-1 text-uppercase" data-bs-toggle="tab" href="#subdomain<?= $row['id']; ?>" aria-controls="subdomain<?= $row['id']; ?>" aria-selected="false">
                                                                    Sub-Domain von uns
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <hr>

                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="custom<?= $row['id']; ?>" role="tabpanel" aria-labelledby="custom-tab<?= $row['id']; ?>">

                                                            <form method="post">

                                                                <label>Domain</label>
                                                                <input class="form-control form-control-solid" name="domainName" placeholder="deine-domain.de" required>

                                                                <br>
                                                                <label for="agb<?= $row['id']; ?>_1" class="checkbox noselect">
                                                                    <input type="checkbox" name="agb" id="agb<?= $row['id']; ?>_1">
                                                                    <span></span>
                                                                    Ich habe die <a href="<?= $helper->url(); ?>agb">AGB</a> und <a href="<?= $helper->url(); ?>datenschutz">Datenschutzerklärung</a> gelesen und akzeptiere diese.
                                                                </label>
                                                                <label for="wiederruf<?= $row['id']; ?>_1" class="checkbox noselect">
                                                                    <input type="checkbox" name="wiederruf" id="wiederruf<?= $row['id']; ?>_1">
                                                                    <span></span>
                                                                    Ich wünsche die vollständige Ausführung der Dienstleistung vor Fristablauf des Widerufsrechts gemäß Fernabsatzgesetz. Die automatische Einrichtung und Erbringung der Dienstleistung führt zum Erlöschen des Widerrufsrechts.
                                                                </label>

                                                                <input hidden value="<?= $row['plesk_id']; ?>" name="planName">

                                                                <br>
                                                                <hr>

                                                                <div class="gap-2 d-md-block">
                                                                    <button type="submit" name="order" class="btn btn-success text-uppercase font-weight-bolder">
                                                                        <i class="fas fa-shopping-cart"></i> Kostenpflichtig bestellen
                                                                    </button>

                                                                    <button type="button" class="btn btn-outline-danger text-uppercase font-weight-bolder" data-bs-dismiss="modal">
                                                                        <i class="fas fa-ban"></i> Abbrechen
                                                                    </button>

                                                                </div>
                                                            </form>

                                                        </div>
                                                        <div class="tab-pane fade" id="subdomain<?= $row['id']; ?>" role="tabpanel" aria-labelledby="subdomain-tab<?= $row['id']; ?>">

                                                            <form method="post">

                                                                <label>Domain</label>
                                                                <input class="form-control form-control-solid" style="background-color: #2d2c2c;" readonly name="domainName" value="web<?= $userid.'-'.rand(0,9).rand(0,9).rand(0,9).rand(0,9); ?>.<?= env('CUSTOM_WEBSPACE_SUBDOMAIN'); ?>" required>

                                                                <br>
                                                                <label for="agb<?= $row['id']; ?>_2" class="checkbox noselect">
                                                                    <input type="checkbox" name="agb" id="agb<?= $row['id']; ?>_2">
                                                                    <span></span>
                                                                    Ich habe die <a href="<?= $helper->url(); ?>agb">AGB</a> und <a href="<?= $helper->url(); ?>datenschutz">Datenschutzerklärung</a> gelesen und akzeptiere diese.
                                                                </label>
                                                                <label for="wiederruf<?= $row['id']; ?>_2" class="checkbox noselect">
                                                                    <input type="checkbox" name="wiederruf" id="wiederruf<?= $row['id']; ?>_2">
                                                                    <span></span>
                                                                    Ich wünsche die vollständige Ausführung der Dienstleistung vor Fristablauf des Widerufsrechts gemäß Fernabsatzgesetz. Die automatische Einrichtung und Erbringung der Dienstleistung führt zum Erlöschen des Widerrufsrechts.
                                                                </label>

                                                                <input hidden value="<?= $row['plesk_id']; ?>" name="planName">

                                                                <br>
                                                                <hr>

                                                                <div class="gap-2 d-md-block">
                                                                    <button type="submit" name="order" class="btn btn-success text-uppercase font-weight-bolder">
                                                                        <i class="fas fa-shopping-cart"></i> Kostenpflichtig bestellen
                                                                    </button>

                                                                    <button type="button" class="btn btn-outline-danger text-uppercase font-weight-bolder" data-bs-dismiss="modal">
                                                                        <i class="fas fa-ban"></i> Abbrechen
                                                                    </button>

                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        function orderNow<?= $row['id']; ?>() {
                                            document.getElementById("orderForm<?= $row['id']; ?>").submit();
                                            const button = document.getElementById('orderBtn<?= $row["id"]; ?>');
                                            button.disabled = true;
                                            button.innerHTML = '<i class="fas fa-sync-alt fa-spin"></i> wird ausgeführt...';
                                        }
                                    </script>


                                    <!--begin::Col-->
                            <div class="col-xl-4">
                                <div class="d-flex h-100 align-items-center">
                                    <!--begin::Option-->
                                    <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                        <!--begin::Heading-->
                                        <div class="mb-7 text-center">
                                            <!--begin::Title-->
                                            <h1 class="text-dark mb-5 fw-boldest"><?= $row['name']; ?></h1>
                                            <!--end::Title-->
                                            <!--begin::Description-->
                                            <div class="text-gray-400 fw-bold mb-5">
                                                Optimal für jegliche Art eines Webauftritts.
                                            </div>
                                            <!--end::Description-->
                                            <!--begin::Price-->
                                            <div class="text-center">
                                                <?php if(is_null($row['old_price'])) { ?>
                                                    <span class="fs-3x fw-bolder text-primary" data-kt-plan-price-month="<?= $row['price']; ?>">
                                                        <?= $row['price']; ?>
                                                    </span>
                                                    <span class="fs-4 mb-2 text-primary">€</span>
                                                    <span class="fs-7 fw-bold opacity-50">/
                                                        <span data-kt-element="period">30 Tage</span>
                                                    </span>
                                                <?php } else { ?>

                                                    <span class="fs-3x fw-bolder text-primary" data-kt-plan-price-month="<?= $row['price']; ?>">
                                                        <font style="color: red;">
                                                            <?= $row['price']; ?>
                                                        </font>
                                                    </span>
                                                    <span class="fs-4 mb-2 text-primary" style="margin-right: 6px;">€</span>

                                                    <span class="fs-7 fw-bold opacity-40">
                                                        <small>
                                                            <s>
                                                                <?= $row['old_price']; ?>€
                                                            </s>
                                                        </small>
                                                    </span>
                                                    <span class="fs-7 fw-bold opacity-50">/
                                                        <span data-kt-element="period">30 Tage</span>
                                                    </span>

                                                <?php } ?>
                                            </div>
                                            <!--end::Price-->
                                        </div>
                                        <!--end::Heading-->
                                        <!--begin::Features-->
                                        <div class="w-100 mb-10">
                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3">PHP7.0-8.1</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->
                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3"><?= $row['disc']; ?>GB NVMe Webspeicher</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->
                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3"><?= $row['domains']; ?> Domains</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->
                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3"><?= $row['subdomains']; ?> Sub-Domains</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3"><?= $row['databases']; ?> Datenbanken</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3"><?= $row['ftp_accounts']; ?> FTP-Accounts</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3"><?= $row['emails']; ?> E-Mail Postfächer</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3">25+ extra Features</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3">Backupredundanz</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->

                                            <!--begin::Item-->
                                            <div class="d-flex align-items-center mb-5">
                                                <span class="fw-bold fs-6 text-gray-800 flex-grow-1 pe-3">combahton DDoS-Schutz</span>
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                <span class="svg-icon svg-icon-1 svg-icon-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="black" />
                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Item-->
                                        </div>
                                        <!--end::Features-->
                                        <!--begin::Select-->
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#webspaceModal<?= $row['id']; ?>" class="btn btn-sm btn-primary">Auswählen</a>
                                        <!--end::Select-->
                                    </div>
                                    <!--end::Option-->
                                </div>
                            </div>

                             <?php $i++; } } ?>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Plans-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Pricing card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->