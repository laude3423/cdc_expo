<?php
require(__DIR__ . '/../../scripts/db_connect.php');
if (isset($_GET['id'])) {
    $id_detaille_substance= $_GET['id'];
    $sql = "SELECT sds*, typSub.* FROM substance_detaille_substance AS sds
    LEFT JOIN substance AS sub ON sds.id_substance=sub.id_substance
    LEFT JOIN type_substance typeSub ON sub.id_type_substance=typeSub.id_type_substance WHERE id_detaille_substance = $id_detaille_substance";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row_1 = $result->fetch_assoc();
        $id_detaille_substance= $row_1['id_detaille_substance'];
        $id_substance= $row_1['id_substance'];
        $id_granulo= $row_1['id_granulo'];
        $id_transparence= $row_1['id_transparence'];
        $id_forme_substance= $row_1['id_forme_substance'];
        $id_dimension_diametre= $row_1['id_dimension_diametre'];
        $id_degre_couleur= $row_1['id_degre_couleur'];
        $id_durete= $row_1['id_durete'];
        $id_categorie= $row_1['id_categorie'];
        $prix_substance= $row_1['prix_substance'];
        $nom_type_substance= $row_1['nom_type_substance'];
        $id_couleur_substance= $row_1['id_couleur_substance'];
        
    }
}
if (isset($_POST['submit'])) {
        $id = $_POST['id_detaille_substance'];
        $prix = $_POST['prix_substance'];
        $sql="UPDATE `substance_detaille_substance` SET `prix_substance`='$prix' WHERE id_detaille_substance=$id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['toast_message'] = "Modification réussie.";
             header("Location: https://cdc.minesmada.org/view/gerer_detail_substance/lister.php");
            exit();
        } else {
            echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    }

?>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
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
<div class="modal" tabindex="-1" role="dialog" id="edit_contenu_facture">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier un détails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="label-form2" action="">
                    <div class="row">
                        <input type="hidden" class="form-control" id="id_detaille_substance"
                            name="id_detaille_substance" value="<?php echo $id_detaille_substance?>">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type_substance " class="fw-bold">Type de la substance :</label>
                                <select class="form-select" id="type_substance" name="type_substance">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    $query = "SELECT * FROM type_substance";
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
                            <div class="mb-3">
                                <label for="id_substance " class="fw-bold">Nom de la substance :</label>
                                <select class="form-select" id="id_substance" name="id_substance">
                                    <option value="">Sélectionner...</option>
                                    <?php
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_couleur_substance " class="fw-bold">Nom de la substance :</label>
                                <select class="form-select" id="id_couleur_substance" name="id_couleur_substance">
                                    <option value="">Sélectionner...</option>
                                    <?php
                                    $query = "SELECT * FROM couleur_substance";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_couleur_substance'] == $id_couleur_substance) ? 'selected' : '';
                                        echo "<option value='" . $row['id_couleur_substance'] . "'$selected>" . $row['nom_couleur_substance'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_granulo " class="fw-bold">Nom de la substance :</label>
                                    <select class="form-select" id="id_granulo" name="id_granulo">
                                        <option value="">Sélectionner...</option>
                                        <?php
                                    $query = "SELECT * FROM granulo";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_granulo'] == $id_granulo) ? 'selected' : '';
                                        echo "<option value='" . $row['id_granulo'] . "'$selected>" . $row['nom_granulo'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_transparence " class="fw-bold">Transparence de la substance :</label>
                                    <select class="form-select" id="id_transparence" name="id_transparence">
                                        <option value="">Sélectionner...</option>
                                        <?php
                                    $query = "SELECT * FROM transparence";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_transparence'] == $id_transparence) ? 'selected' : '';
                                        echo "<option value='" . $row['id_transparence'] . "'$selected>" . $row['nom_transparence'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_degre_couleur " class="fw-bold">Degré de couleur de la substance
                                        :</label>
                                    <select class="form-select" id="id_degre_couleur" name="id_degre_couleur">
                                        <option value="">Sélectionner...</option>
                                        <?php
                                    $query = "SELECT * FROM degre_couleur";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_degre_couleur'] == $id_degre_couleur) ? 'selected' : '';
                                        echo "<option value='" . $row['id_degre_couleur'] . "'$selected>" . $row['nom_degre_couleur'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_forme_substance " class="fw-bold">Forme de la substance :</label>
                                    <select class="form-select" id="id_forme_substance" name="id_forme_substance">
                                        <option value="">Sélectionner...</option>
                                        <?php
                                    $query = "SELECT * FROM forme_substance";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_forme_substance'] == $id_forme_substance) ? 'selected' : '';
                                        echo "<option value='" . $row['id_forme_substance'] . "'$selected>" . $row['nom_forme_substance'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_durete " class="fw-bold">Dureté de la substance :</label>
                                    <select class="form-select" id="id_durete" name="id_durete">
                                        <option value="">Sélectionner...</option>
                                        <?php
                                    $query = "SELECT * FROM durete";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_durete'] == $id_durete) ? 'selected' : '';
                                        echo "<option value='" . $row['id_durete'] . "'$selected>" . $row['nom_durete'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_categorie " class="fw-bold">Catégorie de la substance :</label>
                                    <select class="form-select" id="id_categorie" name="id_categorie">
                                        <option value="">Sélectionner...</option>
                                        <?php
                                    $query = "SELECT * FROM categorie";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_categorie'] == $id_categorie) ? 'selected' : '';
                                        echo "<option value='" . $row['id_categorie'] . "'$selected>" . $row['nom_categorie'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_dimension_diametre " class="fw-bold">Dimension ou diamètre de la
                                        substance :</label>
                                    <select class="form-select" id="id_dimension_diametre" name="id_dimension_diametre">
                                        <option value="">Sélectionner...</option>
                                        <?php
                                    $query = "SELECT * FROM dimension_diametre";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = ($row['id_dimension_diametre'] == $id_dimension_diametre) ? 'selected' : '';
                                        echo "<option value='" . $row['id_dimension_diametre'] . "'$selected>" . $row['nom_dimension_diametre'] . "</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prix_substance " class="fw-bold">Prix de la substance :</label>
                                    <input type="number" name="prix_substance" id="prix_substance"
                                        value=" <?php echo $prix_substance ?>" required>
                                </div>
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