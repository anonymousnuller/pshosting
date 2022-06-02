<?php

$currPage = 'front_Changelog';
include BASE_PATH.'software/controller/PageController.php';

?>


<div class="docs-content d-flex flex-column flex-column-fluid" id="kt_docs_content">
    <!--begin::Container-->
    <div class="container" id="kt_docs_content_container">
        <!--begin::Card-->
        <div class="card card-docs mb-2">
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <!--begin::Changelog-->
                <div class="accordion accordion-flush accordion-icon-toggle" id="kt_accordion">
                    <!--begin::Item-->
                    <div class="accordion-item mb-5">
                        <!--begin::Header-->
                        <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_body_v8_0_36">
                            <span class="accordion-icon">
													<!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black" />
                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black" />
                                    </svg>
                                </span>
                                                    <!--end::Svg Icon-->
                            </span>
                            <h3 class="fs-2 text-gray-800 fw-bolder mb-0 ms-4"><?= env('APP_NAME'); ?> - v2.2</h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div id="kt_accordion_body_v8_0_36" class="fs-6 mt-1 mb-1 py-0 ps-10 collapse show" data-bs-parent="#kt_accordion">
                            <div class="accordion-body ps-0 pt-0">
                                <div class="mb-5">
                                    <h3 class="fs-6 fw-bolder mb-1">Neues:</h3>
                                    <ul class="my-0 py-0">
                                        <li class="py-2">
                                            <code class="ms-0">Neues Design</code> - wir haben das Customer-Portal ein neues Design verpasst.
                                        </li>

                                        <li class="py-2">
                                            <code class="ms-0">Bootstrap 5</code> - zusätzlich mit der Design-Anpassung haben wir auf Bootstrap 5 geupdated.
                                        </li>

                                        <li class="py-2">
                                            <code class="ms-0">Ticket-System</code> - wir haben das Ticketsystem grundlegend verändert und mit einem neuen Design versehen.
                                        </li>
                                    </ul>
                                </div>
                                <div class="mb-5">
                                    <h3 class="fs-6 fw-bolder mb-1">Geändert:</h3>
                                    <ul class="my-0 py-0">
                                        <li class="py-2">
                                            <code class="ms-0">Backup-Redundanz</code> - wir haben explizite Änderungen an unserer Backup-Infrastruktur durchgeführt und gewährleisten mithilfe einiger Partner eine bessere Backupversorgung.
                                        </li>

                                        <li class="py-2">
                                            <code class="ms-0">Design</code> - allgemeine Anpassungen an das Portal wurden vorgenommen, um es übersichtlicher und noch einfacher zu gestalten.
                                        </li>
                                    </ul>
                                </div>
                                <div class="mb-5">
                                    <h3 class="fs-6 fw-bolder mb-1">Fix:</h3>
                                    <ul class="my-0 py-0">
                                        <li class="py-2">
                                            <code class="ms-0">Datenbank-Redundanz</code> - vermehrte Datenbankprobleme gehören mit dem Redundanzausbau an mehreren Standorten nun der Vergangenheit an.
                                        </li>
                                    </ul>
                                </div>
                                <div class="mb-5">
                                    <h3 class="fs-6 fw-bolder mb-1">Entfernt:</h3>
                                    <ul class="my-0 py-0">
                                        <li class="py-2">
                                            <code class="ms-0">Unnötige Files</code> - wir haben unnötige Frontend-Designs, Backend-Files und weiteres entfernt.
                                        </li>

                                        <li class="py-2">
                                            <code class="ms-0">Entfernung von Unterkategorien</code> - die Sidebar wurde aufgeräumt und eine Unterteilung für Profil-Einstellungen über die User-Sidebar angeordnet.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Item-->
                </div>
            </div>
        </div>
    </div>
</div>