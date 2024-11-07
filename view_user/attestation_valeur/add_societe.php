<?php
 require '../../scripts/db_connect.php';
 session_start();
if (isset($_GET['id'])) {
    $id_data_cc=$_GET['id'];
    $options = '';
    $query = "SELECT * FROM substance2";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['id_substance'] . "'>" . $row['nom_substance'] . "</option>";
    }
}
?>
<!-- Formulaire add_commune -->
<div class="modal fade" id="staticBackdrop_societe" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter nouvelle sociéte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="./insert_societe.php" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="id_data_cc" name="id_data_cc"
                        value="<?php echo $id_data_cc ?>" required>
                    <div id="dynamic-fields">
                        <div class="row">
                            <div class="col">
                                <label for="nom_substance" class="fw-bold">Nom de la substance<span
                                        class="required">*</span></label>
                                <select id="nom_substance" name="nom_substance[]" required>
                                    <option value="">Sélectionner...</option>
                                    <?= $options; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="poids" class="fw-bold">Poids à envoyer<span
                                        class="required">*</span></label>
                                <input type="number" class="form-control" step="0.01" name="poids[]" id="poids"
                                    placeholder="Poids" required style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="unite" class="fw-bold">Unité<span class="required">*</span></label>
                                <select id="unite" class="form-select" name="unite[]" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="numero_lp" name="numero_lp" class="col-form-label">Numéro LP:</label>
                            <input type="text" class="form-control" name="numero_lp" id="numero_lp"
                                style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="scan_lp" name="scan_lp" class="col-form-label">Scan LP:</label>
                            <input type="file" class="form-control" name="scan_lp" id="scan_lp" accept=".pdf"
                                style="font-size:90%">
                        </div>
                    </div>
                    <!-- Boutons Add et Less -->
                    <div class="mt-3">
                        <button type="button" id="add-field" class="btn btn-sm btn-success">+</button>
                        <button type="button" id="remove-field" class="btn btn-sm btn-danger">-</button>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <!-- Ajoutez ici d'autres boutons si nécessaire -->
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>

</script>
<script>
$(document).ready(function() {
    var selectOptions = `<?= $options; ?>`;

    // Initialisation de TomSelect sur le champ existant
    new TomSelect("#nom_substance", {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
    // Ajouter un nouveau groupe de champs avec TomSelect
    $('#add-field').click(function() {
        var newRow = $(`
            <div class="row mt-3">
                <div class="col">
                    <label for="nom_substance" class="fw-bold">Nom de la substance<span class="required">*</span></label>
                    <select class="nom_substance" name="nom_substance[]" required>
                        <option value="">Sélectionner...</option>
                        ${selectOptions}
                    </select>
                </div>
                <div class="col">
                    <label for="poids" class="fw-bold">Poids à envoyer<span class="required">*</span></label>
                    <input type="number" class="form-control" step="0.01" name="poids[]" placeholder="Poids" required style="font-size:90%">
                </div>
                <div class="col">
                    <label for="unite" class="fw-bold">Unité<span class="required">*</span></label>
                    <select class="form-select" name="unite[]" required>
                        <option value="">Sélectionner...</option>
                        <option value="kg">kg</option>
                        <option value="g">g</option>
                    </select>
                </div>
            </div>
        `);

        $('#dynamic-fields').append(newRow);

        // Initialiser TomSelect sur le nouveau champ ajouté
        new TomSelect(newRow.find('.nom_substance')[0], {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });

    // Supprimer le dernier groupe de champs
    $('#remove-field').click(function() {
        $('#dynamic-fields .row').last().remove();
    });
});
</script>