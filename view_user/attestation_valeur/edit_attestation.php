<?php
require_once('../../scripts/db_connect.php');
if (isset($_GET['id'])) {
    $id=$_GET['id'];
 $sql = "SELECT att.*, dcc.id_data_cc, dcc.id_societe_importateur, dcc.id_societe_expediteur FROM contenu_attestation AS catt
        LEFT JOIN attestation AS att ON catt.id_attestation = att.id_attestation
        LEFT JOIN data_cc AS dcc ON  dcc.id_data_cc = catt.id_data_cc WHERE att.id_attestation=$id";
        //
$stmt = $conn->prepare($sql);
$stmt->execute();
$resu = $stmt->get_result();
$row = $resu->fetch_assoc();

$id_societe_expediteur = $row['id_societe_expediteur'];
$id_societe_importateur = $row['id_societe_importateur'];
}
?>
<?php
// Récupération des options du select
$options = '';
$query = "SELECT * FROM substance2";
$result = $conn->query($query);
while ($row3 = $result->fetch_assoc()) {
    $options .= "<option value='" . $row3['id_substance'] . "'>" . $row3['nom_substance'] . "</option>";
}
?>



<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier une attestation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="scripts_attestation/update_attestation.php" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" value="<?php echo $row['id_attestation']; ?>"
                        id="id_attestation" name="id_attestation" required>
                    <input type="hidden" class="form-control" value="<?php echo $row['id_data_cc']; ?>" id="data_cc"
                        name="data_cc" required>
                    <div class="mb-3">
                        <label for="num_attestation_edit" class="fw-bold">Numéro de l'attestation<span
                                class="required">*</span></label>
                        <input type="text" class="form-control" value="<?php echo $row['num_attestation']; ?>"
                            id="num_attestation_edit" name="num_attestation_edit" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_attestation_edit" class="fw-bold">Date de l'attestation<span
                                class="required">*</span></label>
                        <input type="date" class="form-control" value="<?php echo $row['date_attestation']; ?>"
                            id="date_attestation_edit" name="date_attestation_edit" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_attestation" name="pj_attestation" class="col-form-label">Pièce joint de
                                de la attestation:</label>
                            <input type="file" class="form-control" name="pj_attestation" id="pj_attestation"
                                accept='.pdf' placeholder="scan de la attestation" accept=".pdf" style="font-size:90%">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="id_societe_expediteur_edit" class="fw-bold ">Societé expediteur: </label>
                        <select class="form-select" id="id_societe_expediteur_edit" name="id_societe_expediteur_edit"
                            autocomplete="off" required>
                            <option value="">Sélectionner...</option>
                            <!-- Remplir les options en récupérant les types de substance depuis la base de données -->
                            <?php
                            // Connexion à la base de données
                            require '../../scripts/db_connect.php';
                            
                            $query = "SELECT * FROM societe_expediteur";
                            $resu = $conn->query($query);
                             while ($rowSub = $resu->fetch_assoc()) {
                                        $selected = ($rowSub["id_societe_expediteur"] == $id_societe_expediteur) ? "selected" : "";
                                        echo "<option value='" . $rowSub['id_societe_expediteur'] ."'$selected>". $rowSub['nom_societe_expediteur'] . "</option>";
                                    }
                            ?>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="id_societe_importateur_edit" class="fw-bold ">Societé importateur: </label>
                        <select class="form-select" id="id_societe_importateur_edit" name="id_societe_importateur_edit"
                            required>
                            <option value="">Sélectionner...</option>
                            <!-- Remplir les options en récuprant les types de substance depuis la base de donnes -->
                            <?php
                            // Connexion à la base de donnes
                            require '../../scripts/db_connect.php';
                            $query = "SELECT * FROM societe_importateur";
                            $result = $conn->query($query);
                            while ($rowSub = $result->fetch_assoc()) {
                                $selected = ($rowSub["id_societe_importateur"] == $id_societe_importateur) ? "selected" : "";
                                echo "<option value='" . $rowSub['id_societe_importateur'] ."'$selected>". $rowSub['nom_societe_importateur'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
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

document.getElementById('pj_attestation').addEventListener('change', validatePDFInput);
</script>