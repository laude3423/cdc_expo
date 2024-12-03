<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
$currentYear = date('Y');
$years = range($currentYear - 6, $currentYear);
$annee = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;
?>
<?php
if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $id = $_POST['id'] ?? null;
    $civilite = $_POST['civilite'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $passeport = $_POST['passeport'] ?? '';
    $scan_passeport = $_POST['scan_passeport'] ?? '';
    $num_vol = $_POST['num_vol'] ?? '';
    $compagnie = $_POST['compagnie'] ?? '';
    $escale = $_POST['escale'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $poids = $_POST['poids'] ?? '';
    $unite = $_POST['unite'] ?? '';
    $matricule = $_POST['matricule'] ?? '';
    $nom_agent = $_POST['nom_agent'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';
    $prenom_agent = $_POST['prenom_agent'] ?? '';
    $id_pays = $_POST['id_pays'] ?? '';
    $facture = $_POST['facture'] ?? '';
    $id='';
    $id_vol=$_POST['num_vol'] ??"";

    // Effectuer une validation des données si nécessaire
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $dateInsert = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');

        $date_creation="";
        $num_as="";

        $sql ="SELECT * FROM direction WHERE id_direction=$id_direction";
        $resultDir = mysqli_query($conn, $sql);
        $rowDir = mysqli_fetch_assoc($resultDir);
        $sigle = $rowDir['sigle_direction'];
        $lieu_emission = $rowDir['lieu_emission'];
        $typeDirection = $rowDir['type_direction'];
        $nomDirection = $rowDir['nom_direction'];
        $codeSql="SELECT date_creation, id_autorisation, numero_autorisation FROM autorisation";
        $resultCode = mysqli_query($conn, $codeSql);

        $max_id_aut = null;
        $date_creation = null;
        $num_autorisation=null;

        while ($row = mysqli_fetch_assoc($resultCode)) {
            // Vérifier si c'est le premier élément ou si l'actuel id_aut est supérieur au max actuel
            if ($max_id_aut === null || $row['id_autorisation'] > $max_id_aut) {
                $max_id_aut = $row['id_autorisation'];
                $date_creation = $row['date_creation'];
                $num_autorisation = $row['numero_autorisation'];

            }
        }
        if($max_id_aut !== null){
            $parts = explode("-", $num_autorisation);
            // Si la chaîne a bien été divisée
            if(count($parts) === 2) {
            $incrementation = substr($parts[0], 2); // "0001"
            } else {
            echo "La chaîne n'a pas pu être divisée comme prévu.";
            }
            $nouvelle_incrementation = intval($incrementation) + 1;
            $nouvelle_incrementation_formattee = sprintf("%03d", $nouvelle_incrementation);
            $anneeFacture = date('Y', strtotime($date_creation));
            $moisFacture = date('m', strtotime($date_creation));
            echo $nouvelle_incrementation_formattee;
            if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
                $num_as = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.AS";
                   
            }else{
                $num_as = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.AS";
                    
            }
        }else{
            $num_as = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.AS";
               
        }
    // Par exemple, vérifier que les champs obligatoires ne sont pas vides
    //pj declaration
    $uploadDir = '../upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    $num_passeport = preg_replace('/[^a-zA-Z0-9]/', '-', $passeport);
    $fileName_PAS = "SCAN_PASSEPORT_" .$num_passeport.".".
    pathinfo($_FILES['scan_passeport']['name'], PATHINFO_EXTENSION);
    $uploadPath_PAS = $uploadDir . $fileName_PAS;
    //deplacement des fichier
    if (move_uploaded_file($_FILES['scan_passeport']['tmp_name'], $uploadPath_PAS)) {
    } else {
    echo "Erreur lors de l'upload du fichier.";
    }

     $num_facture = preg_replace('/[^a-zA-Z0-9]/', '-', $facture);
    $fileName_FAC = "SCAN_PASSEPORT_" .$num_facture.".".
    pathinfo($_FILES['scan_facture']['name'], PATHINFO_EXTENSION);
    $uploadPath_FAC = $uploadDir . $fileName_FAC;
    //deplacement des fichier
    if (move_uploaded_file($_FILES['scan_facture']['tmp_name'], $uploadPath_FAC)) {
    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    // Si $id est null, il s'agit d'une nouvelle autorisation
    if (empty($id)) {
        // Préparer une requête SQL pour insérer les données
        $query = "INSERT INTO autorisation (id_pays, numero_facture,scan_facture, date_depart, date_creation, date_modification, numero_autorisation, civilite, nom_porteur, prenom_porteur, numero_passeport, scan_passeport, id_vol, designation, poids, unite, id_agent_controle) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('isssssssssssisssi', $id_pays,$facture, $uploadPath_FAC, $date_depart, $dateInsert, $dateInsert, $num_as, $civilite, $nom, $prenom, $passeport, $fileName_PAS, $id_vol, $designation, $poids, $unite, $matricule);
        $result = $stmt->execute();

        if ($result) {
            $id = $conn->insert_id;
            include('./generate.php');
            //
                $query = "UPDATE autorisation SET lien_autorisation=?, pj_autorisation=? WHERE id_autorisation=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssi', $pathToSave, $pathToSavePDF, $id);
                $result = $stmt->execute();

                if ($result) {
                    $_SESSION['toast_message'] = "Modification réussie.";
                     header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification de l\'autorisation.</div>';
                }
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout de l\'autorisation.</div>';
        }
    } else {
        // Sinon, il s'agit d'une modification d'une autorisation existante
       
    }

}

    if(isset($_SESSION['toast_message'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="../images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                    <small class="text-muted">Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . $_SESSION['toast_message'] . '
                </div>
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
}
$edit_societe_details = array();

if (!empty($edit_societe_id)) {
    $sql_edit = "SELECT * FROM `degre_couleur` WHERE `id_degre_couleur`='$edit_societe_id'";
    $result_edit = mysqli_query($conn, $sql_edit);

    if ($result_edit) {
        $edit_societe_details = mysqli_fetch_assoc($result_edit);
    } else {
        echo "Erreur lors de la récupération des détails de la société" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const spinner = document.getElementById('loadingSpinner');
        const table = document.getElementById('agentTable');

        // Afficher le spinner
        spinner.style.display = 'block';
        table.style.display = 'none';

        // Simulation de chargement des données
        setTimeout(() => {
            spinner.style.display = 'none';
            table.style.display = 'table';
        }, 2000); // Changer le délai selon vos besoins
    });
    </script>
    <style>
    .hidden {
        display: none;
    }

    #agentTable {
        display: none;
    }

    td {
        font-size: small;
    }
    </style>


    <title>Ministere des mines</title>
    <?php 
    include "../header.php";
    ?>
</head>

<body>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des autorisations</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche...">
            </div>
            <div class="col-2">
                <form method="GET" action="">
                    <select id="yearSelect" class="form-select" name="id" onchange="this.form.submit()">
                        <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year; ?>" <?php echo ($year == $annee) ? 'selected' : ''; ?>>
                            <?php echo $year; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="col text-end">
                <a class="btn btn-dark btn-sm rounded-pill px-3 " href="#" onclick="openModal()"><i
                        class="fa-solid fa-add me-1"></i>Ajouter nouveau</a>
                <a class="btn btn-success btn-sm rounded-pill px-3 " href="./exporter.php?"><i
                        class="fas fa-file-excel"></i>Exporter</a>
            </div>
        </div>
        <hr>
        <?php 
                $sql="SELECT aut.*, py.* FROM `autorisation` AS aut LEFT JOIN pays AS py ON aut.id_pays=py.id_pays
                WHERE YEAR(aut.date_creation) = $annee ORDER BY date_creation DESC";
                $result= mysqli_query($conn, $sql);
                $result= mysqli_query($conn, $sql);
                 if ($result->num_rows > 0) { ?>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table id="agentTable" class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Numéro</th>
                    <th scope="col">Date</th>
                    <th class="masque2" scope="col">Expéditeur</th>
                    <th class="masque2" scope="col">Destination</th>
                    <th class="masque1" scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <?php if($row["validation_autorisation"]=='Validé'){
                            echo'<td>✅</td>';
                        }else{
                            echo'<td>⚠️</td>';
                        } ?>
                    <td><?php echo $row['numero_autorisation'] ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['date_creation'])); ?></td>
                    <td class="masque2"><?php echo $row['nom_porteur'] ?></td>
                    <td class="masque2"><?php echo $row['nom_pays'] ?></td>
                    <td class="masque1"><?php echo $row['validation_autorisation'] ?></td>
                    <td>
                        <a class="link-dark" href="./detail.php?id=<?php echo $row['id_autorisation']; ?>">détails</a>
                        <?php if($row['validation_autorisation']=='Validé') { ?>
                        <a href="#" class="link-dark" data-toggle="tooltip" title="La autorisation est déjà validée">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>

                        <?php } else {?>
                        <a href="#" class="link-dark btn_edit_autorisation"
                            data-id="<?= htmlspecialchars($row["id_autorisation"])?>">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>
                        <?php }?>
                    </td>
                </tr>
                <?php   
                    }
                 }else{
                    echo '<p class="alert alert-info">Aucun résultat trouvé.</p>';
                 }
                ?>


                <tr>
            </tbody>
        </table>
        <div>
            <?php
                include('../../shared/pied_page.php');
            ?>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle autorisation</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id">
                        <div class="row">
                            <div class="col">
                                <label for="civiliste" class="col-form-label">Civilité:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="civilite" id="visa" value="M">
                                    <label class="form-check-label" for="visa">Mademoiselle</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="civilite" id="auto" value="MM">
                                    <label class="form-check-label" for="auto">Madame</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="civilite" id="auto" value="MS">
                                    <label class="form-check-label" for="auto">Monsieur</label>
                                </div>
                            </div>
                            <div class="col">
                                <label for="date_depart" class="col-form-label">Date de départ:</label>
                                <input type="date" class="form-control" name="date_depart" id="date_depart"
                                    style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="nom" class="col-form-label">Nom de l'expéditeur:</label>
                                <input type="text" class="form-control" name="nom" id="nom" placeholder="Nom de l'agent"
                                    required style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="prenom" class="col-form-label">Prénom(s) de l'expéditeur:</label>
                                <input type="text" class="form-control" name="prenom" id="prenom"
                                    placeholder="Prénom de l'agent" style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="passeport" class="col-form-label">Passeport:</label>
                                <input type="text" class="form-control" name="passeport" id="passeport"
                                    placeholder="Numéro du passeport" required style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="scan_passeport" class="col-form-label">Scan du passeport:</label>
                                <input type="file" class="form-control" name="scan_passeport" accept=".pdf"
                                    id="scan_passeport" placeholder="Scan du passeport" required style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="facture" class="col-form-label">Numéro de la facture:</label>
                                <input type="text" class="form-control" name="facture" id="facture"
                                    placeholder="Numéro de la facture" required style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="scan_facture" class="col-form-label">Scan de la facture:</label>
                                <input type="file" class="form-control" name="scan_facture" id="scan_facture"
                                    placeholder="Scan de la facture" required style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="num_vol" class="col-form-label">Numéro du vol:</label>
                                <select id="num_vol" class="form-select" name="num_vol" placeholder="Choisir ..."
                                    autocomplete="off" required style="font-size:90%" onchange="updateFlightDetails()">
                                    <option value="">Choisir ...</option>
                                    <?php    
                                    $query = "SELECT * FROM vol";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        echo "<option value='" . $rowSub['id_vol'] . "' data-compagnie='" . $rowSub['nom_compagnie'] . "' data-escale='" . $rowSub['destination_vol'] . "'>" . $rowSub['numero_vol'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="compagnie" class="col-form-label">Nom de la compagnie:</label>
                                <input type="text" class="form-control" name="compagnie" id="compagnie"
                                    placeholder="Nom de la compagnie" required style="font-size:90%" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="escale" class="col-form-label">Escale:</label>
                                <input type="text" class="form-control" name="escale" id="escale" placeholder="Escale"
                                    required style="font-size:90%" readonly>
                            </div>
                            <div class="col">
                                <label for="continent" class="col-form-label">Continent:</label>
                                <select id="continent" class="form-select" name="continent" placeholder="Choisir ..."
                                    autocomplete="off" required style="font-size:90%" onchange="updateFlightDetails()">
                                    <option value="">Choisir ...</option>
                                    <?php    
                                    $query = "SELECT * FROM continent";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        echo "<option value='" . $rowSub['id_continent'] . "' data-pays='" . $rowSub['id_continent'] ."'>" . $rowSub['nom_continent'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="id_pays" class="col-form-label">Destination:</label>
                                <select class="form-select" id="id_pays" name="id_pays" required disabled>
                                    <option value="">Sélectionner...</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="matricule" class="col-form-label">Matricule du responsable:</label>
                                <select id="matricule" class="form-select" name="matricule" placeholder="Choisir ..."
                                    autocomplete="off" required style="font-size:90%"
                                    onchange="updateFlightDetails_agent()">
                                    <option value="">Choisir ...</option>
                                    <?php    
                                    $query = "SELECT * FROM agent_controle";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        echo "<option value='" . $rowSub['id_agent_controle'] . "' data-nom='" . $rowSub['nom_agent'] . "' data-prenom='" . $rowSub['prenom_agent'] . "'>" . $rowSub['matricule'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="col">
                                    <label for="nom_agent" class="col-form-label">Nom du responsable:</label>
                                    <input type="text" class="form-control" name="nom_agent" id="nom_agent"
                                        placeholder="Nom du responsable" required style="font-size:90%" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <label for="prenom_agent" class="col-form-label">Prénom(s) de l'agent:</label>
                                <input type="text" class="form-control" name="prenom_agent" id="prenom_agent"
                                    placeholder="Nom du responsable" required style="font-size:90%" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="designation" class="col-form-label">Colis:</label>
                                <textarea type="text" name="designation" id="designation"
                                    class="form-control"></textarea>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <label for="poids" class="col-form-label">Poids:</label>
                                        <input type="number" class="form-control" name="poids" id="poids" step="any"
                                            required style="font-size:90%">
                                    </div>
                                    <div class="col">
                                        <label for="unite" class="col-form-label">Unité:</label>
                                        <select class="form-select" id="unite" name="unite" required>
                                            <option value="">Sélectionner...</option>
                                            <option value="Kg">Kg</option>
                                            <option value="g">g</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Fermer</button>
                    <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div id="edit_autorisation_form"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.toast').toast('show');
        $(".btn_edit_autorisation").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('edit_autorisation_form', './edit.php?id=' + id_data_cc,
                'staticBackdrop2');

        });

        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
        // Lorsqu'une option est sélectionnée dans le premier menu
        $("#continent").change(function() {
            var id_continent = $(this).val();
            if (id_continent !== "") {
                $("#id_pays").prop("disabled", false);
                // Charger les pays en fonction du continent sélectionné
                ajax(id_continent);
            } else {
                // Désactiver et réinitialiser le deuxième menu déroulant
                $("#id_pays").prop("disabled", true).html(
                    "<option value=''>Sélectionner d'abord un continent...</option>");
            }
        });
    });

    function ajax(id_continent) {
        $.ajax({
            url: "get.php",
            method: "POST",
            data: {
                continent: id_continent
            },
            dataType: "json",
            success: function(data) {
                const dropdowns = [{
                    id: "#id_pays",
                    options: data.options_pays,
                    emptyMessage: "Aucune..."
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
    }

    // Appeler cette fonction lors du chargement de la page pour peupler le menu déroulant

    function openModal(edit_id = null) {
        myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });
        if (edit_id) {
            // Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs

        } else {
            // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Nouvelle autorisation';
            document.getElementById('nom').value = '';
            document.getElementById('prenom').value = '';
            document.getElementById('passeport').value = '';
            document.getElementById('scan_passeport').value = '';
            document.getElementById('num_vol').value = '';
            document.getElementById('compagnie').value = '';
            document.getElementById('escale').value = '';
            document.getElementById('id').value = '';
        }

        myModal.show();
    }

    function updateFlightDetails() {
        var selectVol = document.getElementById('num_vol');
        var compagnieInput = document.getElementById('compagnie');
        var escaleInput = document.getElementById('escale');

        var selectedOption = selectVol.options[selectVol.selectedIndex];
        var compagnie = selectedOption.getAttribute('data-compagnie');
        var escale = selectedOption.getAttribute('data-escale');

        // Mettre à jour les champs compagnie et escale
        compagnieInput.value = compagnie;
        escaleInput.value = escale;
    }

    function updateFlightDetails_agent() {
        var selectVol = document.getElementById('matricule');
        var compagnieInput = document.getElementById('nom_agent');
        var escaleInput = document.getElementById('prenom_agent');

        var selectedOption = selectVol.options[selectVol.selectedIndex];
        var compagnie = selectedOption.getAttribute('data-nom');
        var escale = selectedOption.getAttribute('data-prenom');

        // Mettre à jour les champs compagnie et escale
        compagnieInput.value = compagnie;
        escaleInput.value = escale;
    }

    function closeModal() {
        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });
        myModal.hide();
    }
    </script>
</body>

</html>