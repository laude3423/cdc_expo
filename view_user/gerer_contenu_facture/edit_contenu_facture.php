<?php
require(__DIR__ . '/../../scripts/db_connect.php');
if (isset($_GET['id'])) {
    $id_contenu_facture= $_GET['id'];
    $sql = "SELECT cf.*, sds.*, ts.* FROM contenu_facture cf
    LEFT JOIN substance_detaille_substance sds ON sds.id_detaille_substance = cf.id_detaille_substance
    LEFT JOIN substance AS sub ON sds.id_substance = sub.id_substance
    LEFT JOIN type_substance AS ts ON ts.id_type_substance = sub.id_type_substance
    WHERE id_contenu_facture = $id_contenu_facture;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row_1 = $result->fetch_assoc();
        $poids_facture = $row_1["poids_facture"];
        $unite_poids_facture = $row_1["unite_poids_facture"];
        $prix_unitaire_facture2 = $row_1["prix_unitaire_facture"];
        $quantite_lp1_initial_lp1_suivis = $row_1["quantite_lp1_initial_lp1_suivis"];
        $quantite_lp1_actuel_lp1_suivis = $row_1["quantite_lp1_actuel_lp1_suivis"];
        $pj_lp1_suivis_lp1_suivis = $row_1["pj_lp1_suivis_lp1_suivis"];

        $id_lp1_info = $row_1["id_lp1_info"];
        $id_detaille_substance  = $row_1["id_detaille_substance"];
        $id_data_cc = $row_1["id_data_cc"];
        $id_ancien_lp = $row_1['id_ancien_lp'];
        $preforme = $row_1['preforme'];
        $type_substance = intval($row_1['id_type_substance']);
        $id_substance = $row_1["id_substance"] ?? "";
        $id_couleur_substance = isset($row_1["id_couleur_substance"]) ? $row_1["id_couleur_substance"] : null;
        $id_granulo = isset($row_1["id_granulo"]) ? $row_1["id_granulo"] : null;
        $id_transparence = isset($row_1["id_transparence"]) ? $row_1["id_transparence"] : null;
        $id_degre_couleur = isset($row_1["id_degre_couleur"]) ? $row_1["id_degre_couleur"] : null;
        $id_forme_substance = isset($row_1["id_forme_substance"]) ? $row_1["id_forme_substance"] : null;
        $id_durete = isset($row_1["id_durete"]) ? $row_1["id_durete"] : null;
        $id_categorie = isset($row_1["id_categorie"]) ? $row_1["id_categorie"] : null;
        $id_dimension_diametre = isset($row_1["id_dimension_diametre"]) ? $row_1["id_dimension_diametre"] : null;
        $prix_substance = isset($row_1["prix_substance"]) ? $row_1["prix_substance"] : null;
        $unite_prix_substance = isset($row_1["unite_prix_substance"]) ? $row_1["unite_prix_substance"] : null;
        
    }
}
$id_lp1_existe ="";
if(!empty($id_lp1_info)){
    $id_lp1_existe ="nouveau";
}else if(!empty($id_ancien_lp)){
    $id_lp1_existe ="ancien";
}
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script>
$(document).ready(function() {
    $('#label-form2').on('submit', function(e) {
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
                        var prix_unitaire_facture2 = parseFloat($('#prix_unitaire_facture2')
                            .val());
                        var unite_monetaire = $('#unite_monetaire_edit').val();
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
                        if (prix_unitaire_facture2 >= prix_unitaire) {
                            $('#label-form2').off('submit').submit(); // Submit the form
                        } else {
                            alert(
                                'Le prix unitaire saisi ne correspond pas au prix unitaire de la base de données:' +
                                prix_unitaire + 'et' + prix_unitaire_facture2);
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
<div class="modal fade" id="edit_contenu_facture" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="label-form2" action="scripts_facture/update_contenu_facture.php">
                    <div class="row">
                        <input type="hidden" class="form-control" id="num_data" name="num_data"
                            value="<?php echo $id_data_cc?>">
                        <input type="hidden" class="form-control" id="id_contenu" name="id_contenu"
                            value="<?php echo $id_contenu_facture?>">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_substance " class="fw-bold">Designation :</label>
                                <select class="form-select" id="id_substance" name="id_substance">
                                    <option value="">Sélectionner...</option>
                                    <!-- Remplir les options en récuprant les types de substance depuis la base de donnes -->
                                    <?php
                                    // Connexion à la base de donnes
                                    require '../../scripts/db_connect.php';
                                    
                                    // Rcuprer les types de substance depuis la base de données
                                    $query = "SELECT * FROM substance";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_substance'] == $id_substance) ? 'selected' : '';
                                        echo "<option value='" . $row['id_substance'] . "'$selected>" . $row['nom_substance'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php
                                    if(!empty($id_couleur_substance)){ ?>
                            <div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Couleur:</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance">
                                    <option value="">Sélectionner...</option>
                                    <!-- Remplir les options en récuprant les types de substance depuis la base de donnes -->
                                    <?php
                                            // Connexion à la base de donnes
                                            require '../../scripts/db_connect.php';
                                            
                                            // Rcuprer les types de substance depuis la base de données
                                            $query = "SELECT DISTINCT cs.* FROM couleur_substance cs
                                            LEFT JOIN substance_detaille_substance sds ON sds.id_couleur_substance = cs.id_couleur_substance
                                            WHERE sds.id_substance = $id_substance";
                                            $result = $conn->query($query);
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row['id_couleur_substance'] == $id_couleur_substance) ? 'selected' : '';
                                                echo "<option value='" . $row['id_couleur_substance'] . "'$selected>" . $row['nom_couleur_substance'] . "</option>";
                                            }
                                            ?>
                                </select>
                            </div>
                            <?php }
                                else {
                                ?><div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Couleur:</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    disabled>
                                    <option value="">Sélectionner...</option>

                                </select>
                            </div>
                            <?php
                                }
                                ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="poids_facture " class="fw-bold">Poids :</label>
                                <input type="number" class="form-control" id="poids_facture" name="poids_facture"
                                    step="0.01" value="<?php echo $poids_facture;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unite_poids_facture" class="fw-bold">Unité :</label>
                                <select class="form-select" id="unite_poids_facture" name="unite_poids_facture">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                // Connexion à la base de donnes
                                require '../../scripts/db_connect.php';
                                
                                // Rcuprer les types de substance depuis la base de données
                                $query_unite = "SELECT sds.* FROM substance_detaille_substance sds
                                INNER JOIN substance s ON sds.id_substance = s.id_substance
                                WHERE sds.unite_prix_substance IS NOT NULL
                                GROUP BY unite_prix_substance";
                                $result_unite = $conn->query($query_unite);
                                while ($row_unite = $result_unite->fetch_assoc()) {
                                    $selected = ($row_unite['unite_prix_substance'] == $unite_prix_substance) ? 'selected' : '';
                                    echo "<option value='" . $row_unite['unite_prix_substance'] . "'$selected>" . $row_unite['unite_prix_substance'] . "</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col">
                                    <label for="prix_unitaire_facture2 " class="fw-bold">Prix unitaire en US
                                        $/Unité:</label>
                                    <input type="number" class="form-control" id="prix_unitaire_facture2"
                                        name="prix_unitaire_facture2" step="0.01"
                                        value="<?php echo $prix_unitaire_facture2;?>">
                                </div>
                                <div class="col">
                                    <?php
                                    $selectedValue_dire = 'dollar'; // Exemple de valeur
                                    function isSelected_dire($value, $selectedValue_dire) {
                                        return $value === $selectedValue_dire ? 'selected' : '';
                                    }
                                    ?>
                                    <label for="unite_monetaire_edit" class="fw-bold">Unité monétaire</label>
                                    <select class="form-select" id="unite_monetaire_edit" name="unite_monetaire_edit"
                                        required>
                                        <option value="">Sélectionner...</option>
                                        <option value="dollar" <?= isSelected_dire('dollar', $selectedValue_dire) ?>>
                                            DOLLAR
                                        </option>
                                        <option value="euro" <?= isSelected_dire('euro', $selectedValue_dire) ?>>EURO
                                        </option>
                                        <option value="yen" <?= isSelected_dire('yen', $selectedValue_dire) ?>>YEN
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <?php if(!empty($id_granulo)){
                                ?>
                            <div class="mb-3">
                                <label for="granulo_facture " class="fw-bold">Granulo:</label>
                                <select class="form-select" id="granulo_facture" name="granulo_facture">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                // Connexion à la base de donnes
                                require '../../scripts/db_connect.php';
                                
                                // Rcuprer les types de substance depuis la base de données
                                $query_g = "SELECT DISTINCT g.* FROM substance_detaille_substance sds 
                                INNER JOIN granulo g ON g.id_granulo = sds.id_granulo
                                WHERE sds.id_substance = $id_substance";
                                
                                $result_g = $conn->query($query_g);
                                while ($row_g = $result_g->fetch_assoc()) {
                                    $selected = ($row_g['id_granulo'] == $id_granulo) ? 'selected' : '';
                                    echo "<option value='" . $row_g['id_granulo'] . "'$selected>" . $row_g['nom_granulo'] . "</option>";
                                }
                                ?>
                                </select>
                            </div>
                            <?php }else {
                                    ?><div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Granulo:</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance "
                                    disabled>
                                    <option value="">Sélectionner...</option>

                                </select>
                            </div>
                            <?php
                                }
                                ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!empty($id_degre_couleur)) { ?>
                            <div class="mb-3">
                                <?php if ($type_substance==3) { ?>
                                <label for="id_degre_couleur " class="fw-bold">Purité :</label>
                                <?php }else { ?>
                                <label for="id_degre_couleur " class="fw-bold">Degré de couleur :</label>
                                <?php } ?>
                                <select class="form-select" id="id_degre_couleur" name="id_degre_couleur">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    // Connexion à la base de données
                                    require '../../scripts/db_connect.php';
                                    
                                    // Récupérer les types de substance depuis la base de données
                                    $query_dc = "SELECT DISTINCT dc.* FROM degre_couleur dc 
                                                LEFT JOIN substance_detaille_substance sds ON dc.id_degre_couleur = sds.id_degre_couleur
                                                WHERE sds.id_substance = $id_substance";
                                    
                                    $result_dc = $conn->query($query_dc);
                                    while ($row_dc = $result_dc->fetch_assoc()) {
                                        $selected = ($row_dc['id_degre_couleur'] == $id_degre_couleur) ? 'selected' : '';
                                        echo "<option value='" . $row_dc['id_degre_couleur'] . "' $selected>" . $row_dc['nom_degre_couleur'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } else { ?>
                            <div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Degré de couleur :</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($id_transparence)) { ?>
                            <div class="mb-3">
                                <?php if ($type_substance==4) { ?>
                                <label for="id_transparence " class="fw-bold">Qualité:</label>
                                <?php }else { ?>
                                <label for="id_transparence " class="fw-bold">Transparence :</label>
                                <?php } ?>
                                <select class="form-select" id="id_transparence" name="id_transparence">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    // Connexion à la base de données
                                    require '../../scripts/db_connect.php';
                                    
                                    // Récupérer les types de substance depuis la base de données
                                    $query_tr = "SELECT DISTINCT t.* FROM transparence t 
                                                INNER JOIN substance_detaille_substance sds ON t.id_transparence = sds.id_transparence
                                                WHERE sds.id_substance = $id_substance";
                                    
                                    $result_tr = $conn->query($query_tr);
                                    while ($row_tr = $result_tr->fetch_assoc()) {
                                        $selected = ($row_tr['id_transparence'] == $id_transparence) ? 'selected' : '';
                                        echo "<option value='" . $row_tr['id_transparence'] . "' $selected>" . $row_tr['nom_transparence'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } else { ?>
                            <div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Transparence :</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!empty($id_durete)) { ?>
                            <div class="mb-3">
                                <label for="id_durete " class="fw-bold">Dureté :</label>
                                <select class="form-select" id="id_durete" name="id_durete">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    // Connexion à la base de données
                                    require '../../scripts/db_connect.php';
                                    
                                    // Récupérer les types de substance depuis la base de données
                                    $query_d = "SELECT DISTINCT d.* FROM durete d 
                                                INNER JOIN substance_detaille_substance sds ON d.id_durete = sds.id_durete
                                                WHERE sds.id_substance = $id_substance";
                                    
                                    $result_d = $conn->query($query_d);
                                    while ($row_d = $result_d->fetch_assoc()) {
                                        $selected = ($row_d['id_durete'] == $id_durete) ? 'selected' : '';
                                        echo "<option value='" . $row_d['id_durete'] . "' $selected>" . $row_d['nom_durete'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } else { ?>
                            <div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Dureté :</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($id_categorie)) {?>

                            <div class="mb-3">
                                <label for="id_categorie " class="fw-bold">Catégorie :</label>
                                <select class="form-select" id="id_categorie" name="id_categorie">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    // Connexion à la base de données
                                    $query_cat="";
                                    require '../../scripts/db_connect.php';
                                     if ($preforme == "3"){
                                        $query_cat = "SELECT * FROM categorie";
                                        $id_categorie = 3;
                                    }else{
                                         $query_cat = "SELECT DISTINCT cat.* FROM categorie cat 
                                                INNER JOIN substance_detaille_substance sds ON cat.id_categorie = sds.id_categorie
                                                WHERE sds.id_substance = $id_substance";
                                    }
                                    
                                    // Récupérer les types de substance depuis la base de données
                                   
                                    
                                    $result_cat = $conn->query($query_cat);
                                    while ($row_cat = $result_cat->fetch_assoc()) {
                                        $selected = ($row_cat['id_categorie'] == $id_categorie) ? 'selected' : '';
                                        echo "<option value='" . $row_cat['id_categorie'] . "' $selected>" . $row_cat['nom_categorie'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } else { ?>
                            <div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Catégorie :</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!empty($id_forme_substance)) { ?>
                            <div class="mb-3">
                                <label for="id_forme_substance" class="fw-bold">Forme :</label>
                                <select class="form-select" id="id_forme_substance" name="id_forme_substance">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    // Connexion à la base de données
                                    require '../../scripts/db_connect.php';

                                    // Récupérer les types de substance depuis la base de données
                                    $query_fs = "SELECT DISTINCT fs.* FROM forme_substance fs 
                                                LEFT JOIN substance_detaille_substance sds ON fs.id_forme_substance = sds.id_forme_substance
                                                WHERE sds.id_substance = $id_substance";

                                    $result_fs = $conn->query($query_fs);
                                    while ($row_fs = $result_fs->fetch_assoc()) {
                                        $selected = ($row_fs['id_forme_substance'] == $id_forme_substance) ? 'selected' : '';
                                        echo "<option value='" . $row_fs['id_forme_substance'] . "' $selected>" . $row_fs['nom_forme_substance'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } else { ?>
                            <div class="mb-3">
                                <label for="id_couleur_substance" class="fw-bold">Forme :</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($id_dimension_diametre)) { ?>
                            <div class="mb-3">
                                <label for="id_dimension_diametre" class="fw-bold">Dimension ou diametre :</label>
                                <select class="form-select" id="id_dimension_diametre" name="id_dimension_diametre">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    // Connexion à la base de données
                                    require '../../scripts/db_connect.php';

                                    // Récupérer les types de substance depuis la base de données
                                    $query_dd = "SELECT DISTINCT dd.* FROM dimension_diametre dd 
                                                LEFT JOIN substance_detaille_substance sds ON dd.id_dimension_diametre = sds.id_dimension_diametre
                                                WHERE sds.id_substance = $id_substance";

                                    $result_dd = $conn->query($query_dd);

                                    while ($row_dd = $result_dd->fetch_assoc()) {
                                        $selected = ($row_dd['id_dimension_diametre'] == $id_dimension_diametre) ? 'selected' : '';
                                        echo "<option value='" . $row_dd['id_dimension_diametre'] . "' $selected>" . $row_dd['nom_dimension_diametre'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php } else { ?>
                            <div class="mb-3">
                                <label for="id_couleur_substance" class="fw-bold">Dimension ou Diamètre :</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance"
                                    disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="mb-3" id="lp1_info_container_edit" data-id-lp1-info="<?php echo $id_lp1_info; ?>">
                        <label for="id_lp1_info_edit" class="fw-bold">Laissez passer I correspondant :</label>
                        <select class="form-select" id="id_lp1_info_edit" name="id_lp1_info_edit" autocomplete="off">
                            <option value="">Sélectionner...</option>
                            <?php
                            // Récupérer les types de substance depuis la base de données
                            $sql = "SELECT * FROM substance WHERE id_substance = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('i', $id_substance);
                            $stmt->execute();
                            $resu = $stmt->get_result();
                            $row = $resu->fetch_assoc();
                            $nom_substance = $row['nom_substance'];
                            $nom_substance = explode(' ', trim($nom_substance))[0];
                            require '../../scripts/connect_db_lp1.php';

                            // Récupérer les types de substance depuis la base de données
                            $query = "SELECT lp.*, s.*, pr.* 
                                    FROM lp_info AS lp
                                    INNER JOIN produits pr ON lp.id_produit = pr.id_produit
                                    INNER JOIN substance s ON pr.id_substance = s.id_substance
                                    WHERE validation_admin = 1 AND s.nom_substance LIKE ?";
                            $stmt = $conn_lp1->prepare($query);
                            $like_nom_substance = "%$nom_substance%";
                            $stmt->bind_param('s', $like_nom_substance);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($row['id_lp'] == $id_lp1_info) ? 'selected' : '';
                                echo "<option value='" . $row['id_lp'] . "' $selected>" . $row['num_LP'] . ' (' . $row['nom_substance'] . ")</option>";
                            }
                            $conn_lp1->close();
                            ?>
                        </select>
                    </div>
                    <div class="mb-3" id="lp1_info_ancien" data-id-ancien-lp="<?php echo $id_ancien_lp; ?>">
                        <label for="ancien_lp_edit" class="fw-bold">Ancien LP1:</label>
                        <select class="form-select" id="ancien_lp_edit" name="ancien_lp_edit">
                            <option value="">Sélectionner...</option>
                            <?php 
                               $sql_ancien = "SELECT * FROM ancien_lp WHERE validation_lp='Validé'";

                                    $result_ancien = $conn->query($sql_ancien);
                                    while ($row_ancien = $result_ancien->fetch_assoc()) {
                                        $selected = ($row_ancien['id_ancien_lp'] == $id_ancien_lp) ? 'selected' : '';
                                        echo "<option value='" . $row_ancien['id_ancien_lp'] . "' $selected>" . $row_ancien['numero_lp'] . "</option>";
                                    }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="hidden" id="verified_lp" name="verified_lp"
                                value="<?php echo $id_lp1_existe; ?>" class="form-control">
                            <button type="button" id="btn_autre_edit" class="btn btn-primary">Ancien LP</button>
                            <button type="button" id="btn_annuler_edit" class="btn btn-secondary"
                                style="display: none;">Annuler</button>
                        </div>
                    </div><br>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var lp1InfoContainerEdit = document.getElementById('lp1_info_container_edit');
    if (lp1InfoContainerEdit) {
        var idLp1Info = lp1InfoContainerEdit.getAttribute('data-id-lp1-info');
        if (!idLp1Info) {
            lp1InfoContainerEdit.style.display = 'none';
        }
    }

    var lp1InfoAncien = document.getElementById('lp1_info_ancien');
    if (lp1InfoAncien) {
        var idAncienLp = lp1InfoAncien.getAttribute('data-id-ancien-lp');
        console.log(idAncienLp);
        if (!idAncienLp) {
            lp1InfoAncien.style.display = 'none';
        }
    }
    $('#btn_autre_edit').click(function() {
        $('#btn_autre_edit').hide(); // Hide the "Autre Laissez passer" button
        $('#lp1_info_container_edit').hide();
        $('#lp1_info_ancien').show();
        $('#btn_annuler_edit').show();
        $('#id_lp1_info_edit select').attr('required', false);
        $('#ancien_lp select').attr('required', true);
        var verifiedLpInput = document.getElementById('verified_lp');
        verifiedLpInput.value = 'ancien';
    });

    // Event handler for the "Annuler" button
    $('#btn_annuler_edit').click(function() {
        $('#btn_autre_edit').show(); // Show the "Autre Laissez passer" button
        $('#btn_annuler_edit').hide(); // Hide the "Annuler" button
        $('#lp1_info_container_edit').show();
        $('#lp1_info_ancien').hide();
        $('#id_lp1_info_edit select').attr('required', true);
        $('#ancien_lp_edit select').attr('required', false);
        var verifiedLpInput = document.getElementById('verified_lp');
        verifiedLpInput.value = 'nouveau';
    });
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
<script>
// Sélection de l'option "Sélectionner..." par son identifiant
var optionSelection = document.getElementById('optionSelection');
// Sélection du select
var select = document.getElementById('id_dimension_diametre ');

// Désactivation du select lorsque la page est chargée
window.onload = function() {
    if (optionSelection.selected) {
        select.disabled = true;
    }
};
</script>