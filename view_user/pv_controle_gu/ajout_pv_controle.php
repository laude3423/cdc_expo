<?php
include_once('../../scripts/db_connect.php');
//test

?>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouveau PV de controle</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../pv_controle_gu/traitement.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nombre" name="nombre" class="col-form-label">Nombre et mode d'emballage:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre de colis"
                            required style="font-size:90%">
                    </div>
                    <div class="mb-3">
                        <label for="lieu_controle" name="lieu_controle" class="col-form-label">Lieu de controle:</label>
                        <input type="text" class="form-control" name="lieu_controle" id="lieu_controle"
                            placeholder="Lieu de controle" required style="font-size:90%">
                        <input type="hidden" id="id" name="id" value="<?php echo $id_data_cc; ?>">
                        <input type="hidden" id="id_data" name="id_data">
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