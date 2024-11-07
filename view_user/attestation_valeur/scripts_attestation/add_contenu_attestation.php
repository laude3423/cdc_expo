<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- Inclure jQuery (Tom-select nécessite jQuery) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
.container {
    font-size: small;
    /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
}

.hidden {
    display: none;
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function actualisation() {
    var id = $('#id_attestation').val();
    $.ajax({
        url: './scripts_attestation/get_table_info.php',
        method: 'GET',
        data: {
            id: id
        },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                var rowCount = data.row_count;
                var totalWeight = data.total_weight;
                var unite = data.unite;
                var modalTitle = 'Ajouter un contenu (' + rowCount +
                    ' lignes, Poids total: ' + totalWeight + unite + ')';
                $('.modal-title').text(modalTitle);
            } catch (e) {
                console.error("Erreur lors de l'analyse de la réponse JSON: ", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur AJAX: ", xhr.responseText);
            alert('Une erreur est survenue lors de la récupération des informations.');
        }
    });
}
$(document).ready(function() {
    var id = $('#id_attestation').val();
    $('#add_contenu_attestation').on('shown.bs.modal', function() {
        $.ajax({
            url: './scripts_attestation/get_table_info.php',
            method: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    var rowCount = data.row_count;
                    var totalWeight = data.total_weight;
                    var unite = data.unite;
                    var modalTitle = 'Ajouter un contenu (' + rowCount +
                        ' lignes, Poids total: ' + totalWeight + unite + ')';
                    $('.modal-title').text(modalTitle);
                } catch (e) {
                    console.error("Erreur lors de l'analyse de la réponse JSON: ", e);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX: ", xhr.responseText);
                alert('Une erreur est survenue lors de la récupération des informations.');
            }
        });
    });
    $('#label-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission

        // Check prix unitaire first
        $.ajax({
            url: 'scripts_attestation/insert_contenu_attestation.php',
            method: 'POST',
            data: $('#label-form').serialize(),
            success: function(response) {
                console.log('Réponse brute du serveur:',
                    response);
                if (response.success) {
                    $('#label-form')[0].reset();
                    alert(response.message);
                } else {
                    alert("Erreur: " + response.message);
                }
                actualisation();
            },
            error: function(xhr, status, error) {
                console.error('Statut de l\'erreur:',
                    status);
                console.error('Erreur:', error);
                console.error('Réponse:', xhr.responseText);
                $('#message').html(
                    '<div class="alert alert-danger">Erreur lors de l\'enregistrement des données: ' +
                    error + '</div>').show();
            }
        });
    });
});
</script>
<?php
$id_lp1_existe='nouveau';
// Connexion à la base de donnes
    require '../../scripts/db_connect.php';

?>

<!-- Formulaire add_commune -->
<div class="modal fade" id="add_contenu_attestation" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div id="message" style="display: none;"></div>
                <form method="post" id="label-form" action="">
                    <div class="row">
                        <input type="text" class="form-control" name="id_attestation"
                            value="<?php echo $id_attestation; ?>" id="id_attestation" required style="font-size:90%">
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_substance" class="fw-bold">Designation:</label>
                                <select class="form-select" id="id_substance" name="id_substance" required>
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    $query = "SELECT DISTINCT sub.* FROM contenu_attestation AS catt 
                                    INNER JOIN substance2 AS sub ON catt.id_substance=sub.id_substance 
                                    WHERE catt.id_attestation = ? AND catt.qte_actuel > 0";

                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $id_attestation);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($row['id_substance'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['nom_substance'], ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                    $stmt->close();
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="poids" class="fw-bold">Poids:</label>
                                <input type="number" class="form-control" name="poids" id="poids" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                        <!-- Ajoutez ici d'autres boutons si nécessaire -->
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>

</script>