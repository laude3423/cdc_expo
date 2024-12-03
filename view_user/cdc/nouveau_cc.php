<?php
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
    }
?>
<div class="modal fade" id="staticBackdrop_cc" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajouter une attestation de domiciliation</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="../cdc/traitement_cc.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="numero_dom" name="numero_dom" class="col-form-label">Numéro de
                            domiciliation:</label>
                        <input type="text" class="form-control" name="numero_dom" id="numero_dom"
                            placeholder="Numéro de l'attestation de domiciliation" required style="font-size:90%">
                    </div>
                    <input type="hidden" id="id_data_cc" name="id_data_cc" value="<?php echo $id_data_cc?>">
                    <div class="mb-3">
                        <label for="date_dom" name="date_dom" class="col-form-label">Date:</label>
                        <input type="date" class="form-control" name="date_dom" id="date_dom" required
                            style="font-size:90%">
                    </div>
                    <div class="mb-3">
                        <label for="pj_domciliation" name="pj_domciliation" class="col-form-label">Scan de
                            l'attestation:</label>
                        <input type="file" class="form-control" name="pj_domciliation" id="pj_domciliation"
                            style="font-size:90%" accept=".pdf" required>
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