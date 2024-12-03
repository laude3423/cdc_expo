<?php
include_once('../../scripts/db_connect.php');
require(__DIR__ . '/../../scripts/session.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql = "SELECT * FROM data_cc
        WHERE id_data_cc = $id_data_cc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        $num_dom= $row['num_domiciliation'];
        $date_dom = $row['date_dom'];
        $id_societe_importateur= $row['id_societe_importateur'];
    }
?>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier une attestation de domiciliation</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="./update_cc.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="numero_dom" name="numero_dom" class="col-form-label">Numéro de
                            domiciliation:</label>
                        <input type="text" class="form-control" name="numero_dom" id="numero_dom"
                            placeholder="Numéro de l'attestation de domiciliation" value="<?php echo $num_dom ?>"
                            required style="font-size:90%">
                    </div>
                    <input type="hidden" id="id_data_cc" name="id_data_cc" value="<?php echo $id_data_cc?>">
                    <div class="mb-3">
                        <label for="date_dom" name="date_dom" class="col-form-label">Date:</label>
                        <input type="date" class="form-control" value="<?php echo $date_dom ?>" name="date_dom"
                            id="date_dom" required style="font-size:90%">
                    </div>
                    <div class="mb-3">
                        <label for="pj_dom" name="pj_dom" class="col-form-label">Scan de l'attestation:</label>
                        <input type="file" class="form-control" name="pj_dom" id="pj_dom" style="font-size:90%"
                            accept=".pdf">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>