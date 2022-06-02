<?php
$currPage = 'customer_Zahlungsverlauf';
include BASE_PATH.'software/controller/PageController.php';
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">

        <div id="kt_content_container" class="container-xxl">
            <div class="container">
                <div class="col-md-12">
                    <div class="card card-body d-flex flex-column">
                        <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table table-striped table-row-bordered gy-5 gs-7 text-center" id="kt_datatable_example_1">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Beschreibung</th>
                                        <th>Betrag</th>
                                        <th>Datum</th>
                                        <th> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $SQL = $db->prepare("SELECT * FROM `customer_transactions` WHERE `user_id` = :user_id ORDER BY `id` DESC");
                                    $SQL->execute(array(":user_id" => $userid));
                                    if ($SQL->rowCount() != 0) {
                                        while ($row = $SQL -> fetch(PDO::FETCH_ASSOC)){

                                            if($row['amount'] < 0.00){
                                                $amount = '<p style="color: red;">'.$amount.'€</p>';
                                            } else {
                                                $amount = '<p>'.$amount.'€</p>';
                                            }

                                            ?>
                                            <tr>
                                                <td><?= $row['id']; ?></td>
                                                <td><?= $row['desc']; ?></td>
                                                <td><?= $row['amount']; ?>€</td>
                                                <td><?= $helper->formatDateNormal($row['created_at']); ?> Uhr</td>
                                                <td> <a class="btn btn-outline-primary btn-sm font-weight-bolder" href="<?= env('URL'); ?>payment/invoice/<?= $row['id']; ?>/" target="_blank">Zur Rechnung</a> </td>
                                            </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <script type="text/javascript">
                            /*$("#kt_datatable_example_1").DataTable({
                                "scrollY": "500px",
                                "scrollCollapse": true,
                                "paging": true,
                                "dom": "<'table-responsive'tr>",
                                "order": [[ 0, "desc" ]]
                            });*/
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