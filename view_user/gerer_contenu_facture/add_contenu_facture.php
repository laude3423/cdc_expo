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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#label-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        $.ajax({
            type: 'POST',
            url: 'check_prix_unitaire.php',
            data: $(this).serialize(), // Send form data
            success: function(response) {
                console.log("Raw response: ", response); // Log the raw response
                try {
                    var data;
                    if (typeof response === 'string') {
                        data = JSON.parse(response.trim());
                    } else {
                        data = response; // Already parsed JSON
                    }
                    console.log("Parsed response: ", data); // Log parsed response
                    if (data.status === 'success') {
                        var prix_unitaire = parseFloat(data.prix_substance);
                        var prix_unitaire_facture = parseFloat($('#prix_unitaire_facture')
                            .val());
                        if (prix_unitaire_facture >= prix_unitaire) {
                            $('#label-form').off('submit').submit(); // Submit the form
                        } else {
                            alert(
                                'Le prix unitaire saisi ne correspond pas au prix unitaire de la base de données:' +
                                prix_unitaire);
                        }
                    } else {
                        alert(data.message);
                    }
                } catch (e) {
                    console.error("JSON parse error: ", e); // Log parsing error
                    alert('Erreur lors du traitement de la réponse JSON.');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error: ", xhr.responseText); // Log AJAX error
                alert('Une erreur est survenue lors de la vérification du prix unitaire.');
            }
        });
    });
});
</script>
<?php
// Connexion à la base de donnes
    require '../../scripts/db_connect.php';

?>

<!-- Formulaire add_commune -->
<div class="modal" tabindex="-1" role="dialog" id="add_contenu_facture">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="label-form" action="scripts_facture/insert_contenu_facture.php">
                    <div class="row">
                        <input type="hidden" class="form-control" name="num_data" value="<?php echo $id_data_cc; ?>"
                            id="num_data" required style="font-size:90%">
                        <div class="col">
                            <div class="mb-3">
                                <label for="typeSubstance" class="fw-bold">Type de la substance :</label>
                                <select class="form-select" id="typeSubstance" name="typeSubstance" required>
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    $query = "SELECT * FROM type_substance";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id_type_substance'] . "'>" . $row['nom_type_substance'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_substance" class="fw-bold">Designation :</label>
                                <select class="form-select" id="id_substance" name="id_substance" required disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_categorie" class="fw-bold">Categorie:</label>
                                <select class="form-select" id="id_categorie" name="id_categorie" required disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unite_poids_facture" class="fw-bold">Unité :</label>
                                <select class="form-select" id="unite_poids_facture" name="unite_poids_facture"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="poids_facture" class="fw-bold">Poids :</label>
                                <input type="number" class="form-control" id="poids_facture" name="poids_facture"
                                    step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prix_unitaire_facture" class="fw-bold">Prix unitaire en US $/Unité:</label>
                                <input type="number" class="form-control" id="prix_unitaire_facture"
                                    name="prix_unitaire_facture" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="granulo_facture" class="fw-bold">Granulo:</label>
                                <select class="form-select" id="granulo_facture" name="granulo_facture" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_couleur_substance" class="fw-bold">Couleur:</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_degre_couleur " class="fw-bold">Degré de couleur :</label>
                                <select class="form-select" id="id_degre_couleur" name="id_degre_couleur" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_transparence" class="fw-bold">Transparence:</label>
                                <select class="form-select" id="id_transparence" name="id_transparence" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_durete" class="fw-bold">Dureté :</label>
                                <select class="form-select" id="id_durete" name="id_durete" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_forme_substance" class="fw-bold">Forme :</label>
                                <select class="form-select" id="id_forme_substance" name="id_forme_substance" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_dimension_diametre" class="fw-bold">Dimension ou diametre:</label>
                                <select class="form-select" id="id_dimension_diametre" name="id_dimension_diametre"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="id_lp1_info" class="fw-bold ">Laissez passer I correspondant : </label>
                            <select class="form-select" id="id_lp1_info" name="id_lp1_info" required>
                                <option value="">Sélectionner...</option>
                            </select>
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
$(document).ready(function() {
    // Lorsqu'une option est sélectionne dans le premier menu
    $("#id_categorie").change(function() {
        var id_categorie = $(this).val();
        var id_substance = $('#id_substance').val();
        if ((id_categorie !== "") && (id_substance !== "")) {
            $("#id_couleur_substance").prop("disabled", false);
            $("#id_degre_couleur").prop("disabled", false);
            $("#id_dimension_diametre").prop("disabled", false);
            $("#unite_poids_facture").prop("disabled", false);
            $("#granulo_facture").prop("disabled", false);
            $("#id_transparence").prop("disabled", false);
            $("#id_durete").prop("disabled", false);
            $("#id_forme_substance").prop("disabled", false);
            // Activer le deuxième menu déroulant
            $("#id_couleur_substance").prop("disabled", false);
            $("id_lp1_info").prop("disabled", false);
            // Charger les districts en fonction de la région sélectionnée
            $.ajax({
                url: "scripts_facture/get_couleur.php",
                method: "POST",
                data: {
                    id_substance: id_substance,
                    id_categorie: id_categorie
                },
                dataType: "json",
                success: function(data) {
                    const dropdowns = [{
                            id: "#id_dimension_diametre",
                            options: data.options_dimension_diametre,
                            emptyMessage: "Aucune dimension ou diamètre..."
                        },
                        {
                            id: "#id_couleur_substance",
                            options: data.options_couleur,
                            emptyMessage: "Aucune couleur..."
                        },
                        {
                            id: "#id_degre_couleur",
                            options: data.options_degre_couleur,
                            emptyMessage: "Aucun degré de couleur..."
                        },
                        {
                            id: "#id_durete",
                            options: data.options_durete,
                            emptyMessage: "Aucune dureté..."
                        },
                        {
                            id: "#unite_poids_facture",
                            options: data.options_unite_poids_facture,
                            emptyMessage: "Sélectionner d'abord une unité de poids..."
                        },
                        {
                            id: "#granulo_facture",
                            options: data.options_granulo,
                            emptyMessage: "Aucune granulométrie..."
                        },
                        {
                            id: "#id_transparence",
                            options: data.options_transparence,
                            emptyMessage: "Aucune transparence..."
                        },
                        {
                            id: "#id_forme_substance",
                            options: data.options_forme_substance,
                            emptyMessage: "Aucune forme de substance..."
                        },
                        {
                            id: "#unite_poids_facture",
                            options: data.options_unite,
                            emptyMessage: "Aucune unité..."
                        },
                        {
                            id: "#id_lp1_info",
                            options: data.options_lp1,
                            emptyMessage: "Aucune substance..."
                        }
                    ];

                    dropdowns.forEach(dropdown => {
                        if (dropdown.options ===
                            "<option value=''>Sélectionner...</option>") {
                            $(dropdown.id).prop("disabled", true).html(
                                `<option value=''>${dropdown.emptyMessage}</option>`
                            );
                        } else {
                            $(dropdown.id).prop("disabled", false).html(dropdown
                                .options);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.log("An error occurred:", error);
                    console.log("Response text:", xhr.responseText);
                }
            });

        } else {
            // Désactiver et réinitialiser le deuxième menu déroulant
            $("#id_couleur_substance").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_degre_couleur").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_dimension_diametre").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#unite_poids_facture").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#granulo_facture").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_transparence").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_durete").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_forme_substance").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_lp1_info").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");

        }
    });
});
document.getElementById('typeSubstance').addEventListener('change', function() {
    var typeSubstanceId = this.value;
    var substanceSelect = document.getElementById('id_substance');

    // Enable the substance select field if a type is selected
    if (typeSubstanceId) {
        substanceSelect.disabled = false;
    } else {
        substanceSelect.disabled = true;
    }

    // Clear the previous options
    substanceSelect.innerHTML = '<option value="">Sélectionner...</option>';

    if (typeSubstanceId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'scripts_facture/get_substance.php?typeSubstanceId=' + typeSubstanceId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var substances = JSON.parse(xhr.responseText);
                substances.forEach(function(substance) {
                    var option = document.createElement('option');
                    option.value = substance.id_substance;
                    option.textContent = substance.nom_substance;
                    substanceSelect.appendChild(option);
                });
            }
        };
        xhr.send();
    }
});
document.getElementById('id_substance').addEventListener('change', function() {
    var substanceId = this.value;
    var substanceSelect = document.getElementById('id_categorie');

    // Enable the substance select field if a type is selected
    if (substanceId) {
        substanceSelect.disabled = false;
    } else {
        substanceSelect.disabled = true;
    }

    // Clear the previous options
    substanceSelect.innerHTML = '<option value="">Sélectionner...</option>';

    if (substanceId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'scripts_facture/get_categorie.php?substanceId=' + substanceId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var substances = JSON.parse(xhr.responseText);
                substances.forEach(function(substance) {
                    var option = document.createElement('option');
                    option.value = substance.id_categorie;
                    option.textContent = substance.nom_categorie;
                    substanceSelect.appendChild(option);
                });
            }
        };
    }
    xhr.send();
});
</script>