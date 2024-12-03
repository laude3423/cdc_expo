<!-- Inclure jQuery (Tom-select nécessite jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<style>
.container {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.btn {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.dropdown-item {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.form-control {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.form-select {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.h4 {
    font-size: 20px;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.modal {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.modal-dialog {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}
</style>
<!-- Formulaire add_commune -->
<div class="modal" tabindex="-1" role="dialog" id="edit_facture">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier une facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="scripts_facture/update_facture.php" enctype="multipart/form-data">
                    <input type="hidden" class="form-control" id="id_data_cc" name="id_data_cc" required>
                    <div class="mb-3">
                        <label for="num_facture_edit" class="fw-bold">Numéro du facture:</label>
                        <input type="text" class="form-control" id="num_facture_edit" name="num_facture_edit" required>
                        <div id="error_message" style="color: red; display: none;">Le numéro de facture ne doit pas
                            commencer par "N°" ou "n°".</div>
                    </div>
                    <div class="mb-3">
                        <label for="date_facture_edit" class="fw-bold">Date du facture:</label>
                        <input type="date" class="form-control" id="date_facture_edit" name="date_facture_edit"
                            required>
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
                            
                            // Récupérer les types de substance depuis la base de données
                            $query = "SELECT * FROM societe_expediteur";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id_societe_expediteur'] . "'>" . $row['nom_societe_expediteur'] . "</option>";
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
                            
                            // Rcuprer les types de substance depuis la base de données
                            $query = "SELECT * FROM societe_importateur";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id_societe_importateur'] . "'>" . $row['nom_societe_importateur'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_facture" name="pj_facture" class="col-form-label">Pièce joint de
                                de la facture:</label>
                            <input type="file" class="form-control" name="pj_facture" id="pj_facture"
                                placeholder="scan de la facture" style="font-size:90%">
                        </div>
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

document.getElementById('pj_facture').addEventListener('change', validatePDFInput);

document.getElementById("num_facture").addEventListener("input", function() {
    const factureInput = document.getElementById("num_facture");
    const errorMessage = document.getElementById("error_message");
    const value = factureInput.value.trim();

    if (/^(N°|n°)/.test(value)) {
        errorMessage.style.display = "block";
        factureInput.setCustomValidity("Le numéro de facture ne doit pas commencer par 'N°' ou 'n°'.");
    } else {
        errorMessage.style.display = "none";
        factureInput.setCustomValidity("");
    }
});
</script>