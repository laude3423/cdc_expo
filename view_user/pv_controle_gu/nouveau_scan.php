<?php require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql = "SELECT * FROM data_cc WHERE id_data_cc =$id_data_cc";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        $id_pv_controle = $row['num_pv_controle'];
        $id_pv_scellage = $row['num_pv_scellage'];

    }
    
 ?>
<div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Insérer scan des PV</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="./insert_scan.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="scan_controle" name="scan_controle" class="col-form-label">Scan de PV de
                            contrôle:</label>
                        <input type="file" class="form-control" name="scan_controle" id="scan_controle"
                            placeholder="Nom de l'agent" required style="font-size:90%">
                    </div>
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" id="id_data" name="id_data">
                    <input type="hidden" value="<?php echo $id_pv_scellage; ?>" id="num_pv_scellage"
                        name="num_pv_scellage">
                    <input type="hidden" value="<?php echo $id_pv_controle; ?>" id="num_pv_controle"
                        name="num_pv_controle">
                    <div class="mb-3">
                        <label for="scan_scellage" name="scan_scellage" class="col-form-label">Scan de PV de
                            scellage:</label>
                        <input type="file" class="form-control" name="scan_scellage" id="scan_scellage"
                            placeholder="Préom de l'agent" required style="font-size:90%">
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