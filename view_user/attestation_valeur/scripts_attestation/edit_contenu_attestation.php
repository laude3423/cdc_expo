<?php 
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/connect_db_lp1.php');
if (isset($_GET['id'])) {
    $id_contenu_attestation= $_GET['id'];

    $sql = "SELECT cat.*, lp.* FROM contenu_attestation AS cat LEFT JOIN lp_scan AS lp ON
    lp.id_lp_scan = cat.id_lp_scan WHERE cat.id_contenu_attestation = $id_contenu_attestation";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row_1 = $result->fetch_assoc();
        $id_substance = $row_1["id_substance"];
        $poids = $row_1["poids_attestation"];
        $unite = $row_1["unite"];
        $id_data_cc= $row_1["id_data_cc"];
        $numero_lp = $row_1['numero_lp'];
    }
}
?>

<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editer un contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="label-form" action="./scripts_attestation/update_contenu_attestation.php"
                    enctype="multipart/form-data">
                    <div class="row">
                        <input type="hidden" class="form-control" name="num_data" value="<?php echo $id_data_cc; ?>"
                            id="num_data" required style="font-size:90%">
                        <input type="hidden" class="form-control" name="id_contenu"
                            value="<?php echo $id_contenu_attestation; ?>" id="id_contenu" required
                            style="font-size:90%">
                    </div>
                    <div class="mb-3">
                        <label for="id_substance" class="fw-bold">Designation:</label>
                        <select class="form-select" id="id_substance" name="id_substance" required>
                            <option value="">Sélectionner...</option>
                            <?php
                                    $query = "SELECT sub.* FROM contenu_attestation AS catt 
                                    INNER JOIN substance2 AS sub ON catt.id_substance=sub.id_substance 
                                    WHERE catt.id_data_cc = ?";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $id_data_cc);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                     while ($row = $result->fetch_assoc()) {
                                        $selected = ($row["id_substance"] == $id_substance) ? "selected" : "";
                                       echo "<option value='" . $row['id_substance'] . "' " . $selected . ">". $row['nom_substance'] . "</option>";

                                    }
                                    ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="poids" class="fw-bold">Poids:</label>
                        <input type="number" class="form-control" value="<?php echo $poids ?>" name="poids" id="poids"
                            step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <?php
                                // Supposons que $selectedValue contient la valeur récupérée de la base de données.
                                $selectedValue = $unite; // Exemple de valeur
                                function isSelecteder($value, $selectedValue) {
                                    return $value === $selectedValue ? 'selected' : '';
                                }
                                ?>
                        <label for="unite" class="fw-bold">Unité:</label>
                        <select class="form-select" id="unite" name="unite" required>
                            <option value="">Séléctionner</option>
                            <option value="kg" <?= isSelecteder('kg', $selectedValue) ?>>Kg</option>
                            <option value="g" <?= isSelecteder('g', $selectedValue) ?>>g</option>
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="numero_lp" name="numero_lp" class="col-form-label">Numéro LP:</label>
                                <input type="text" class="form-control" value="<?php echo $numero_lp ?>"
                                    name="numero_lp" id="numero_lp" style="font-size:90%">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="scan_lp" name="scan_lp" class="col-form-label">Scan LP:</label>
                                <input type="file" class="form-control" name="scan_lp" id="scan_lp" accept=".pdf"
                                    style="font-size:90%">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo $id_lp ?>" name="id_lp" id="id_lp">
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                        <!-- Ajoutez ici d'autres boutons si nécessaire -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('label-form').addEventListener('submit', function(event) {
    console.log('Formulaire soumis');
});
</script>