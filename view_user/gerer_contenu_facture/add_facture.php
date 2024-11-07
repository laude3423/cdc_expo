<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

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
<div class="modal" tabindex="-1" role="dialog" id="add_facture">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="scripts_facture/insert_facture.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="num_facture" class="fw-bold">Numéro du facture:</label>
                        <input type="text" class="form-control" id="num_facture" name="num_facture" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_facture" class="fw-bold">Date du facture:</label>
                        <input type="date" class="form-control" id="date_facture" name="date_facture" required>
                        <div id="date_error" style="color: red; display: none;">Veuillez entrer une date valide.</div>
                    </div>
                    <div class="mb-3">
                        <label for="id_societe_expediteur" class="fw-bold ">Societé expediteur: </label>
                        <select id="id_societe_expediteur" name="id_societe_expediteur" autocomplete="off" required>
                            <option value="">Sélectionner...</option>
                            <!-- Remplir les options en récuprant les types de substance depuis la base de donnes -->
                            <?php
                            // Connexion à la base de donnes
                            require '../../scripts/db_connect.php';
                            
                            // Rcuprer les types de substance depuis la base de données
                            $query = "SELECT * FROM `societe_expediteur` WHERE `validation` = 'Validé' ";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id_societe_expediteur'] . "'>" . $row['nom_societe_expediteur'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_societe_importateur" class="fw-bold ">Societé importateur: </label>
                        <select id="id_societe_importateur" name="id_societe_importateur" required>
                            <option value="">Sélectionner...</option>
                            <!-- Remplir les options en récuprant les types de substance depuis la base de donnes -->
                            <?php
                            // Connexion à la base de donnes
                            require '../../scripts/db_connect.php';
                            
                            // Rcuprer les types de substance depuis la base de données
                            $query = "SELECT * FROM societe_importateur WHERE `validation` = 'Validé'";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id_societe_importateur'] . "'>" . $row['nom_societe_importateur'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_facture" name="pj_facture" class="fw-bold ">Pièce joint de
                                la facture:</label>
                            <input type="file" class="form-control" name="pj_facture" id="pj_facture" accept=".pdf"
                                placeholder="scan de la facture" required style="font-size:90%">
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
new TomSelect("#id_societe_expediteur", {
    create: true,
    sortField: {
        field: "text",
        direction: "asc"
    }
});
</script>
<script>
new TomSelect("#id_societe_importateur", {
    create: true,
    sortField: {
        field: "text",
        direction: "asc"
    }
});

document.getElementById('date_facture').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error');
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

document.getElementById('pj_facture').addEventListener('change', validatePDFInput);
</script>