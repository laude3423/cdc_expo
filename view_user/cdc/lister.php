<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');


?>

<?php
if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;

    if (isset($_POST['submit'])) {
        $expediteur = $_POST['expediteur'];
        $importateur = $_POST["importateur"];
        $id_data = $_POST["facture"];
        $lieu_embarquement = $_POST["lieu_emb"];
        $num_domiciliation = $_POST["numDom"];
        $num_fiche_declaration = $_POST["declaration"];
        $date_fiche_declaration = $_POST["date_declaration"];
        $num_lp3e = $_POST["num_lp3"];
        $date_lp3e = $_POST["date_lp3"];
        $chef = $_POST["chef"];
        $qualite = $_POST["qualite"];
        $mode_emballage= $_POST["mode_emballage"];
        $lieu_controle= $_POST["lieu_controle"];
        
        
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');

        $date_creation="";
        $num_pv_controle="";
        $num_pv="";
        $codeSql="SELECT date_creation_pv_controle, num_pv_controle FROM data_cc WHERE id_data_cc = (SELECT MAX(id_data_cc) FROM
        data_cc)
        AND num_pv_controle IS NOT NULL";
        $resultCode = mysqli_query($conn, $codeSql);
        $incrementation = 0;
        if($resultCode){
            if(mysqli_num_rows($resultCode) > 0) {
                $rowss = mysqli_fetch_assoc($resultCode);
                $date_creation = $rowss['date_creation_pv_controle'];
                $num_pv_controle = $rowss['num_pv_controle'];
                $parts = explode("-", $num_pv_controle);
                if(count($parts) === 2) {
                $incrementation = substr($parts[0], 2); // "0001"
                } else {
                echo "La chaîne n'a pas pu être divisée comme prévu.";
                }
                $nouvelle_incrementation = intval($incrementation) + 1;
                $nouvelle_incrementation_formattee = sprintf("%03d", $nouvelle_incrementation);
                $anneeFacture = date('Y', strtotime($date_creation));
                $moisFacture = date('m', strtotime($date_creation));
                if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
                $num_pv = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/DEV/DIR";
                }else{
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/DIR";
                }
            } else {
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/DIR";
            }
            
        }
        // recherche
        $query = "SELECT num_pv_controle FROM data_cc WHERE id_data_cc=$id_data";
        $result = mysqli_query($conn, $query);
        if($result){
        $row= mysqli_fetch_assoc($result);
        if($row["num_pv_controle"]==''){
            //include "../generate_fichier/generate_insert_controle.php?date_fiche_declaration=$date_fiche_declaration&num_fiche_declaration=$num_fiche_declaration&lieu_embarquement=$lieu_embarquement";
            include "../generate_fichier/generate_insert_controle.php";
            include "./traitement.php";
                
            }else{
                $_SESSION['toast_message2'] = "Le numéro de facture que vous avez choisi est déjà enregistré.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
                
            }
        }
        
    }
    //modification
    
    if(isset($_SESSION['toast_message'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
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
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
}
if(isset($_SESSION['toast_message2'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="../../view/images/warning.jpeg" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                    <small class="text-muted">Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . $_SESSION['toast_message2'] . '
                </div>
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
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>

    <title>Ministere des mines</title>
    <?php 
    include "../../shared/header.php";
    ?>
    <style>
    #spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    </style>


</head>

<body>
    <div class="container">
        <div class="row mb-3" style="margin-top: 30px;">
            <div class="col md-8 mb-3">
                <h5>Liste des cértificats de conformité</h5>
            </div>
            <div class="col md-10 text-end">
                <a class="btn btn-success btn-sm rounded-pill px-3 mb-3" href="./exporter.php?">Exporter en excel</a>

            </div>
        </div>
        <?php 
            $sql='';
            if($groupeID===2){
                $sql="SELECT datacc.*, societe_imp.*, societe_exp.*
                  FROM data_cc datacc
                  INNER JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
                  INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
                  LEFT JOIN users us ON us.id_user=datacc.id_user
                  LEFT JOIN direction di ON us.id_direction = di.id_direction 
                  WHERE datacc.num_cc IS NOT NULL
                  ORDER BY datacc.date_cc DESC";
            }else{
                $sql="SELECT datacc.*, societe_imp.*, societe_exp.*
                  FROM data_cc datacc
                  INNER JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
                  INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
                  LEFT JOIN users us ON us.id_user=datacc.id_user
                  LEFT JOIN direction di ON us.id_direction = di.id_direction 
                  WHERE datacc.num_cc IS NOT NULL AND di.id_direction=$id_direction
                  ORDER BY datacc.date_cc DESC";
            }
                
                $result= mysqli_query($conn, $sql);
                 if ($result->num_rows > 0) {
                    ?><table class=" table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Numéro de CDC</th>
                    <th scope="col">Société expéditeur</th>
                    <th scope="col">Numéro Facture</th>
                    <th scope="col">Numéro DOM</th>
                    <th scope="col">Société importateur</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php  while($row = mysqli_fetch_assoc($result)){
                    ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $row['num_cc'] ?></td>
                    <td><?php echo $row['nom_societe_expediteur'] ?></td>
                    <td><?php echo $row['num_facture'] ?></td>
                    <td><?php echo $row['num_domiciliation'] ?></td>
                    <td><?php echo $row['nom_societe_importateur'] ?></td>
                    <td>
                        <a class="link-dark detail_pv_scellage"
                            href="../pv_controle/detail.php?id=<?php echo $row['id_data_cc']?>">détails</a>

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
    </div>


    <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</body>

</html>