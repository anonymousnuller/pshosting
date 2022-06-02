<?php

$currPage = 'partner_Projekteintrag anlegen';
include BASE_PATH . 'software/controller/PageController.php';

function uploadImage() {

    $target_dir = BASE_PATH . 'public/assets/images/partner/';
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ceck if image file actual file
    if(isset($_POST[''])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);

        if($_FILES["file"]["size"] > 512) {
            $error = 'Die Datei ist zu groß!';
        }

        if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
            $error = 'Bitte lade nur Bilder in Form von .jpg, .png oder .jpeg hoch!';
        }

        if($check !== false) {
            $error = '';

            $uploadOk = 1;
        } else {
            $error = '';
            $uploadOk = 0;
        }

        if($uploadOk == 1) {
            move_uploaded_file($_FILES['file']["tmp_name"], $target_file);

            $result = true;
            $error = 'Hochladen erfolgreich.';
        }
    }
}

?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-2">
                <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5"><?= env('APP_NAME'); ?></h5>
                <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                <span class="text-muted font-weight-bold mr-4"><?= $currPageName; ?></span>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="border-radius: 15px 10px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <form method="post" enctype="multipart/form-data">
                                        Wähle ein Bild aus:
                                        <input type="file" name="file" id="file">
                                    </form>

                                    <img src="<?= env('HOME_URL'); ?>assets/images/partner/school-office.png"  style="border-radius: 5px;" class="img-fluid" alt="School-Office Partner-Logo">
                                </div>
                                <div class="col-8">
                                    <h4 class="iq-title" style="margin-bottom: 10px;">
                                        <input name="title" id="title">
                                    </h4>

                                    <p class="iq-title-desc" style="margin-bottom: 15px;">
                                        <i>
                                            ... ist ein Projekt, welches sich um die Digitialisierung alle deutschen Schulen spezialisiert hat.
                                            Nebenbei werden noch optionale Dienste, wie Cloud-Pakete, Webspace und einige weitere Sachen kostengünstig oder auch kostenlos zur Verfügung gestellt.
                                        </i>
                                    </p>

                                    <a href="https://school-office.eu" class="btn btn-website" target="_blank">
                                        <i class="fas fa-globe"></i> Webseite
                                    </a>

                                    <a href="https://twitter.com/SchoolOfficeTM" class="btn btn-twitter" target="_blank">
                                        <i class="fab fa-instagram"></i> Twitter
                                    </a>

                                    <a href="https://discord.gg/nD3EmJtaz3" class="btn btn-discord" target="_blank">
                                        <i class="fab fa-discord"></i> Discord
                                    </a>
                                </div>

                                <div class="col-2">
                                    <h5 class="iq-title">
                                        Rabattcodes:
                                    </h5>

                                    <ul>
                                        <li>
                                            school2GO (30%)
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
