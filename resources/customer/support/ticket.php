<?php
$currPage = 'customer_Ticket Support';
include BASE_PATH.'software/controller/PageController.php';

## include ticket manage file
include BASE_PATH . 'software/managing/customer/support/manage.php';

?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="container" style="max-width: 130%;">
                <div class="row">
                    <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                        <!--begin::Messenger-->
                        <div class="card" id="kt_chat_messenger">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <div class="card-title">
                                    <!--begin::User-->
                                    <div class="d-flex justify-content-center flex-column me-3">
                                        <a href="javascript:;" class="fs-4 fw-bolder text-gray-900 text-hover-primary me-1 mb-2 lh-1">Betreff: <?= $ticketInfos['title']; ?></a>
                                    </div>
                                    <!--end::User-->
                                </div>

                                <div class="card-toolbar">
                                    <form method="post" id="<?php if($ticketInfos['state'] == 'CLOSED') { echo 'openTicket'; } else { echo 'closeTicket'; } ?>">

                                        <button type="button" class="btn btn-<?php if($ticketInfos['state'] == 'CLOSED') { echo 'success'; } else { echo 'danger'; } ?> btn-sm" onclick="ticketAction();">
                                            <?php if($ticketInfos['state'] == 'CLOSED') { echo '<i class="fas fa-lock-open"></i> öffnen'; } else { echo '<i class="fas fa-lock"></i> schließen'; } ?>
                                        </button>

                                        <input hidden="hidden" name="<?php if($ticketInfos['state'] == 'CLOSED') { echo 'openTicket'; } else { echo 'closeTicket'; } ?>">

                                    </form>

                                    <?php if($ticketInfos['state'] == 'CLOSED') { ?>

                                        <script>
                                            function ticketAction() {
                                                Swal.fire({
                                                    text: "Bist Du dir Sicher, dass Du das Ticket wieder eröffnen möchtest?",
                                                    icon: "question",
                                                    buttonStyling: false,
                                                    showCancelButton: true,
                                                    confirmButtonText: "Ja, bin ich!",
                                                    cancelButtonText: "Nein, abbrechen.",
                                                    customClass: {
                                                        confirmButton: "btn btn-success",
                                                        cancelButton: "btn btn-outline-primary"
                                                    }
                                                }).then((result) => {
                                                    if(result.isConfirmed) {
                                                        document.getElementById('openTicket').submit();
                                                    }
                                                });
                                            }
                                        </script>

                                    <?php } else { ?>

                                        <script>
                                            function ticketAction() {
                                                Swal.fire({
                                                    text: "Bist Du dir Sicher, dass Du das Ticket schließen möchtest?",
                                                    icon: "question",
                                                    buttonStyling: false,
                                                    showCancelButton: true,
                                                    confirmButtonText: "Ja, bin ich!",
                                                    cancelButtonText: "Nein, abbrechen.",
                                                    customClass: {
                                                        confirmButton: "btn btn-success",
                                                        cancelButton: "btn btn-outline-primary"
                                                    }
                                                }).then((result) => {
                                                    if(result.isConfirmed) {
                                                        document.getElementById('closeTicket').submit();
                                                    }
                                                });
                                            }
                                        </script>

                                    <?php } ?>

                                    <!--begin::Menu-->
                                    <div class="me-2">
                                        <button class="btn btn-sm btn-icon btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="bi bi-three-dots fs-3"></i>
                                        </button>
                                        <!--begin::Menu 3-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3 my-1">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="tooltip" title="Coming soon">Einstellungen</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu 3-->
                                    </div>
                                    <!--end::Menu-->
                                </div>
                            </div>

                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body row">
                                <div class="col-md-12" style="clear: both;">
                                    <span style="float: left;">
                                        Ticket-ID: <?= $ticket_id; ?>
                                    </span>
                                    <span style="float: right;">
                                        Deine Support-PIN: <?= $support_pin; ?> <i style="cursor: pointer;" class="fas fa-copy copy-btn" data-clipboard-text="<?= $support_pin; ?>" data-toggle="tooltip" title="Support-PIN kopieren"></i>
                                    </span>
                                </div>

                                <br><br>

                                <div class="card-subtitle">
                                    <div class="row">
                                        <?php
                                        if ($ticketInfos['state'] == 'OPEN') {
                                            $status = '<span class="badge badge-success">Offen</span>';
                                        } elseif ($ticketInfos['state'] == 'PROCESSING') {
                                            $status = '<span class="badge badge-info">In Bearbeitung</span>';
                                        } elseif ($ticketInfos['state'] == 'WAITINGC') {
                                            $status = '<span class="badge badge-warning">Warte auf Kunde</span>';
                                        } elseif ($ticketInfos['state'] == 'WAITINGI') {
                                            $status = '<span class="badge badge-warning">Warte auf Inhaber</span>';
                                        } elseif ($ticketInfos['state'] == 'CLOSED') {
                                            $status = '<span class="badge badge-danger">Geschlossen</span>';
                                        }
                                        ?>
                                        <div class="col-md-3">Status » <?= $status; ?></div>

                                        <?php
                                        if ($ticketInfos['priority'] == 'LOW') {
                                            $priority = '<span class="badge badge-pill badge-dark">Offen</span>';
                                        } elseif ($ticketInfos['priority'] == 'MIDDEL') {
                                            $priority = '<span class="badge badge-pill badge-info">Mittel</span>';
                                        } elseif ($ticketInfos['priority'] == 'HIGH') {
                                            $priority = '<span class="badge badge-pill badge-warning">Hoch</span>';
                                        } elseif ($ticketInfos['priority'] == 'SEHR') {
                                            $priority = '<span class="badge badge-pill badge-success">Sehr hoch</span>';
                                        } elseif ($ticketInfos['priority'] == 'ASAP') {
                                            $priority = '<span class="badge badge-pill badge-danger">ASAP (Notfall)</span>';
                                        }
                                        ?>

                                        <div class="col-md-3">Priorität » <?= $priority; ?></div>

                                        <div class="col-md-3">Letzte Antwort » <?= $last_msg; ?></div>

                                        <div class="col-md-3">Produkt » <?= $ticketInfos['product_id']; ?> (<?= $ticketInfos['product_category']; ?>)</div>

                                    </div>
                                </div>

                                <br><br>

                                <hr class="hr-color">
                                <br>
                                <!--begin::Messages-->
                                <div class="scroll-y me-n5 pe-5 h-300px h-lg-auto" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_messenger_header, #kt_chat_messenger_footer" data-kt-scroll-wrappers="#kt_content, #kt_chat_messenger_body" data-kt-scroll-offset="5px">
                                <?php
                                $SQL = $db->prepare("SELECT * FROM `support_tickets_messages` WHERE `ticket_id` = :ticket_id ORDER BY `id` DESC");
                                $SQL->execute(array(":ticket_id" => $ticket_id));
                                if ($SQL->rowCount() != 0) {
                                    while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {

                                        $writer_token = $user->getDataById($row['writer_id'], 'session_token');

                                        if ($row['type'] == 'message'){
                                            if($user->isInTeam($writer_token) == true) {
                                                if ($user->getDataById($row['writer_id'], 'role') == 'first' || 'second' || 'third' || 'admin') {
                                                    $badge = '<span class="badge rounded-pill bg-info">Support</span>';
                                                }

                                                if(empty($user->getDataById($row['writer_id'], 'firstname')) || is_null($user->getDataById($row['writer_id'], 'firstname'))) {
                                                    $name = $user->getDataById($row['writer_id'], 'username');
                                                } else {
                                                    $name = $user->getDataById($row['writer_id'], 'firstname') . ' ' . $user->getDataById($row['writer_id'], 'lastname');
                                                }
                                ?>

                                    <!--begin::Message(in)-->
                                    <div class="d-flex justify-content-start mb-10">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-column align-items-start">
                                            <!--begin::User-->
                                            <div class="d-flex align-items-center mb-2">
                                                <!--begin::Avatar-->
                                                <div class="symbol symbol-35px symbol-circle">
                                                    <img alt="<?= env('APP_NAME'); ?> Team" src="<?= $helper->imageUrl(); ?>logos/profile.jpg" />
                                                </div>
                                                <!--end::Avatar-->
                                                <!--begin::Details-->
                                                <div class="ms-3">
                                                    <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">
                                                        <?= $name; ?>
                                                    </a>
                                                    <span class="text-muted fs-7 mb-1">schrieb am <?= $helper->formatDateTicketMessage($row['created_at']); ?> Uhr</span>
                                                </div>
                                                <!--end::Details-->
                                            </div>
                                            <!--end::User-->
                                            <!--begin::Text-->
                                            <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start" data-kt-element="message-text">
                                                <?= $helper->nl2br2($row['message']); ?>
                                            </div>
                                            <!--end::Text-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Message(in)-->
                                    <?php } elseif($user->getDataById($row['writer_id'], 'role') == 'customer') {
                                                    $badge = '<span class="badge rounded-pill bg-secondary">Kunde</span>';

                                                    if (empty($user->getDataById($row['writer_id'], 'firstname')) || is_null($user->getDataById($row['writer_id'], 'firstname'))) {
                                                        $name = $user->getDataById($row['writer_id'], 'username');
                                                    } else {
                                                        $name = $user->getDataById($row['writer_id'], 'firstname');
                                                    }

                                                ?>

                                            <!--begin::Message(out)-->
                                            <div class="d-flex justify-content-end mb-10">
                                                <!--begin::Wrapper-->
                                                <div class="d-flex flex-column align-items-end">
                                                    <!--begin::User-->
                                                    <div class="d-flex align-items-center mb-2">
                                                        <!--begin::Details-->
                                                        <div class="me-3">
                                                            <span class="text-muted fs-7 mb-1">schrieb am <?= $helper->formatDateTicketMessage($row['created_at']); ?> Uhr</span>
                                                            <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary ms-1">Du</a>
                                                        </div>
                                                        <!--end::Details-->
                                                        <!--begin::Avatar-->
                                                        <div class="symbol symbol-35px symbol-circle">
                                                            <img alt="<?= $user->getDataById($row['writer_id'], 'username'); ?> Kunde" src="https://api.cookiemc.de/200/<?= $username; ?>.png?ssl=0" />
                                                        </div>
                                                        <!--end::Avatar-->
                                                    </div>
                                                    <!--end::User-->
                                                    <!--begin::Text-->
                                                    <div class="p-5 rounded bg-light-primary text-dark fw-bold mw-lg-400px text-end" data-kt-element="message-text">
                                                        <?= $helper->nl2br2($row['message']); ?>
                                                    </div>
                                                    <!--end::Text-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Message(out)-->

                                    <?php } ?>


                                <!--end::Messages-->
                                <?php } else { ?>
                                            <!--begin::Message(in)-->
                                            <div class="d-flex justify-content-center mb-10">
                                                <!--begin::Wrapper-->
                                                <div class="d-flex flex-column align-items-center">
                                                    <!--begin::User-->
                                                    <div class="d-flex align-items-center mb-2">
                                                        <!--begin::Avatar-->
                                                        <div class="symbol symbol-35px symbol-circle">
                                                            <img alt="<?= env('APP_NAME'); ?> System" src="<?= $helper->imageUrl(); ?>logos/profile.jpg" />
                                                        </div>
                                                        <!--end::Avatar-->
                                                        <!--begin::Details-->
                                                        <div class="ms-3">
                                                            <a href="#" class="fs-5 fw-bolder text-gray-900 text-hover-primary me-1">
                                                                System, schrieb:
                                                            </a>
                                                            <span class="text-muted fs-7 mb-1">schrieb am <?= $helper->formatDateTicketMessage($row['created_at']); ?> Uhr</span>
                                                        </div>
                                                        <!--end::Details-->
                                                    </div>
                                                    <!--end::User-->
                                                    <!--begin::Text-->
                                                    <div class="p-5 rounded bg-light-info text-dark fw-bold mw-lg-400px text-start" data-kt-element="message-text">
                                                        <?= $helper->xssFix($row['message']); ?>
                                                    </div>
                                                    <!--end::Text-->
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
                                            <!--end::Message(in)-->


                                    <?php } } } ?>
                                </div>
                            </div>
                            <!--end::Card body-->
                            <!--begin::Card footer-->

                            <?php if($ticketInfos['state'] == 'OPEN' || 'PROCESSING') { ?>

                                <div class="card-footer pt-4" id="kt_chat_messenger_footer">
                                    <form method="post">
                                        <!--begin::Input-->
                                        <input name="csrf_token" value="<?php $csrf_token = $site->generateCSRF(); echo $csrf_token; $_SESSION['csrf_token'] = $csrf_token; ?>" type="hidden">
                                        <textarea class="form-control form-control-flush mb-3" rows="3" data-kt-element="input" name="message" placeholder="Gebe deine Nachricht oder Antwort ein..."></textarea>
                                        <!--end::Input-->
                                        <!--begin:Toolbar-->
                                        <div class="d-flex flex-stack">
                                            <!--end::Actions-->
                                            <!--begin::Send-->
                                            <button class="btn btn-primary" type="submit" name="answerTicket" data-kt-element="Abschicken">Abschicken</button>
                                            <!--end::Send-->
                                        </div>
                                        <!--end::Toolbar-->
                                    </form>
                                </div>
                                <!--end::Card footer-->
                            <?php } else { ?>

                                <div class="card-footer pt-4" id="kt_chat_messenger_footer">
                                    <div class="d-flex align-items-center text-center">
                                        Das Support-Ticket ist geschlossen.
                                    </div>
                                    <!--end::Toolbar-->
                                </div>
                                <!--end::Card footer-->

                            <?php } ?>
                        </div>
                        <!--end::Messenger-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>