<?php
include_once('../../../scripts/db_connect.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql = "SELECT catt.*,dcc.num_attestation, dcc.date_attestation, dcc.id_societe_expediteur,dcc.id_societe_importateur FROM contenu_attestation AS catt 
        LEFT JOIN data_cc AS dcc ON dcc.id_data_cc = catt.id_data_cc WHERE  dcc.id_data_cc=$id_data_cc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        $numero_attestation=$row['num_attestation'];
        $numero_date=$row['date_attestation'];
        $id_societe_expediteur= $row['id_societe_expediteur'];
        $id_societe_importateur= $row['id_societe_importateur'];
    }
?>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouveau PV de controle</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="./generate_pv/insert_pv.php" method="post" enctype="multipart/form-data">
                    <div class="row" style="display: none;">
                        <div class="col">
                            <label for="expediteur" name="expediteur" class="col-form-label">Société
                                expéditeur:</label>
                            <select id="expediteur" name="expediteur" placeholder="Choisir ..." class="form-control"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT * FROM societe_expediteur";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        $selected = ($rowSub["id_societe_expediteur"] == $id_societe_expediteur) ? "selected" : "";
                                        echo "<option value='" . $rowSub['id_societe_expediteur'] ."'$selected>". $rowSub['nom_societe_expediteur'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="importateur" name="importateur" class="col-form-label">Société
                                importateur:</label>
                            <select id="importateur" name="importateur" placeholder="Choisir ..." class="form-control"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT * FROM societe_importateur";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        $selected = ($rowSub["id_societe_importateur"] == $id_societe_importateur) ? "selected" : "";
                                        echo "<option value='" . $rowSub['id_societe_importateur'] ."'$selected>". $rowSub['nom_societe_importateur'] . "</option>";
                                    }
                                   
                                    ?>
                            </select>
                        </div>
                        <input type="date" class="form-control" name="date_fiche" id="date_fiche" required
                            style="font-size:90%" readOnly>
                        <input type="text" class="form-control" name="numero_fiche" id="numero_fiche" required
                            style="font-size:90%" readOnly>
                        <input type="text" class="form-control" name="numero_attestation"
                            value="<?php echo $numero_attestation; ?>" id="numero_attestation" required
                            style="font-size:90%" readOnly>
                        <input type="text" class="form-control" name="date_attestation"
                            value="<?php echo $date_attestation; ?>" id="date_attestation" required
                            style="font-size:90%" readOnly>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="id_fiche_controle" name="id_fiche_controle" class="col-form-label">Numéro de
                                la fiche de contrôle:</label>
                            <select id="id_fiche_controle" name="id_fiche_controle" placeholder="Choisir ..."
                                class="form-select" required style="font-size:90%" onchange="updateFlightDetails()">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT * FROM ancien_lp WHERE type_lp='FDC' AND validation_lp='Validé'";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                     while ($rowSub = $resu->fetch_assoc()) {
                                        echo "<option value='" . $rowSub['id_ancien_lp'] . "' data-numero='" . $rowSub['numero_lp'] . "' data-date='" . $rowSub['date_creation'] ."'>" . $rowSub['numero_lp'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="mode_emballage" name="mode_emballage" class="col-form-label">Nombre et mode
                                d'emballage:</label>
                            <input type="text" class="form-control" name="mode_emballage" id="mode_emballage"
                                placeholder="Nombre et mode d'emballage" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_controle" name="lieu_controle" class="col-form-label">Lieu de
                                controle:</label>
                            <input type="text" class="form-control" name="lieu_controle" id="lieu_controle"
                                placeholder="Lieu de controle" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="lieu_emb" name="lieu_emb" class="col-form-label">Lieu
                                d'embarquement:</label>
                            <input type="text" class="form-control" name="lieu_emb" id="lieu_emb"
                                placeholder="Lieu d'embarquement" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_empotage" name="lieu_empotage" class="col-form-label">Lieu
                                d'empotage:</label>
                            <input type="text" placeholder="Lieu d'empotage" class="form-control" name="lieu_empotage"
                                id="lieu_empotage" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="declaration" name="declaration" class="col-form-label">Numéro de fiche de
                                déclaration:</label>
                            <input type="text" class="form-control" name="declaration" id="declaration"
                                placeholder="Numéro de fiche de déclaration" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_declaration" name="pj_declaration" class="col-form-label">Pièce joint de
                                fiche de
                                déclaration:</label>
                            <input type="file" class="form-control" name="pj_declaration" id="pj_declaration"
                                placeholder="Numéro de fiche de déclaration" required style="font-size:90%"
                                accept=".pdf">
                        </div>
                        <div class="col">
                            <label for="date_declaration" name="date_declaration" class="col-form-label">Date de
                                fiche de déclaration:</label>
                            <input type="date" class="form-control" name="date_declaration" id="date_declaration"
                                required style="font-size:90%">
                            <div id="date_error2" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                            <input type="hidden" id="id_data_cc" value="<?php echo $id_data_cc; ?>" name="id_data_cc">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_demande_autorisation" name="pj_demande_autorisation"
                                class="col-form-label">Scan de
                                demande d'autorisation:</label>
                            <input type="file" class="form-control" name="pj_demande_autorisation"
                                id="pj_demande_autorisation" style="font-size:90%" accept=".pdf">
                        </div>
                        <div class="col">
                            <label for="date_demande_autorisation" name="date_demande_autorisation"
                                class="col-form-label">Date:</label>
                            <input type="date" class="form-control" name="date_demande_autorisation"
                                id="date_demande_autorisation" placeholder="Date" required style="font-size:90%">
                            <div id="date_error3" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_engagement" name="pj_engagement" class="col-form-label">Scan de
                                l'engagement de responsabilité:</label>
                            <input type="file" class="form-control" name="pj_engagement" id="pj_engagement"
                                style="font-size:90%" accept=".pdf">
                        </div>
                        <div class="col">
                            <label for="date_engagement" name="date_engagement" class="col-form-label">Date:</label>
                            <input type="date" class="form-control" name="date_engagement" id="date_engagement"
                                placeholder="Date" required style="font-size:90%">
                            <div id="date_error4" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                        </div>
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
<script>
$(document).ready(function() {

});


document.getElementById('date_declaration').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error2');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
    }
});
document.getElementById('date_demande_autorisation').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error3');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
    }
});
document.getElementById('date_engagement').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error4');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
    }
});

function isValidDate(dateString) {
    const date = new Date(dateString);
    const timestamp = date.getTime();

    if (typeof timestamp !== 'number' || Number.isNaN(timestamp)) {
        return false;
    }

    return dateString === date.toISOString().split('T')[0];
}

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

document.getElementById('pj_demande_autorisation').addEventListener('change', validatePDFInput);
document.getElementById('pj_declaration').addEventListener('change', validatePDFInput);
document.getElementById('pj_engagement').addEventListener('change', validatePDFInput);

function updateFlightDetails() {
    var selectVol = document.getElementById('id_fiche_controle');
    var date_fiche = document.getElementById('date_fiche');
    var numero_fiche = document.getElementById('numero_fiche');

    var selectedOption = selectVol.options[selectVol.selectedIndex];
    var date = selectedOption.getAttribute('data-date');
    var numero = selectedOption.getAttribute('data-numero');

    // Mettre à jour les champs compagnie et escale
    date_fiche.value = date;
    numero_fiche.value = numero;
}
</script>