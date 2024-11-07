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
        $droit_conformite = $row['droit_conformite'];
        $num_ov =$row['num_ov'];
        $num_quittance =$row['num_quittance'];
        $date_ov =$row['date_ov'];
        $date_quittance =$row['date_quittance'];
        $redevance = $row['redevance'];
        $ristourne = $row['ristourne'];
        $description = $row['description'];

        // $sql1 = "SELECT unite_poids_facture, sum(poids_facture) AS somme FROM contenu_facture WHERE id_data_cc= $id_data_cc";
        // $stmt2 = $conn->prepare($sql1);
        // $stmt2->execute();
        // $resu = $stmt2->get_result();
        // $row2 = $resu->fetch_assoc();
        // $somme= $row2['somme'];
        // $unite_poids_facture = $row2['unite_poids_facture'];
        // $redevance = $assiette * $somme * 0.006; // 0.6%
        // $ristourne = $assiette * $somme * 0.014; // 1.4%

    }
    
 ?>
<style>
.required {
    color: red;
}
</style>
<div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Insérer scan des PV</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="../pv_controle_gu/update_scan.php" method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col">
                            <label for="redevance" name="redevance" class="col-form-label">Total redevance<span
                                    class="required">*</span></label>
                            <input type="number" class="form-control" name="redevance" step="any" id="redevance"
                                value="<?php echo $redevance; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="ristourne" name="ristourne" class="col-form-label">Total de ristourne<span
                                    class="required">*</span></label>
                            <input type="number" class="form-control" name="ristourne" id="ristourne" step="any"
                                value="<?php echo $ristourne; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="droit_conformite" name="droit_conformite" class="col-form-label">Droit de
                                conformité<span class="required">*</span></label>
                            <input type="number" class="form-control" name="droit_conformite" id="droit_conformite"
                                value="<?php echo $droit_conformite; ?>" step="any" required style="font-size:90%">
                        </div>
                    </div>
                    <div>
                        <label for="description">Description du calcul de redevance et ristourne:</label>
                        <textarea id="description" class="form-control" value="<?php echo $description; ?>"
                            name="description" rows="4" cols="50" required></textarea>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label for="num_ov" name="num_ov" class="col-form-label">N° des trois OV séparés par
                                un tiret(sans éspace)<span class="required">*</span></label>
                            <input type="text" value="<?php echo $num_ov; ?>" class="form-control" name="num_ov"
                                id="num_ov" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="date_ov" name="date_ov" class="col-form-label">Date de l'O.V<span
                                    class="required">*</span></label>
                            <input type="date" value="<?php echo $date_ov; ?>" class="form-control" name="date_ov"
                                id="date_ov" required style="font-size:90%">
                            <div id="date_error1" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="scan_ov_rdv" name="scan_ov_rdv" class="col-form-label">Scan de l'O.V
                                signé(redevance):</label>
                            <input type="file" class="form-control" name="scan_ov_rdv" id="scan_ov_rdv" accept=".pdf"
                                style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="scan_ov_ris" name="scan_ov_ris" class="col-form-label">Scan de l'O.V
                                signé(ristourne):</label>
                            <input type="file" class="form-control" name="scan_ov_ris" id="scan_ov_ris" accept=".pdf"
                                style="font-size:90%">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label for="num_quittance" name="num_quittance" class="col-form-label">N° des trois
                                quittances séparés par un tiret(sans éspace)<span class="required">*</span></label>
                            <input type="text" class="form-control" value="<?php echo $num_quittance; ?>"
                                name="num_quittance" id="num_quittance" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="date_quittance" name="date_quittance" class="col-form-label">Date de la
                                quittance
                                trésor<span class="required">*</span></label>
                            <input type="date" class="form-control" value="<?php echo $date_quittance; ?>"
                                name="date_quittance" id="date_quittance" required style="font-size:90%">
                            <div id="date_error2" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="scan_quittance_rdv" name="scan_quittance_rdv" class="col-form-label">Scan de
                                la
                                quittance(redevance):</label>
                            <input type="file" class="form-control" name="scan_quittance_rdv" id="scan_quittance_rdv"
                                accept=".pdf" style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="scan_quittance_ris" name="scan_quittance_ris" class="col-form-label">Scan de
                                la
                                quittance(ristourne):</label>
                            <input type="file" class="form-control" name="scan_quittance_ris" id="scan_quittance_ris"
                                accept=".pdf" style="font-size:90%">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label for="scan_ov_droit" name="scan_ov_droit" class="col-form-label">Scan de l'O.V
                                signé(droit de conformité):</label>
                            <input type="file" class="form-control" name="scan_ov_droit" id="scan_ov_droit"
                                accept=".pdf" style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="scan_quittance_droit" name="scan_quittance_droit" class="col-form-label">Scan de
                                la
                                quittance(droit de conformité):</label>
                            <input type="file" class="form-control" name="scan_quittance_droit"
                                id="scan_quittance_droit" accept=".pdf" style="font-size:90%">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="scan_controle" name="scan_controle" class="col-form-label">Scan de PV de
                            contrôle:</label>
                        <input type="file" class="form-control" name="scan_controle" id="scan_controle" accept=".pdf"
                            style="font-size:90%">
                    </div>
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" id="id_data" name="id_data">
                    <input type="hidden" value="<?php echo $id_pv_scellage; ?>" id="num_pv_scellage"
                        name="num_pv_scellage">
                    <input type="hidden" value="<?php echo $id_pv_controle; ?>" id="num_pv_controle"
                        name="num_pv_controle">
                    <?php if($groupeID===3) {?>
                    <div class="mb-3">
                        <label for="scan_scellage" name="scan_scellage" class="col-form-label">Scan de PV de
                            scellage:</label>
                        <input type="file" class="form-control" name="scan_scellage" id="scan_scellage" accept=".pdf"
                            placeholder="Préom de l'agent" style="font-size:90%">
                    </div>
                    <?php }?>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Function to calculate redevance and ristourne
    // function calculateValues() {
    //     var assiette = parseFloat($("#assiette").val());
    //     var quantite = parseFloat($("#sommeTotal").val());

    //     if (!isNaN(assiette) && !isNaN(quantite)) {
    //         var redevance = assiette * quantite * 0.006; // 0.6%
    //         var ristourne = assiette * quantite * 0.014; // 1.4%

    //         $("#redevance").val(redevance.toFixed(2));
    //         $("#ristourne").val(ristourne.toFixed(2));
    //     }
    // }

    // // Trigger calculation when either assiette_rrm or quantite_en_chiffre changes
    // $("#assiette, #sommeTotal").on("input", calculateValues);
});
document.getElementById('date_ov').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error1');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
    }
});
document.getElementById('date_quittance').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error2');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
    }
});

function validatePDFInput(event) {
    var fileInput = event.target;
    var filePath = fileInput.value;
    var allowedExtension = /(\.pdf)$/i;

    if (!allowedExtension.exec(filePath)) {
        alert('Veuillez choisir un fichier PDF.');
        fileInput.value = '';
        return false;
    }
}

document.getElementById('scan_ov').addEventListener('change', validatePDFInput);
document.getElementById('scan_quittance').addEventListener('change', validatePDFInput);
document.getElementById('scan_controle').addEventListener('change', validatePDFInput);
document.getElementById('scan_scellage').addEventListener('change', validatePDFInput);
</script>