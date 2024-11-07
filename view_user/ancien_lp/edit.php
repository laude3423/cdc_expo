<?php
require_once('../../scripts/db_connect.php');
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM ancien_lp WHERE id_ancien_lp = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resu = $stmt->get_result();
    $row = $resu->fetch_assoc();

    $stmt->close();
}else{
    echo "vide";
}
?>
<?php
$selectedPermis = $row['type_permis'];
$selectedValue = $row['type_lp']; // Exemple de valeur
$selectedUnite = $row['unite'];
    function isSelected($value, $selectedValue) {
    return $value === $selectedValue ? 'selected' : '';
    }
    function isSelecteded($value, $selectedPermis) {
    return $value === $selectedPermis ? 'selected' : '';
    }
?>
<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier LP</h1>
                <button type="button" class="btn-close" onclick="closeModal1()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="./update.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id_edit" name="id_edit" value="<?php echo $id; ?>">
                    <div class="row">
                        <div class="col">
                            <label for="type_lp_edit" class="col-form-label">Type de LP:</label>
                            <select id="type_lp_edit" class="form-select" name="type_lp_edit" placeholder="Choisir ..."
                                autocomplete="off" required style="font-size:90%" onchange="showFields2()">
                                <option value="">Choisir ...</option>
                                <option value="LPS" <?= isSelected('LPS', $selectedValue) ?>>LPS</option>
                                <option value="LPIIIC" <?= isSelected('LPIIIC', $selectedValue) ?>>LPIIIC</option>
                                <option value="LPIFOLIO" <?= isSelected('LPIFOLIO', $selectedValue) ?>>LPIFOLIO</option>
                                <option value="LPIIIE" <?= isSelected('LPIIIE', $selectedValue) ?>>LPIIIE</option>
                                <option value="LPII" <?= isSelected('LPII', $selectedValue) ?>>LPII</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="folio_edit" class="col-form-label">Numéro du folio:</label>
                            <input type="text" class="form-control" name="folio_edit" id="folio_edit"
                                placeholder="Numéro du folio" value="<?php echo $row['numero_folio']; ?>" required
                                style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="num_lp_edit" class="col-form-label">Numéro du LP:</label>
                            <input type="text" class="form-control" name="num_lp_edit" id="num_lp_edit"
                                placeholder="Numéro du Laisser-passer" value="<?php echo $row['numero_lp']; ?>" required
                                style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="nom_titulaire_edit" class="col-form-label">Titulaire:</label>
                            <input type="text" class="form-control" name="nom_titulaire_edit" id="nom_titulaire_edit"
                                placeholder="Nom du titulaire" value="<?php echo $row['titulaire_lp']; ?>" required
                                style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="date_creation_edit" class="col-form-label">Date de création:</label>
                            <input type="date" class="form-control" name="date_creation_edit" id="date_creation_edit"
                                placeholder="date de creation" value="<?php echo $row['date_creation']; ?>"
                                style="font-size:90%" required>
                        </div>
                        <div class="col">
                            <label for="scan_lp_edit" class="col-form-label">Scan du LP (facultatif):</label>
                            <input type="file" class="form-control" name="scan_lp_edit" id="scan_lp_edit"
                                placeholder="Scan du Laisser-passer" style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="quantite" class="col-form-label">Quantite:</label>
                            <input type="number" class="form-control" name="quantite" id="quantite"
                                placeholder="Quantite dans LP1" step="0.01" value="<?php echo $row['quantite']; ?>"
                                style="font-size:90%" required>
                        </div>
                        <div class="col">
                            <label for="unite" class="col-form-label">Unite:</label>
                            <select id="unite" class="form-select" name="unite" placeholder="Choisir ..."
                                autocomplete="off" required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <option value="kg" <?= isSelected('kg', $selectedUnite) ?>>kg</option>
                                <option value="g" <?= isSelected('g', $selectedUnite) ?>>g</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 hidden" id="row_edit1">
                        <div class="row">
                            <div class="col">
                                <label for="type_permis_edit" class="col-form-label">Type du permis:</label>
                                <select id="type_permis_edit" class="form-select" name="type_permis_edit"
                                    placeholder="Choisir ..." autocomplete="off" style="font-size:90%">
                                    <option value="">Choisir ...</option>
                                    <option value="PRE" <?= isSelecteded('PRE', $selectedPermis) ?>>PRE</option>
                                    <option value="PE" <?= isSelecteded('PE', $selectedPermis) ?>>PE</option>
                                    <option value="ZE" <?= isSelecteded('ZE', $selectedPermis) ?>>ZE</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="num_permis_edit" class="col-form-label">Numéro du permis:</label>
                                <input type="text" class="form-control" name="num_permis_edit" id="num_permis_edit"
                                    placeholder="Numéro du permis" value="<?php echo $row['numero_permis']; ?>"
                                    style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="nom_substance_edit" class="col-form-label">Nom de la substance:</label>
                                <input type="text" class="form-control" name="nom_substance_edit"
                                    id="nom_substance_edit" value="<?php echo $row['nom_substance']; ?>"
                                    placeholder="Nom de la substance" style="font-size:90%">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 hidden" id="row_edit2">
                        <label for="num_autorisation_edit" class="col-form-label">Numéro de l'autorisation:</label>
                        <input type="text" class="form-control" name="num_autorisation_edit" id="num_autorisation_edit"
                            placeholder="Numéro de l'autorisation" value="<?php echo $row['numero_autorisation']; ?>"
                            style="font-size:90%">
                    </div>
                    <div class="mb-3 hidden" id="row_edit3">
                        <label for="nom_commercant_edit" class="col-form-label">Nom du commerçant:</label>
                        <input type="text" class="form-control" name="nom_commercant_edit" id="nom_commercant_edit"
                            placeholder="Nom du commerçant" value="<?php echo $row['nom_commercant']; ?>"
                            style="font-size:90%">

                    </div>
                    <div class="mb-3 hidden" id="row_edit4">
                        <label for="nom_exporteur_edit" class="col-form-label">Nom de l'exporteur:</label>
                        <input type="text" class="form-control" name="nom_exporteur_edit" id="nom_exporteur_edit"
                            placeholder="Nom de l'exporteur" value="<?php echo $row['nom_exporteur']; ?>"
                            style="font-size:90%">
                    </div>
                    <div class="mb-3 hidden" id="row_edit5">
                        <label for="nom_transformateur_edit" class="col-form-label">Nom du transformateur:</label>
                        <input type="text" class="form-control" name="nom_transformateur_edit"
                            id="nom_transformateur_edit" value="<?php echo $row['nom_transformateur']; ?>"
                            placeholder="Nom de l'exporteur" style="font-size:90%">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal1()">Fermer</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function showFields() {
        var selectElement = document.getElementById('type_lp_edit');
        var selectedValue = selectElement.options[selectElement.selectedIndex].value;

        $('#row_edit1, #row_edit2, #row_edit3, #row_edit4, #row_edit5').addClass('hidden');
        $('#type_permis_edit, #numero_permis_edit, #nom_substance_edit, #num_autorisation_edit, #nom_commercant_edit, #num_exporteur_edit, #num_transformateur_edit')
            .attr('required', false);

        if (selectedValue === 'LPS') {
            $('#row_edit2').removeClass('hidden');
            $('#num_autorisation_edit').attr('required', true);
        } else if (selectedValue === 'LPIIIC') {
            $('#row_edit3').removeClass('hidden');
            $('#nom_commercant_edit').attr('required', true);
        } else if (selectedValue === 'LPIIIE') {
            $('#row_edit4').removeClass('hidden');
            $('#num_exporteur_edit').attr('required', true);
        } else if (selectedValue === 'LPII') {
            $('#row_edit5').removeClass('hidden');
            $('#num_transformateur_edit').attr('required', true);
        } else if (selectedValue === 'LPIFOLIO') {
            $('#row_edit1').removeClass('hidden');
            $('#type_permis_edit, #numero_permis_edit, #nom_substance_edit').attr('required', true);
        }
    }

    // Appeler la fonction au chargement du document pour gérer les valeurs déjà sélectionnées
    showFields();

    // Attacher l'événement onchange à la liste déroulante
    $('#type_lp_edit').change(function() {
        showFields();
    });
});

function closeModal1() {
    var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop2'), {
        backdrop: 'static',
        keyboard: false
    });
    myModal.hide();
}


// Fonction pour pré-sélectionner une valeur
function preselectValue(value) {
    var selectElement = document.getElementById('type_lp_edit');
    selectElement.value = value;
    showFields(); // Appel de la fonction pour mettre à jour les champs en conséquence
}

// Exemple d'appel de la fonction avec une valeur prédéfinie
// Vous pouvez appeler cette fonction au chargement de la page ou selon votre logique de modification
document.addEventListener('DOMContentLoaded', function() {
    preselectValue('LPIIIC'); // Remplacez 'LPIIIC' par la valeur que vous souhaitez pré-sélectionner
});
</script>