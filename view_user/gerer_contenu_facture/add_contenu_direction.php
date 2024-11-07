<?php 
if (isset($_GET['id'])) {
    $id_data_cc= $_GET['id'];
}
?>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- Inclure jQuery (Tom-select nécessite jQuery) -->

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
<script>
function actualisation() {
    var id = $('#num_data_dire').val();
    $.ajax({
        url: 'get_table_info.php',
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
    var id = $('#num_data_dire').val();
    $('#staticBackdrop3').on('shown.bs.modal', function() {
        $.ajax({
            url: 'get_table_info.php',
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
    $('#label-form_dire').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission

        // Check prix unitaire first
        $.ajax({
            type: 'POST',
            url: 'check_prix_dire.php',
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
                        var prix_unitaire_facture = parseFloat($(
                                '#prix_unitaire_facture_dire')
                            .val());
                        var unite_monetaire = $('#unite_monetaire_dire').val();
                        var taux = parseFloat($('#taux').val());
                        var id_substance = $('#id_substance_dire').val();

                        switch (unite_monetaire) {
                            case 'yen':
                                prix_unitaire_facture *= 0.007;
                                break;
                            case 'euro':
                                prix_unitaire_facture *= 1.08;
                                break;
                            case 'dollar':
                                // Ne rien faire car le prix ne change pas
                                break;
                            default:
                                alert('Unité monétaire non prise en charge');
                                return;
                        }
                        if ((id_substance == 126) || (id_substance == 127)) {
                            if (taux <= 0) {
                                alert('Vuillez compléter le taux!');
                            } else {
                                prix_unitaire = prix_unitaire * (taux / 100);
                                if (prix_unitaire_facture >= prix_unitaire) {
                                    // Now submit the form data to insert_contenu_facture.php
                                    $.ajax({
                                        url: 'scripts_facture/insert_contenu_dire.php',
                                        method: 'POST',
                                        data: $('#label-form_dire').serialize(),
                                        success: function(response) {
                                            console.log(
                                                'Réponse brute du serveur:',
                                                response);
                                            if (response.success) {
                                                $('#label-form_dire')[0]
                                                    .reset();
                                                $('#ancien_lp_dire').val('');
                                                $('#id_lp1_info_dire').val('');
                                                alert(response.message);
                                            } else {
                                                alert("Erreur: " + response
                                                    .message);
                                            }
                                            actualisation();
                                        },
                                        error: function(xhr, status, error) {
                                            console.error(
                                                'Statut de l\'erreur:',
                                                status);
                                            console.error('Erreur:', error);
                                            console.error('Réponse:', xhr
                                                .responseText);
                                            $('#message').html(
                                                '<div class="alert alert-danger">Erreur lors de l\'enregistrement des données: ' +
                                                error + '</div>').show();
                                        }
                                    });

                                } else {
                                    alert(
                                        'Le prix unitaire saisi ne correspond pas au prix unitaire de la base de données: ' +
                                        prix_unitaire + '$'
                                    );
                                }
                            }
                        } else {
                            if (prix_unitaire_facture >= prix_unitaire) {
                                // Now submit the form data to insert_contenu_facture.php
                                $.ajax({
                                    url: 'scripts_facture/insert_contenu_dire.php',
                                    method: 'POST',
                                    data: $('#label-form_dire').serialize(),
                                    success: function(response) {
                                        console.log('Réponse brute du serveur:',
                                            response);
                                        if (response.success) {
                                            $('#label-form_dire')[0].reset();
                                            $('#ancien_lp_dire').val('');
                                            $('#id_lp1_info_dire').val('');
                                            alert(response.message);
                                        } else {
                                            alert("Erreur: " + response
                                                .message);
                                        }
                                        actualisation();
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Statut de l\'erreur:',
                                            status);
                                        console.error('Erreur:', error);
                                        console.error('Réponse:', xhr
                                            .responseText);
                                        $('#message').html(
                                            '<div class="alert alert-danger">Erreur lors de l\'enregistrement des données: ' +
                                            error + '</div>').show();
                                    }
                                });

                            } else {
                                alert(
                                    'Le prix unitaire saisi ne correspond pas au prix unitaire de la base de données: ' +
                                    prix_unitaire + '$'
                                );
                            }
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
$id_lp1_existe='nouveau';
// Connexion à la base de donnes
    require '../../scripts/db_connect.php';

?>

<!-- Formulaire add_commune -->
<div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div id="message" style="display: none;"></div>
                <form method="post" id="label-form_dire" action="">
                    <div class="row">
                        <input type="hidden" class="form-control" name="num_data_dire"
                            value="<?php echo $id_data_cc; ?>" id="num_data_dire" style="font-size:90%">
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_categorie_dire" class="fw-bold">Categorie:</label>
                                <select class="form-select" id="id_categorie_dire" name="id_categorie_dire" required>
                                    <option value="">Sélectionner...</option>
                                    <?php    
                                    $query = "SELECT * FROM categorie WHERE id_categorie IN (1, 2)";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                     while ($rowSub = $resu->fetch_assoc()) {
                                        $nom_categorie = $rowSub['nom_categorie'] === 'Taillée' ? 'Travaillée' : $rowSub['nom_categorie'];
                                        echo "<option value='" . $rowSub['id_categorie'] . "'>" . $nom_categorie . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="famille" class="fw-bold">Famille de la substance:</label>
                                <select class="form-select" id="famille" name="famille" required disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_substance_dire" class="fw-bold">Designation :</label>
                                <select class="form-select" id="id_substance_dire" name="id_substance_dire" required
                                    disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="unite_poids_facture_dire" class="fw-bold">Unité :</label>
                                <select class="form-select" id="unite_poids_facture_dire"
                                    name="unite_poids_facture_dire" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="poids_facture_dire" class="fw-bold">Poids :</label>
                                <input type="number" class="form-control" id="poids_facture_dire"
                                    name="poids_facture_dire" step="0.01" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <div class="col">
                                    <label for="prix_unitaire_facture_dire" class="fw-bold">Prix unitaire:</label>
                                    <input type="number" class="form-control" id="prix_unitaire_facture_dire"
                                        name="prix_unitaire_facture_dire" step="0.01" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="unite_monetaire_dire" class="fw-bold">Unité monétaire</label>
                                <select class="form-select" id="unite_monetaire_dire" name="unite_monetaire_dire"
                                    required>
                                    <option value="">Sélectionner...</option>
                                    <option value="dollar">DOLLAR</option>
                                    <option value="euro">EURO</option>
                                    <option value="yen">YEN</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_transparence_dire" class="fw-bold">Choix qualité:</label>
                                <select class="form-select" id="id_transparence_dire" name="id_transparence_dire"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="granulo_facture_dire" class="fw-bold">Granulo/Calibre:</label>
                                <select class="form-select" id="granulo_facture_dire" name="granulo_facture_dire"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_couleur_substance_dire" class="fw-bold">Couleur:</label>
                                <select class="form-select" id="id_couleur_substance_dire"
                                    name="id_couleur_substance_dire" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_durete_dire" class="fw-bold">Dureté :</label>
                                <select class="form-select" id="id_durete_dire" name="id_durete_dire" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_forme_substance_dire" class="fw-bold">Forme :</label>
                                <select class="form-select" id="id_forme_substance_dire" name="id_forme_substance_dire"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3" id="direction_date_dire">
                        <div class="col">
                            <label for="date_lp1_dire" class="fw-bold">Date de creation de LP :</label>
                            <input type="date" class="form-control" id="date_lp1_dire" name="date_lp1_dire">
                        </div>
                        <div class="col">
                            <label for="id_direction_dire" class="fw-bold">Direction:</label>
                            <select id="id_direction_dire" class="form-control" name="id_direction_dire"
                                onchange="updateFlightDetails()">
                                <option value="">Sélectionner...</option>
                                <?php    
                                    require_once('../../scripts/connect_db_lp1.php');
                                    $query = "SELECT * FROM directions";
                                    $stmt = $conn_lp1->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        echo "<option value='" . $rowSub['id_direction'] . "'>" . $rowSub['nom_direction'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="id_dimension_diametre_dire" class="fw-bold">Dimension ou
                                    diametre:</label>
                                <select class="form-select" id="id_dimension_diametre_dire"
                                    name="id_dimension_diametre_dire" required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col" id="lp1_info_nouveau_dire">
                            <div class="mb-3">
                                <label for="id_lp1_info_dire" class="fw-bold ">Laissez passer I correspondant :
                                </label>
                                <select id="id_lp1_info_dire" class="form-select" name="id_lp1_info_dire">
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col" id="lp1_info_ancien_dire">
                            <label for="ancien_lp_dire" class="fw-bold ">Ancien Laissez passer I: </label>
                            <select id="ancien_lp_dire" name="ancien_lp_dire">
                                <option value="">Sélectionner...</option>
                                <?php    
                                    $query = "SELECT * FROM ancien_lp WHERE validation_lp='Validé' AND expiration IS NULL";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        echo "<option value='" . $rowSub['id_ancien_lp'] . "'>" . $rowSub['numero_lp'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div id="row_taux">
                        <label for="taux" class="fw-bold">Taux de tantale ou cuivre en %:</label>
                        <input type="number" class="form-control" name="taux" id="taux">
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="hidden" id="verified_lp_dire" name="verified_lp_dire"
                                value="<?php echo $id_lp1_existe; ?>" class="form-control">
                            <button type="button" id="btn_autre_lp_dire" class="btn btn-primary">Ancien
                                LP</button>
                            <button type="button" id="btn_annuler_dire" class="btn btn-secondary"
                                style="display: none;">Annuler</button>
                        </div>
                    </div><br>
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
$(document).ready(function() {
    $('#lp1_info_ancien_dire').hide();
    $('#btn_annuler_dire').hide();
    $('#row_taux').hide();
    selectTom3();
    $("#id_categorie_dire").change(function() {
        var id_categorie = $(this).val();
        if (id_categorie !== "") {
            $("famille").prop("disabled", false);
            // Charger les districts en fonction de la région sélectionnée
            $.ajax({
                url: "get_famille.php",
                method: "POST",
                data: {
                    id_categorie: id_categorie,
                },
                dataType: "json",
                success: function(data) {
                    const dropdowns = [{
                        id: "#famille",
                        options: data.options_famille,
                        emptyMessage: "Aucune catégorie..."
                    }];

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
            $("#famille").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un catégorie</option>");

        }
    });
    $("#famille").change(function() {
        var id_famille = $(this).val();
        var id_categorie = $('#id_categorie_dire').val();

        if (id_categorie !== "" && id_famille !== "") {
            $("#id_substance_dire").prop("disabled", false);
            // Charger les substances en fonction de la catégorie et famille sélectionnées
            $.ajax({
                url: "scripts_facture/get_substance_dire.php",
                method: "POST",
                data: {
                    id_categorie: id_categorie,
                    id_famille: id_famille,
                },
                dataType: "json",
                success: function(data) {
                    const dropdowns = [{
                        id: "#id_substance_dire",
                        options: data.options_sub,
                        emptyMessage: "Aucune substance disponible"
                    }];

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
            $("#id_substance_dire").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord une famille</option>"
            );
        }
    });

    // Event handler for the "Autre Laissez passer" button
    $('#btn_autre_lp_dire').click(function() {
        $('#btn_autre_lp_dire').hide(); // Hide the "Autre Laissez passer" button
        $('#lp1_info_nouveau_dire').hide();
        $('#direction_date_dire').hide();
        $('#btn_annuler_dire').show(); // Show the "Annuler" button
        $('#lp1_info_ancien_dire').show();
        $('#date_lp1_dire input, #id_direction_dire select').attr('required', false);
        $('#ancien_lp_dire select').attr('required', true);
        var verifiedLpInput = document.getElementById('verified_lp_dire');
        verifiedLpInput.value = 'ancien';
    });

    // Event handler for the "Annuler" button
    $('#btn_annuler_dire').click(function() {
        $('#btn_autre_lp_dire').show(); // Show the "Autre Laissez passer" button
        $('#btn_annuler_dire').hide(); // Hide the "Annuler" button
        $('#lp1_info_nouveau_dire').show();
        $('#lp1_info_ancien_dire').hide();
        $('#direction_date_dire').show();
        $('#date_lp1_dire input, #id_direction_dire select, #id_lp1_info_dire select').attr('required',
            true);
        $('#ancien_lp').attr('required', false);
        var verifiedLpInput = document.getElementById('verified_lp_dire');
        verifiedLpInput.value = 'nouveau';
    });


    // Lorsqu'une option est sélectionne dans le premier menu
    $("#id_substance_dire").change(function() {
        var id_substance = $(this).val();
        var id_categorie = $('#id_categorie_dire').val();
        var id_famille = $('#famille').val();
        if ((id_substance == 126) || (id_substance == 127)) {
            $('#row_taux').show();
        } else {
            $('#row_taux').hide();
        }
        if ((id_substance !== "") && (id_categorie !== "") && (id_famille)) {
            $("#id_couleur_substance_dire").prop("disabled", false);
            $("#id_dimension_diametre_dire").prop("disabled", false);
            $("#unite_poids_facture_dire").prop("disabled", false);
            $("#granulo_facture_dire").prop("disabled", false);
            $("#id_transparence_dire").prop("disabled", false);
            $("#id_durete_dire").prop("disabled", false);
            $("#id_forme_substance_dire").prop("disabled", false);
            // Activer le deuxième menu déroulant
            $("#id_couleur_substance_dire").prop("disabled", false);
            $("#id_direction_dire").prop("disabled", false);
            // Charger les districts en fonction de la région sélectionnée
            $.ajax({
                url: "scripts_facture/get_details_pim.php",
                method: "POST",
                data: {
                    id_substance: id_substance,
                    id_categorie: id_categorie,
                    id_famille: id_famille,
                },
                dataType: "json",
                success: function(data) {
                    const dropdowns = [{
                            id: "#id_dimension_diametre_dire",
                            options: data.options_dimension_diametre,
                            emptyMessage: "Aucune dimension ou diamètre..."
                        },
                        {
                            id: "#id_couleur_substance_dire",
                            options: data.options_couleur,
                            emptyMessage: "Aucune couleur..."
                        },
                        {
                            id: "#id_durete_dire",
                            options: data.options_durete,
                            emptyMessage: "Aucune dureté..."
                        },
                        {
                            id: "#unite_poids_facture_dire",
                            options: data.options_unite_poids_facture,
                            emptyMessage: "Sélectionner d'abord une unité de poids..."
                        },
                        {
                            id: "#granulo_facture_dire",
                            options: data.options_granulo,
                            emptyMessage: "Aucune granulométrie..."
                        },
                        {
                            id: "#id_transparence_dire",
                            options: data.options_transparence,
                            emptyMessage: "Aucune transparence..."
                        },
                        {
                            id: "#id_forme_substance_dire",
                            options: data.options_forme_substance,
                            emptyMessage: "Aucune forme de substance..."
                        },
                        {
                            id: "#unite_poids_facture_dire",
                            options: data.options_unite,
                            emptyMessage: "Aucune unité..."
                        },
                        {
                            id: "#id_direction_dire",
                            options: data.options_direction,
                            emptyMessage: "Aucune direction"
                        }
                    ];

                    dropdowns.forEach(dropdown => {
                        if (dropdown.options ===
                            "<option value=''>Sélectionner...</option>"
                        ) {
                            $(dropdown.id).prop("disabled", true).html(
                                `<option value=''>${dropdown.emptyMessage}</option>`
                            );
                        } else {
                            $(dropdown.id).prop("disabled", false).html(
                                dropdown
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
            $("#id_couleur_substance_dire").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_dimension_diametre_dire").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#unite_poids_facture_dire").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#granulo_facture_dire").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_transparence_dire").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_durete").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
            $("#id_forme_substance_dire").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un substance...</option>");
        }
    });
});

function updateFlightDetails() {
    var id_direction = $('#id_direction').val();
    var id_substance = $('#id_substance').val();
    var date_lp1 = $('#date_lp1').val();
    if ((date_lp1 !== "") && (id_substance !== "") && (id_direction !== "")) {
        $("id_lp1_info").prop("disabled", false);
        // Charger les districts en fonction de la région sélectionnée
        $.ajax({
            url: "get_lp1.php",
            method: "POST",
            data: {
                id_substance: id_substance,
                id_direction: id_direction,
                date_lp: date_lp1
            },
            dataType: "json",
            success: function(data) {
                const dropdowns = [{
                    id: "#id_lp1_info",
                    options: data.options_lp1,
                    emptyMessage: "Aucune laissez passer..."
                }];

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
        $("#id_lp1_info_dire").prop("disabled", true).html(
            "<option value=''>Sélectionner d'abord un substance, une date et une direction</option>");

    }
}


// document.getElementById('famille').addEventListener('change', function() {
//     var typeSubstanceId = this.value;
//      var id_categorie= $('#id_categorie_dire').val();
//     var substanceSelect = document.getElementById('id_substance');

//     // Enable the substance select field if a type is selected
//     if (typeSubstanceId) {
//         substanceSelect.disabled = false;
//     } else {
//         substanceSelect.disabled = true;
//     }

//     // Clear the previous options
//     substanceSelect.innerHTML = '<option value="">Sélectionner...</option>';

//     if (typeSubstanceId) {
//         var xhr = new XMLHttpRequest();
//         xhr.open('GET', 'scripts_facture/get_substance.php?typeSubstanceId=' + typeSubstanceId,
//             true);
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState == 4 && xhr.status == 200) {
//                 var substances = JSON.parse(xhr.responseText);
//                 substances.forEach(function(substance) {
//                     var option = document.createElement('option');
//                     option.value = substance.id_substance;
//                     option.textContent = substance.nom_substance;
//                     substanceSelect.appendChild(option);
//                 });
//             }
//         };
//         xhr.send();
//     }
// });
// document.getElementById('id_substance').addEventListener('change', function() {
//     var substanceId = this.value;
//     var substanceSelect = document.getElementById('id_categorie');

//     // Enable the substance select field if a type is selected
//     if (substanceId) {
//         substanceSelect.disabled = false;
//     } else {
//         substanceSelect.disabled = true;
//     }

//     // Clear the previous options
//     substanceSelect.innerHTML = '<option value="">Sélectionner...</option>';

//     if (substanceId) {
//         var xhr = new XMLHttpRequest();
//         xhr.open('GET', 'scripts_facture/get_categorie.php?substanceId=' + substanceId, true);
//         xhr.onreadystatechange = function() {
//             if (xhr.readyState == 4 && xhr.status == 200) {
//                 var substances = JSON.parse(xhr.responseText);
//                 substances.forEach(function(substance) {
//                     var option = document.createElement('option');
//                     option.value = substance.id_categorie;
//                     option.textContent = substance.nom_categorie;
//                     substanceSelect.appendChild(option);
//                 });
//             }
//         };
//     }
//     xhr.send();
// });

function selectTom3() {
    // Initialisez TomSelect pour chaque élément select
    var selectOptions = {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };
    new TomSelect("#ancien_lp_dire", selectOptions);

};
</script>