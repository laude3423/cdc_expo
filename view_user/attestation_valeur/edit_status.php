<?php
require '../../scripts/db_connect.php';
session_start();

if (isset($_GET['id'])) {
    $id_contenu = $_GET['id'];
    
    // Use a prepared statement for the SELECT query
    $sql4 = "SELECT * FROM contenu_attestation WHERE id_contenu_attestation = ?";
    $stmt = $conn->prepare($sql4);
    $stmt->bind_param("i", $id_contenu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row_1 = $result->fetch_assoc();
        $validate = $row_1["validation_contenu"];
    } else {
        echo "Aucun enregistrement trouvé pour cet ID.";
        exit();
    }
}

if (isset($_POST['submit2'])) {
    $validation = $_POST['validation'];
    $id_contenu = $_POST['id_contenu_validation'];
    
    $update_sql = "UPDATE contenu_attestation SET validation_contenu = ? WHERE id_contenu_attestation = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $validation, $id_contenu);

    if($update_stmt->execute()){
        header("Location: ./liste_contenu_attestation.php?id=" . $id_attestation);
        exit();
    }


}
?>

<!-- Formulaire add_commune -->
<div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modification du status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="./scripts_attestation/update_status.php">
                    <?php
                    $selectedValue2 = $validate;
                    function isSelected_status($value, $selectedValue2) {
                        return $value === $selectedValue2 ? 'selected' : '';
                    }
                    ?>
                    <input type="hidden" class="form-control" id="id_contenu_validation" name="id_contenu_validation"
                        value="<?php echo htmlspecialchars($id_contenu, ENT_QUOTES, 'UTF-8'); ?>" required>
                    <input type="hidden" class="form-control" id="id_attestation" name="id_attestation"
                        value="<?php echo htmlspecialchars($id_attestation, ENT_QUOTES, 'UTF-8'); ?>" required>

                    <div class="mb-3">
                        <label for="nom_status" class="fw-bold">Status: </label>
                        <select class="form-select" name="validation" id="validation" required>
                            <option value="">Sélectionner</option>
                            <option value="refaire" <?= isSelected_status('refaire', $selectedValue2) ?>>À Refaire
                            </option>
                            <option value="valide" <?= isSelected_status('valide', $selectedValue2) ?>>Validé</option>
                            <option value="attente" <?= isSelected_status('attente', $selectedValue2) ?>>En attente
                            </option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit2" class="btn btn-sm btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>