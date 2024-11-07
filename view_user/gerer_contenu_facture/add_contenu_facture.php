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
    var id = $('#num_data').val();
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
    var id = $('#num_data').val();
    $('#add_contenu_facture').on('shown.bs.modal', function() {
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
    $('#label-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission

        // Check prix unitaire first
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
                        var unite_poids = $('#unite_poids_facture').val();
                        var id_categorie = parseInt($('#id_categorie').val());
                        var typeSubstance = parseInt($('#typeSubstance').val());
                        var unite_monetaire = $('#unite_monetaire').val();
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
                        if (id_categorie == 3) {
                            prix_unitaire = prix_unitaire * 2.75;
                        }
                        if ((typeSubstance == 4) && (unite_poids == 'g_pour_kg')) {
                            prix_unitaire = prix_unitaire / 1000;

                        }
                        console.log(typeSubstance);
                        console.log(unite_poids);
                        if (prix_unitaire_facture >= prix_unitaire) {
                            console.log(prix_unitaire_facture);
                            console.log(prix_unitaire);
                            $.ajax({
                                url: 'scripts_facture/insert_contenu_facture.php',
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

                        } else {
                            alert(
                                'Le prix unitaire saisi ne correspond pas au prix unitaire de la base de données: ' +
                                prix_unitaire + '$'
                            );
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
<div class="modal fade" id="add_contenu_facture" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div id="message" style="display: none;"></div>
                <form method="post" id="label-form" action="">
                    <div class="row">
                        <input type="hidden" class="form-control" name="num_data" value="<?php echo $id_data_cc; ?>"
                            id="num_data" required style="font-size:90%">
                        <div class="col">
                            <div class="mb-3">
                                <label for="typeSubstance" class="fw-bold">Type de la substance :</label>
                                <select class="form-select" id="typeSubstance" name="typeSubstance" required>
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    $query = "SELECT * FROM type_substance WHERE id_type_substance NOT IN (5, 6)";
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
                                    <?php
                                        // $query = "SELECT * FROM categorie";
                                        // $result = $conn->query($query);
                                        // while ($row = $result->fetch_assoc()) {
                                        //     echo "<option value='" . $row['id_categorie'] . "'>" . $row['nom_categorie'] . "</option>";
                                        // }
                                        ?>
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
                            <div class="row">
                                <div class="col">
                                    <label for="prix_unitaire_facture" class="fw-bold">Prix unitaire:</label>
                                    <input type="number" class="form-control" id="prix_unitaire_facture"
                                        name="prix_unitaire_facture" step="0.001" required>
                                </div>
                                <div class="col">
                                    <label for="unite_monetaire" class="fw-bold">Unité monétaire</label>
                                    <select class="form-select" id="unite_monetaire" name="unite_monetaire" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="dollar">DOLLAR</option>
                                        <option value="euro">EURO</option>
                                        <option value="yen">YEN</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="row1">
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
                                    <label for="id_transparence" id="label-transparence"
                                        class="fw-bold">Transparence:</label>
                                    <label for="id_transparence" id="label-qualite" class="fw-bold">Qualité:</label>
                                    <select class="form-select" id="id_transparence" name="id_transparence" required>
                                        <option value="">Sélectionner...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_durete" class="fw-bold">Dureté :</label>
                                    <select class="form-select" id="id_durete" name="id_durete" required>
                                        <option value="">Sélectionner...</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_degre_couleur" id="label-degre" class="fw-bold">Degré de couleur:</label>
                                <label for="id_degre_couleur " id="label-purite" class="fw-bold">Purité:</label>
                                <select class="form-select" id="id_degre_couleur" name="id_degre_couleur" required>
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
                    <div class="row mb-3" id="direction_date">
                        <div class="col">
                            <label for="date_lp1" class="fw-bold">Date de creation de LP :</label>
                            <input type="date" class="form-control" id="date_lp1" name="date_lp1">
                        </div>
                        <div class="col">
                            <label for="id_direction" class="fw-bold">Direction:</label>
                            <select id="id_direction" class="form-control" name="id_direction"
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
                        <div class="col" id="dimension_diametre">
                            <div class="mb-3">
                                <label for="id_dimension_diametre" class="fw-bold">Dimension ou
                                    diametre:</label>
                                <select class="form-select" id="id_dimension_diametre" name="id_dimension_diametre"
                                    required>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col" id="lp1_info_container">
                            <label for="id_lp1_info" class="fw-bold ">Laissez passer I correspondant :
                            </label>
                            <select id="id_lp1_info" class="form-select" name="id_lp1_info">
                                <option value="">Sélectionner...</option>
                            </select>
                        </div>
                        <div class="col" id="lp1_info_container2">
                            <label for="ancien_lp" class="fw-bold ">Ancien Laissez passer I: </label>
                            <select id="ancien_lp" name="ancien_lp" class="form-select">
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
                    <div class="row">
                        <div class="col">
                            <input type="hidden" id="verified_lp" name="verified_lp"
                                value="<?php echo $id_lp1_existe; ?>" class="form-control">
                            <button type="button" id="btn_autre_lp" class="btn btn-primary">Ancien
                                LP</button>
                            <button type="button" id="btn_annuler" class="btn btn-secondary"
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
    $('#lp1_info_container2').hide();
    $('#btn_annuler').hide();
    $('#label-qualite').hide();
    $('#label-purite').hide();
    $('#dimension_diametre').hide();
    //selectTom2();
    // Event handler for the "Autre Laissez passer" button
    $('#btn_autre_lp').click(function() {
        $('#btn_autre_lp').hide(); // Hide the "Autre Laissez passer" button
        $('#lp1_info_container').hide();
        $('#direction_date').hide();
        $('#btn_annuler').show(); // Show the "Annuler" button
        $('#lp1_info_container2').show();
        $('#date_lp1 input, #id_direction select').attr('required', false);
        $('#ancien_lp select').attr('required', true);
        var verifiedLpInput = document.getElementById('verified_lp');
        verifiedLpInput.value = 'ancien';
    });

    // Event handler for the "Annuler" button
    $('#btn_annuler').click(function() {
        $('#btn_autre_lp').show(); // Show the "Autre Laissez passer" button
        $('#btn_annuler').hide(); // Hide the "Annuler" button
        $('#lp1_info_container').show();
        $('#lp1_info_container2').hide(); // Hide the fields again
        $('#lp1_info_container').show();
        $('#direction_date').show();
        $('#date_lp1 input, #id_direction select, #id_lp1_info select').attr('required',
            true);
        $('#ancien_lp').attr('required', false);
        var verifiedLpInput = document.getElementById('verified_lp');
        verifiedLpInput.value = 'nouveau';
    });
    $("#typeSubstance").change(function() {
        var type = $(this).val();
        if (type == 3) {
            $('#row1').hide();
            $('#label-degre').hide();
            $('#label-purite').show();
            $('#label-qualite').hide();
            $('#label-transparence').show();
        } else if (type == 4) {
            $('#row1').show();
            $('#enregistre').show();
            $('#dimension_diametre').show();
            $('#label-degre').show();
            $('#label-qualite').show();
            $('#label-transparence').hide();
        } else {
            $('#label-transparence').show();
            $('#label-qualite').hide();
            $('#row1').show();
            $('#label-degre').show();
            $('#dimension_diametre').hide();
        }

    });
    // Lorsqu'une option est sélectionne dans le premier menu
    $("#id_categorie").change(function() {
        var id_categorie = $(this).val();
        if (id_categorie == 3) {
            id_categorie = 2;
        }
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
            $("#id_direction").prop("disabled", false);
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
                            id: "#id_direction",
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
        }
    });


    // $("#id_direction").change(function() {
    //     var id_direction = $(this).val();
    //     var id_substance = $('#id_substance').val();
    //     var date_lp1 = $('#date_lp1').val();
    //     if ((id_categorie !== "") && (id_substance !== "")) {
    //         $("id_lp1_info").prop("disabled", false);
    //         // Charger les districts en fonction de la région sélectionnée
    //         $.ajax({
    //             url: "get_lp1.php",
    //             method: "POST",
    //             data: {
    //                 id_substance: id_substance,
    //                 id_direction: id_direction,
    //                 date_lp: date_lp1
    //             },
    //             dataType: "json",
    //             success: function(data) {
    //                 const dropdowns = [{
    //                     id: "#id_lp1_info",
    //                     options: data.options_lp1,
    //                     emptyMessage: "Aucune laissez passer..."
    //                 }];

    //                 dropdowns.forEach(dropdown => {
    //                     if (dropdown.options ===
    //                         "<option value=''>Sélectionner...</option>") {
    //                         $(dropdown.id).prop("disabled", true).html(
    //                             `<option value=''>${dropdown.emptyMessage}</option>`
    //                         );
    //                     } else {
    //                         $(dropdown.id).prop("disabled", false).html(dropdown
    //                             .options);
    //                     }
    //                 });
    //             },
    //             error: function(xhr, status, error) {
    //                 console.log("An error occurred:", error);
    //                 console.log("Response text:", xhr.responseText);
    //             }
    //         });

    //     } else {
    //         $("#id_lp1_info").prop("disabled", true).html(
    //             "<option value=''>Sélectionner d'abord un substance...</option>");

    //     }
    // });
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
        $("#id_lp1_info").prop("disabled", true).html(
            "<option value=''>Sélectionner d'abord un substance, une date et une direction</option>");

    }
}
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
        xhr.open('GET', 'scripts_facture/get_substance.php?typeSubstanceId=' + typeSubstanceId,
            true);
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
    var typeSubstanceId = this.value;
    var substanceSelect = document.getElementById('id_categorie');

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
        xhr.open('GET', 'scripts_facture/get_categorie.php?substanceId=' + typeSubstanceId,
            true);
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
        xhr.send();
    }
});
</script>