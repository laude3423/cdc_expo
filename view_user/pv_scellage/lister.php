<?php 
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');
require_once('../../histogramme/insert_logs.php');
if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
$activite="Insertion d'un nouvel PV de Scellage";

$currentYear = date('Y');
$years = range($currentYear - 6, $currentYear);
$annee = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;
?>


<?php
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $facture=$_POST['id'];
        $id_data=$_POST['id'];
        $lieu_sce = htmlspecialchars($_POST['lieu_scellage']);
        $nombre = htmlspecialchars($_POST['nombre']);
        $type_colis= htmlspecialchars($_POST["type_colis"]);
        $agent_scellage= $_POST["agent_scellage"];
        $police = $_POST["police"];
        $douane = $_POST["douane"];
        $fraude = $_POST["fraude"];
        $qualite = $_POST["qualite"];
        // $chef = $_POST["chef"];
        // $qualite = $_POST["qualite"];

        $requte="SELECT * FROM data_cc WHERE id_data_cc=$facture";
        $resultC = mysqli_query($conn, $requte);
        $rowA = mysqli_fetch_assoc($resultC);

        $monde_emballage = htmlspecialchars($rowA["mode_emballage"]);
        $lieu_emb = htmlspecialchars($rowA["lieu_embarquement_pv"]);
        $numDom = htmlspecialchars($rowA["num_domiciliation"]);
        $dateDom = $rowA["date_dom"];
        $declaration = htmlspecialchars($rowA["num_fiche_declaration_pv"]);
        $date_declaration = $rowA["date_fiche_declaration_pv"];
        $num_lp3 = htmlspecialchars($rowA["num_lp3e_pv"]);
        $date_lp3 = $rowA["date_lp3e"];
        $date_dom = $rowA["date_dom"];
        $date_facture = $rowA["date_facture"];
        $expediteur = $rowA['id_societe_expediteur'];
        $destination = $rowA['id_societe_importateur'];

        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');
        $num_pv="";
        $codeSql="SELECT num_pv_controle FROM data_cc WHERE id_data_cc=$id_data";
        $resultCode = mysqli_query($conn, $codeSql);
        $num_pv_scellage=null;
        while ($row = mysqli_fetch_assoc($resultCode)) {
            $num_pv_scellage = $row['num_pv_controle'];
        }
        if ($num_pv_scellage !== null) {
            $num_pv = preg_replace('/\.[^.]+$/', '.PVSC', $num_pv_scellage);
        }else{
            $num_pv = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.PVSC";
        }
        // execution de requête
        
        include "../generate_fichier/generate_insert_scellage.php";
         include "./traitement.php";
           
        
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

    <title>Ministere des mines</title>
    <?php include_once('../../view/shared/navBar.php'); ?>
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
                <h5>Liste des P.V de Scellage</h5>
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
                <a class="btn btn-success btn-sm rounded-pill px-3" href="../cdc/exporter.php?"><i
                        class="fas fa-file-excel"></i> Exporter en excel</a>
            </div>
        </div>
        <hr>
        <?php 
        $sql='';
        if($groupeID===2){
            $sql="SELECT datacc.*, societe_imp.*, societe_exp.* FROM data_cc datacc
            INNER JOIN  societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
            INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
            LEFT JOIN users us ON datacc.id_user = us.id_user
            LEFT JOIN direction di ON us.id_direction=di.id_direction
            WHERE YEAR(datacc.date_modification_pv_scellage) = $annee AND datacc.num_pv_scellage IS NOT NULL
            ORDER BY datacc.date_modification_pv_scellage DESC";
        }else{
            $sql="SELECT datacc.*, societe_imp.*, societe_exp.* FROM data_cc datacc
            INNER JOIN  societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
            INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
            LEFT JOIN users us ON datacc.id_user = us.id_user
            LEFT JOIN direction di ON us.id_direction=di.id_direction
            WHERE YEAR(dcc.date_modification_pv_scellage) = $annee AND datacc.num_pv_scellage IS NOT NULL AND di.id_direction=$id_direction
            ORDER BY datacc.date_modification_pv_scellage DESC";
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
                    <th scope="col">Numéro du PV</th>
                    <th class="masque2" scope="col">Date</th>
                    <th class="masque2" scope="col">Numéro Facture</th>
                    <th class="masque1" scope="col">Société expéditeur</th>
                    <th class="masque1" scope="col">Destination</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <?php  if( $row['validation_scellage']=='Validé'){
                    ?>
                    <td>✅</td>
                    <?php  }else if($row["validation_scellage"]=='À Refaire'){
                            echo'<td>❌</td>';
                        } else {?>
                    <td>⚠️</td>
                    <?php }?>
                    <td><?php echo $row['num_pv_scellage'] ?></td>
                    <td class="masque2"><?php echo date('d/m/Y', strtotime($row['date_modification_pv_scellage'])); ?>
                    </td>
                    <td class="masque2"><?php echo $row['num_facture'] ?></td>
                    <td class="masque1"><?php echo $row['nom_societe_expediteur'] ?></td>
                    <td class="masque1"><?php echo $row['pays_destination'] ?></td>
                    <td><?php echo $row['validation_scellage'] ?>
                    <td>
                        <a class="link-dark detail_pv_scellage"
                            href="detail.php?id=<?php echo $row['id_data_cc']?>">détails</a>
                        <?php if($groupeID !=2){
                            if ($row['validation_scellage'] != 'Validé') {
                                ?>
                        <a href="#" class="link-dark btn_edit_pv_scellage"
                            data-id="<?= htmlspecialchars($row["id_data_cc"])?>"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <?php
                            } else {
                                    ?>
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
                } else {
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
    <div id="edit_pv_scellage_form"></div>

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
    var myModal;
    var closeModalAfterSubmit = false; // Variable pour vérifier si la modal doit être fermée

    // Fonction pour fermer la modal et actualiser la page si nécessaire
    function closeModal() {
        if (myModal) {

            myModal.hide();
            vider();

        }
    }

    // Fonction pour confirmer la suppression
    function confirmDeletion() {
        // Ici, vous pouvez ajouter le code PHP pour effectuer la suppression
        // Par exemple, vous pouvez utiliser une requête AJAX pour appeler un script PHP de suppression
        console.log("Suppression confirmée");
        closeModal(); // Fermer la modale après la confirmation
    }
    $(document).ready(function() {
        $('.toast').toast('show');
        $('[data-toggle="tooltip"]').tooltip();
        $(".btn_edit_pv_scellage").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('edit_pv_scellage_form', './edit_pv.php?id=' + id_data_cc, 'staticBackdrop2');

        });


        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });

    function confirmerSuppression(id) {
        // Utilisation de la fonction confirm pour afficher une boîte de dialogue
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");

        // Si l'utilisateur clique sur "OK", la suppression est effectuée
        if (confirmation) {
            $.ajax({
                url: 'delete.php',
                method: 'POST', // Utilisez la méthode POST pour la suppression
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // Traitez la réponse du serveur ici
                    if (response.success) {
                        // La suppression a réussi
                        alert('Suppression réussie.');
                        // Vous pouvez également effectuer d'autres actions nécessaires après la suppression
                        location.reload();
                    } else {
                        // La suppression a échoué
                        alert('Erreur lors de la suppression : ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression : ' + error);
                }
            });
        } else {
            // Sinon, rien ne se passe
        }
    }
    </script>
</body>

</html>