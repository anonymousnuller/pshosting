<?php

$currPage = 'customer_Zahlung akzeptiert';
include BASE_PATH.'software/controller/PageController.php';

?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="d-flex flex-column-fluid">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center">
                                <img src="<?= $helper->imageUrl(); ?>success.png" width="200" alt="OK"/>

                                <br><br>
                                Deine Zahlung wurde akzeptiert!
                            </h4>
                        </div>

                        <div class="col-md-12 align-items-center text-center">
                            <a href="<?= env('URL') . 'index/'; ?>" class="btn btn-primary">
                                Zum Dashboard
                            </a>

                            <a href="<?= env('URL') . 'payment/charge/'; ?>" class="btn btn-outline-primary">
                                Meine Aufladungen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
