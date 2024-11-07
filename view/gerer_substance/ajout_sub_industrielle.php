<?php 
include "../db_connect.php";
?>
<?php 
if (isset($_POST['submit'])) {
    // $substance = $_POST['substance'];
    $id_substance = isset($_POST["substance"]) ? $_POST["substance"] : array();
    $forme = isset($_POST["forme"]) ? $_POST["forme"] : array(); 
    $durete = isset($_POST["durete"]) ? $_POST["durete"] : array(); 
    $dimension = isset($_POST["dimension"]) ? $_POST["dimension"] : array();
    $granulo = isset($_POST["granulo"]) ? $_POST["granulo"] : array();
    $transparence = isset($_POST["transparence"]) ? $_POST["transparence"] : array();
    $caracteristique = isset($_POST["caracteristique"]) ? $_POST["caracteristique"] : array();
    $durete = isset($_POST["durete"]) ? $_POST["durete"] : array();
    $categorie = isset($_POST["categorie"]) ? $_POST["categorie"] : array();
    $dimension = isset($_POST["dimension"]) ? $_POST["dimension"] : array();
    $famille = isset($_POST["famille"]) ? $_POST["famille"] : array(); 
    $unite = $_POST['unite'];
    $id_detail = $_POST['id'];
    $nomenclature = isset($_POST["nomenclature"]) ? $_POST['nomenclature'] : array();
    $case = isset($_POST["case"]) ? $_POST['case'] : array();
    
    // Préparer la requête SQL
    // $subInsertQuery = "INSERT INTO `substance`( `nom_substance`, `id_type_substance`) VALUES ('$substance','$id_type_substance')";
    // $conn->query($subInsertQuery);
    // $id_substance = $conn->insert_id; 

    // Parcourir les tableaux pour insérer les données non vides
   function generateCombinations($arrays) {
    $result = [[]];
    foreach ($arrays as $array) {
        $temp = [];
        foreach ($result as $res) {
            foreach ($array as $item) {
                $temp[] = array_merge($res, [$item]);
            }
        }
        $result = $temp;
    }
    return $result;
}

// Générer toutes les combinaisons
$combinations = generateCombinations([
    $id_substance,
    $categorie,
    $granulo,
    $famille,
    $transparence,
    $case,
    $forme,
    $durete,
    $dimension,
    $nomenclature,
    $caracteristique
]);

// Préparer la requête SQL
$sql = "INSERT INTO industrielle (
    id_substance,
    id_granulo,
    id_transparence,
    id_caracteristique,
    id_forme_substance,
    id_durete,
    id_categorie,
    id_dimension_diametre,
    id_famille,
    id_nomenclature,
    id_case,
    unite
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Erreur de préparation de la requête : ' . $conn->error);
}

// Insérer chaque combinaison dans la base de données
foreach ($combinations as $combination) {
    // Assurez-vous que toutes les clés existent dans $combination et qu'elles sont définies
    $id_substance = $combination[0] ?? null;
    $id_granulo = $combination[1] ?? null;
    $id_transparence = $combination[2] ?? null;
    $id_caracteristique = $combination[3] ?? null;
    $id_forme_substance = $combination[4] ?? null;
    $id_durete = $combination[5] ?? null;
    $id_categorie = $combination[6] ?? null;
    $id_dimension_diametre = $combination[7] ?? null;
    $id_famille = $combination[8] ?? null;
    $id_nomenclature = $combination[9] ?? null;
    $id_case = $combination[10] ?? null;
    $unite = $combination[11] ?? null;

    // Lier les paramètres
    $stmt->bind_param(
        "iiiiiiiiiiis", // Types des paramètres (i = integer, s = string)
        $id_substance,
        $id_granulo,
        $id_transparence,
        $id_caracteristique,
        $id_forme_substance,
        $id_durete,
        $id_categorie,
        $id_dimension_diametre,
        $id_famille,
        $id_nomenclature,
        $id_case,
        $unite
    );

    // Exécuter la requête
    if (!$stmt->execute()) {
        echo "Erreur d'exécution : " . $stmt->error . "\n";
    } else {
        echo "Insertion réussie\n";
    }
}

// Fermer la déclaration
$stmt->close();

// Fermer la connexion
$conn->close();
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- TomSelect JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<!-- Votre script personnalisé -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!--Font awesome-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="style.css">
<!--Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
// Attend que le document soit prêt
$(document).ready(function() {
    // Affiche le modal automatiquement
    $('#staticBackdrop').modal('show');
});
</script>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class=" modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle substance</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col">
                            <label for="nomenclature" name="nomenclature" class="col-form-label">Nomenclature: <span
                                    style="color:rgb(247, 62, 6)">*</span></label>
                            <select id="nomenclature" name="nomenclature[]" placeholder="Choisir ..."
                                autocomplete="off">
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT * FROM nomenclature";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_nomenclature'] . "'>" . $rowSub['nom_nomenclature'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="famille" name="famille" class="col-form-label">Famille:</label>
                            <select id="famille" name="famille[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir...</option>
                                <?php    
                                $query = "SELECT * FROM famille";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_famille'] . "'>" . $rowSub['nom_famille'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="substance" name="substance" class="col-form-label">Nom de la
                                substance:</label>
                            <select id="substance" name="substance[]" placeholder="Choisir ..." autocomplete="off"
                                multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $queryAZ = "SELECT * FROM substance";
                                $stmtAZ = $conn->prepare($queryAZ);
                                $stmtAZ->execute();
                                $resuAZ = $stmtAZ->get_result();
                                while ($rowSub = $resuAZ->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_substance'] . "'>" . $rowSub['nom_substance'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="categorie" name="categorie" class="col-form-label">Catégorie:</label>
                            <select id="categorie" name="categorie[]" placeholder="Choisir ..." autocomplete="off"
                                required multiple>
                                <option value="">Choisir</option>
                                <?php    
                                $query = "SELECT id_categorie, nom_categorie FROM categorie";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_categorie'] . "'>" . $rowSub['nom_categorie'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="granulo" name="granulo" class="col-form-label">Granulomètrie:</label>
                            <select id="granulo" name="granulo[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_granulo, nom_granulo FROM granulo";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_granulo'] . "'>" . $rowSub['nom_granulo'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="transparence" name="transparence" class="col-form-label">Transparence:</label>
                            <select id="transparence" name="transparence[]" placeholder="Choisir ..." autocomplete="off"
                                multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_transparence, nom_transparence FROM transparence";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_transparence'] . "'>" . $rowSub['nom_transparence'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="caracteristique" name="caracteristique"
                                class="col-form-label">Caractéristique:</label>
                            <select id="caracteristique" name="caracteristique[]" placeholder="Choisir ..."
                                autocomplete="off" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT * FROM caracteristique";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_caracteristique'] . "'>" . $rowSub['nom_caracteristique'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="durete" name="durete" class="col-form-label">Dureté de la substance:</label>
                            <select id="durete" name="durete[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_durete, nom_durete FROM durete";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_durete'] . "'>" . $rowSub['nom_durete'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="dimension" name="dimension" class="col-form-label">Dimension ou
                                Diamètre:</label>
                            <select id="dimension" name="dimension[]" placeholder="Choisir ..." autocomplete="off"
                                multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_dimension_diametre, nom_dimension_diametre FROM dimension_diametre";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_dimension_diametre'] . "'>" . $rowSub['nom_dimension_diametre'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="forme" name="forme" class="col-form-label">Forme de la substance:</label>
                            <select id="forme" name="forme[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir...</option>
                                <?php    
                                $query = "SELECT id_forme_substance, nom_forme_substance FROM forme_substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_forme_substance'] . "'>" . $rowSub['nom_forme_substance'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="case" name="case" class="col-form-label">Nombre de case:</label>
                            <select id="case" name="case[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT * FROM `case`";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_case'] . "'>" . $rowSub['nom_case'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="unite" name="unite" class="col-form-label">Unité de la substance:</label>
                            <select class="form-select" id="unite" name="unite" aria-label="Default select example"
                                required>
                                <option selected>Choisir ...</option>
                                <option value="ct">US $ / Carat</option>
                                <option value="g">US $ / Gramme</option>
                                <option value="kg">US $ / Kilogramme</option>
                                <option value="boite">US $ / Boite</option>
                            </select>
                        </div>
                        <input type="hidden" id="id" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    selectTom();
});

function selectTom() {
    // Initialisez TomSelect pour chaque élément select


    var selectOptions = {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };
    new TomSelect("#nomenclature", selectOptions);
    new TomSelect("#substance", selectOptions);
    new TomSelect("#categorie", selectOptions);
    new TomSelect("#granulo", selectOptions);
    new TomSelect("#transparence", selectOptions);
    new TomSelect("#caracteristique", selectOptions);
    new TomSelect("#durete", selectOptions);
    new TomSelect("#dimension", selectOptions);
    new TomSelect("#forme", selectOptions);
    new TomSelect("#case", selectOptions);
    new TomSelect("#famille", selectOptions);

};
</script>
<script>
new TomSelect('select[multiple]', {
    plugins: {
        remove_button: {
            title: 'Supprimer'
        }
    }
})
</script>