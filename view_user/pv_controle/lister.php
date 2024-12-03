<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
require('../../histogramme/insert_logs.php');
$currentYear = date('Y');
$years = range($currentYear - 6, $currentYear);
$annee = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;
if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
$activite="Génération d'un nouvel PV de contrôle";
?>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $expediteur = $_POST['expediteur'];
        $importateur = $_POST["importateur"];
        $id_data = $_POST["id"];
        $mode_emballage = htmlspecialchars($_POST["mode_emballage"]);
        $lieu_controle = htmlspecialchars($_POST["lieu_controle"]);
        $lieu_embarquement = htmlspecialchars($_POST["lieu_emb"]);
        // $num_domiciliation = $_POST["numDom"];
        $num_fiche_declaration = htmlspecialchars($_POST["declaration"]);
        $date_declaration = $_POST["date_declaration"];
        $num_lp3e = htmlspecialchars($_POST["num_lp3"]);
        $date_lp3e = $_POST["date_lp3"];
        // $chef = $_POST["chef"];
        // $qualite = $_POST["qualite"];
        // $dateDom=$_POST['date_dom'];
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $dateInsert = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');

        $date_creation="";
        $num_pv_controle="";
        $num_pv="";$num_cc="";

        $sql ="SELECT * FROM direction WHERE id_direction=$id_direction";
        $resultDir = mysqli_query($conn, $sql);
        $rowDir = mysqli_fetch_assoc($resultDir);
        $sigle = $rowDir['sigle_direction'];
        $lieu_emission = htmlspecialchars($rowDir['lieu_emission']);
        $typeDirection = htmlspecialchars($rowDir['type_direction']);
        $nomDirection = htmlspecialchars($rowDir['nom_direction']);
        $codeSql = "SELECT incrementation, date_incrementation 
            FROM incrementation 
            WHERE id_direction = $id_direction 
            AND id_incrementation = (SELECT MAX(id_incrementation) FROM incrementation WHERE id_direction = $id_direction)";
            $resultCode = mysqli_query($conn, $codeSql);
            if ($resultCode) {
                if (mysqli_num_rows($resultCode) > 0) { // Vérifie s'il y a des résultats
                    $row = mysqli_fetch_assoc($resultCode);
                    $max_incrementation = $row['incrementation'];
                    $date_incrementation = $row['date_incrementation'];
                    //
                    $nouvelle_incrementation = intval($max_incrementation) + 1;
                    $nouvelle_incrementation_formattee = sprintf("%03d", $nouvelle_incrementation);
                    $anneeFacture = date('Y', strtotime($date_incrementation));
                    $moisFacture = date('m', strtotime($date_incrementation));
                    if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
                        if($groupeID ===3){
                            $num_pv = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.PVCC";
                            $num_cc = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.CC";
                        }else{
                            $num_pv = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."-MIM/SG/DGM/$sigle.PVCC";
                            $num_cc = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."-MIM/SG/DGM/$sigle.CC"; 
                        }
                    }else{
                        $nouvelle_incrementation=1;
                        if($groupeID ===3){
                            $num_pv = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.PVCC";
                            $num_cc = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.CC";
                        }else{
                            $num_pv = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/$sigle.PVCC";
                            $num_cc = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/$sigle.CC";
                        }
                    }
                } else {
                    $nouvelle_incrementation=1;
                    // echo "Aucun résultat trouvé pour cette direction.";
                    if($groupeID===3){
                        $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.PVCC";
                        $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.CC";
                    }else{
                        $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/$sigle.PVCC";
                        $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/$sigle.CC";
                    }
                }
            } else {
                echo "Erreur dans la requête : " . mysqli_error($conn);
            }
        // recherche
        // $stmt = $conn->prepare("INSERT INTO `incrementation`( `id_data_cc`, `id_direction`, `incrementation`, `date_incrementation`) VALUES (?, ?, ?, ?)");
        // $stmt->bind_param("iiis", $id_data, $id_direction, $nouvelle_incrementation, $dateInsert);
        // $stmt->execute();

        $requette="SELECT num_pv_controle FROM data_cc WHERE id_data_cc=$id_data";
        $result = mysqli_query($conn, $requette);
        $rows = mysqli_fetch_assoc($result);
        if($groupeID===2){
            echo $num_cc.$num_pv;
        }else{
            //include "../generate_fichier/generate_insertControle.php";
            if (empty($rows['num_pv_controle'])) {
                include "../generate_fichier/generate_insertControle.php";
                include "./traitement.php";
            } else {
                $_SESSION['toast_message2'] = "Le numéro de facture que vous avez choisi est déjà enregistré.";
                header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
                exit();
            }
        }
        
    }
    //modification
    
 if(isset($_SESSION['toast_message'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="../../view/images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
                <strong class="me-auto">Notifications</strong>
                <small class="text-muted">Maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['toast_message'] . '
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
}
if(isset($_SESSION['toast_message2'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                 <img src="../../view/images/warning.jpeg" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                <small class="text-muted">Maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['toast_message'] . '
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message2']);
}
$edit_societe_details = array();

if (!empty($edit_societe_id)) {
    $sql_edit = "SELECT * FROM `categorie` WHERE `id_categorie`='$edit_societe_id'";
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

    </script>
    <title>Ministere des mines</title>
    <?php include_once('../../view/shared/navBar.php'); ?>
    <?php 
    ?>
    <style>
    #agentTable {
        display: none;
    }
    </style>
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


</head>

<body>
    <div class="container">
        <hr>
        <div class="row mb-3">
            <div class="col">
                <h5>Liste des P.V de constantation et de controle</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche par numéro...">
            </div>
            <div class="col">
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
                <a class="btn btn-success rounded-pill px-3" href="../cdc/exporter.php?"><i
                        class="fas fa-file-excel"></i> Exporter en excel</a>
            </div>
        </div>
        <hr>
        <?php
        $sql = ""; 
        if ($groupeID === 2) {
            $sql = "SELECT dcc.*, societe_imp.*, societe_exp.*
                FROM data_cc dcc
                INNER JOIN societe_importateur societe_imp ON dcc.id_societe_importateur= societe_imp.id_societe_importateur
                INNER JOIN societe_expediteur societe_exp ON dcc.id_societe_expediteur= societe_exp.id_societe_expediteur
                LEFT JOIN users us ON dcc.id_user = us.id_user
                LEFT JOIN direction di ON us.id_direction=di.id_direction
                WHERE YEAR(dcc.date_modification_pv_controle) = $annee AND dcc.num_pv_controle IS NOT NULL
            ORDER BY dcc.date_modification_pv_controle DESC";
        } else {
            $sql="SELECT dcc.*, societe_imp.*, societe_exp.*
                FROM data_cc dcc
                INNER JOIN societe_importateur societe_imp ON dcc.id_societe_importateur= societe_imp.id_societe_importateur
                INNER JOIN societe_expediteur societe_exp ON dcc.id_societe_expediteur= societe_exp.id_societe_expediteur
                LEFT JOIN users us ON dcc.id_user = us.id_user
                LEFT JOIN direction di ON us.id_direction=di.id_direction
                WHERE YEAR(dcc.date_modification_pv_controle) = $annee AND dcc.num_pv_controle IS NOT NULL AND di.id_direction=$id_direction 
                ORDER BY dcc.date_modification_pv_controle DESC";
        }
            $result= mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
            ?>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table id="agentTable" class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Numéro de PV</th>
                    <th scope="col" class="masque2">Date</th>
                    <th scope="col" class="masque2">Numéro Facture</th>
                    <th scope="col" class="masque1">Société expéditeur</th>
                    <th scope="col" class="masque1">Destination</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    while($row = mysqli_fetch_assoc($result)){
                        ?>
                <tr>
                    <?php  if( $row['validation_controle']=='Validé'){
                    ?>
                    <td>✅</td>
                    <?php  }else if($row["validation_controle"]=='À Refaire'){
                            echo'<td>❌</td>';
                        }else{?>
                    <td>⚠️</td>
                    <?php }?>
                    <td><?php echo $row['num_pv_controle'] ?></td>
                    <td class="masque2"><?php echo date('d/m/Y', strtotime($row['date_modification_pv_controle'])); ?>
                    </td>
                    <?php if(empty($row['num_facture'])){?>
                    <td class="masque2">Non commerçant</td>
                    <?php }else{ ?>
                    <td class="masque2"><?php echo $row['num_facture'] ?></td>
                    <?php }?>
                    <td class="masque1"><?php echo $row['nom_societe_expediteur'] ?></td>
                    <td class="masque1"><?php echo $row['pays_destination'] ?></td>
                    <td><?php echo $row['validation_controle'] ?></td>
                    <td>
                        <a class="link-dark"
                            href="../pv_controle_gu/detail.php?id=<?php echo $row['id_data_cc']; ?>">détails</a>
                        <?php if($groupeID !=2){
                            if ($row['validation_controle'] != 'Validé') {
                                ?>
                        <a href="#" class="link-dark btn_edit_pv_scellage"
                            data-id="<?= htmlspecialchars($row["id_data_cc"])?>"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <?php
                            } else { ?>
                        <a href="#" class="link-dark" data-toggle="tooltip"
                            title="Modification non autorisée : Le PV est déjà validé">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>
                        <?php
                            }
                        }
                        ?>

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
    <div id="edit_pv_controle_form"></div>

    <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.toast').toast('show');
        $('[data-toggle="tooltip"]').tooltip();

        $(".btn_edit_pv_controle").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('edit_pv_controle_form', './edit_pv.php?id=' + id_data_cc,
                'staticBackdrop2');

        });

        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });
    </script>
</body>

</html>