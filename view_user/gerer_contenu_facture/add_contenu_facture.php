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
<div class="modal" tabindex="-1" role="dialog" id="add_contenu_facture">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="scripts_facture/insert_contenu_facture.php">
                    <div class="row">
                        <input type="hidden" class="form-control" id="id_data_cc" name="id_data_cc" required>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_substance" class="fw-bold">Designation :</label>
                                <select class="form-select" id="id_substance" name="id_substance" required>
                                    <option value="">Sélectionner...</option>
                                    <!-- Remplir les options en récuprant les types de substance depuis la base de donnes -->
                                    <?php
                                // Connexion à la base de donnes
                                require '../../scripts/db_connect.php';
                                
                                // Rcuprer les types de substance depuis la base de données
                                $query = "SELECT * FROM substance";
                                $result = $conn->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id_substance'] . "'>" . $row['nom_substance'] . "</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_couleur_substance" class="fw-bold">Couleur:</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    required>
                                    <option value="">Sélectionner...</option>
                                    <!-- Remplir les options en récuprant les types de substance depuis la base de donnes -->
                                    <?php
                                // Connexion à la base de donnes
                                require '../../scripts/db_connect.php';
                                
                                // Rcuprer les types de substance depuis la base de données
                                $query = "SELECT * FROM couleur_substance";
                                $result = $conn->query($query);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id_couleur_substance'] . "'>" . $row['nom_couleur_substance'] . "</option>";
                                }
                                ?>
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
                                <label for="prix_unitaire_facture" class="fw-bold">Prix unitaire en US $/Unité:</label>
                                <input type="number" class="form-control" id="prix_unitaire_facture"
                                    name="prix_unitaire_facture" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="granulo_facture" class="fw-bold">Granulo:</label>
                                <select class="form-select" id="granulo_facture" name="granulo_facture" required>
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
                                <label for="id_categorie" class="fw-bold">Categorie:</label>
                                <select class="form-select" id="id_categorie" name="id_categorie" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_forme_substance" class="fw-bold">Forme :</label>
                                <select class="form-select" id="id_forme_substance" name="id_forme_substance" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_dimension_diametre" class="fw-bold">Dimension ou diametre:</label>
                                <select class="form-select" id="id_dimension_diametre" name="id_dimension_diametre"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="id_lp1_info" class="fw-bold ">Laissez passer I correspondant : </label>
                        <select id="id_lp1_info" name="id_lp1_info" placeholder="Choisir ..." autocomplete="off"
                            required style="font-size:90%">
                            <option value="">Choisir ...</option>
                            <?php    
                                    require '../../scripts/connect_db_lp1.php';
                                    $query = "SELECT * FROM lp_info";
                                    $result = $conn_lp1->query($query);
                                    
                                    while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id_lp'] ."'>" . $row['num_LP'] . "</option>";
                                        }
                                    ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <!-- Ajoutez ici d'autres boutons si nécessaire -->
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
function selectTom() {
    // Initialisez TomSelect pour chaque élément select
    var selectOptions = {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };

    new TomSelect("#id_lp1_info", selectOptions);;

};
$(document).ready(function() {
    selectTom();
    // Lorsqu'une option est sélectionne dans le premier menu
    $("#id_substance").change(function() {
        var id_substance = $(this).val();
        if (id_substance !== "") {
            $("#id_couleur_substance").prop("disabled", false);
            $("#id_degre_couleur").prop("disabled", false);
            $("#id_dimension_diametre").prop("disabled", false);
            $("#unite_poids_facture").prop("disabled", false);
            $("#granulo_facture").prop("disabled", false);
            $("#id_transparence").prop("disabled", false);
            $("#id_durete").prop("disabled", false);
            $("#id_categorie").prop("disabled", false);
            $("#id_forme_substance").prop("disabled", false);
            // Activer le deuxième menu déroulant
            $("#id_couleur_substance").prop("disabled", false);

            // Charger les districts en fonction de la région sélectionnée
            $.ajax({
                url: "scripts_facture/get_couleur.php",
                method: "POST",
                data: {
                    id_substance: id_substance
                },
                dataType: "json",
                success: function(data) {
                    // Remplir le deuxième menu droulant avec les districts

                    if (data.options_dimension_diametre ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#id_dimension_diametre").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord un substance...</option>"
                        );
                    } else {
                        $("#id_dimension_diametre").prop("disabled", false).html(data
                            .options_dimension_diametre);
                    }
                    // Couleur de substance
                    if (data.options_couleur ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#id_couleur_substance").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une couleur...</option>"
                        );
                    } else {
                        $("#id_couleur_substance").prop("disabled", false).html(data
                            .options_couleur);
                    }

                    // Degré de couleur
                    if (data.options_degre_couleur ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#id_degre_couleur").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord un degré de couleur...</option>"
                        );
                    } else {
                        $("#id_degre_couleur").prop("disabled", false).html(data
                            .options_degre_couleur);
                    }

                    // Dureté
                    if (data.options_durete ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#id_durete").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une dureté...</option>"
                        );
                    } else {
                        $("#id_durete").prop("disabled", false).html(data.options_durete);
                    }

                    // Unité de poids facture
                    if (data.options_unite_poids_facture ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#unite_poids_facture").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une unité de poids...</option>"
                        );
                    } else {
                        $("#unite_poids_facture").prop("disabled", false).html(data
                            .options_unite_poids_facture);
                    }

                    // Granulométrie facture
                    if (data.options_granulo ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#granulo_facture").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une granulométrie...</option>"
                        );
                    } else {
                        $("#granulo_facture").prop("disabled", false).html(data
                            .options_granulo);
                    }

                    // Transparence
                    if (data.options_transparence ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#id_transparence").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une transparence...</option>"
                        );
                    } else {
                        $("#id_transparence").prop("disabled", false).html(data
                            .options_transparence);
                    }

                    // Catégorie
                    if (data.options_categorie ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#id_categorie").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une catégorie...</option>"
                        );
                    } else {
                        $("#id_categorie").prop("disabled", false).html(data
                            .options_categorie);
                    }

                    // Forme de substance
                    if (data.options_forme_substance ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#id_forme_substance").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une forme de substance...</option>"
                        );
                    } else {
                        $("#id_forme_substance").prop("disabled", false).html(data
                            .options_forme_substance);
                    }

                    // Unité substance
                    if (data.options_unite ===
                        "<option value=''>Sélectionner...</option>") {
                        $("#unite_poids_facture").prop("disabled", true).html(
                            "<option value=''>Sélectionner d'abord une forme de substance...</option>"
                        );
                    } else {
                        $("#unite_poids_facture").prop("disabled", false).html(data
                            .options_unite);
                    }


                    // $('#commune_destination').val('');
                },
                error: function(xhr, status, error) {
                    // Gestion de l'erreur
                    console.log("An error occurred:", error);
                    // Relancer le script get_dates_permis.php
                    // $.ajax(this);
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
            $("#id_categorie").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_forme_substance").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");

        }
    });
});
</script>