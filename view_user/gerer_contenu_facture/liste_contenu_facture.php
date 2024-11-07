<?php

// Connexion à la base de données
require(__DIR__ . '/../../scripts/db_connect.php');
require(__DIR__ . '/../../scripts/session.php');
include '../../histogramme/insert_logs.php';
if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
$validation_v = $fonctionUsers. ' ' . $nom_user. ' '.$prenom_user;
$id_data_cc='';
if (isset($_GET['id'])) {
    $id_data_cc= $_GET['id'];

    $sql = "SELECT dcc.*, sexp.*, simp.*, user.*
    FROM data_cc dcc
    LEFT JOIN users AS user ON user.id_user= dcc.id_user
    LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
    LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
    WHERE dcc.id_data_cc = $id_data_cc;
    ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row_1 = $result->fetch_assoc();
        $num_facture = $row_1["num_facture"];
        $date_facture = $row_1["date_facture"];

        $nom_societe_expediteur = $row_1["nom_societe_expediteur"];
        $adresse_societe_expediteur = $row_1["adresse_societe_expediteur"];
        $nif_societe_expediteur = $row_1["nif_societe_expediteur"];
        $contact_societe_expediteur = $row_1["contact_societe_expediteur"];
        $email_societe_expediteur = $row_1["email_societe_expediteur"];

        $nom_societe_importateur = $row_1["nom_societe_importateur"];
        $adresse_societe_importateur = $row_1["adresse_societe_importateur"];
        $contact_societe_importateur = $row_1["contact_societe_importateur"];
        $email_societe_importateur = $row_1["email_societe_importateur"]; 
        $pays_destination = $row_1["pays_destination"];
        $num_pv_scellage=$row_1['num_pv_scellage'];
        $num_pv_controle=$row_1['num_pv_controle'];
        $pj_facture=$row_1['pj_facture'];
        $id_contenu_fact= $row_1['id_contenu_facture']?? "";
        $validation_facture=$row_1['validation_facture'] ?? "";
        $nom_users = $row_1['fonction'] ?? "";
        $nom_utilisateur = $row_1['nom_user']. ' '.$row_1['prenom_user'];
        $user_validation_facture=$row_1['user_validation_facture'];
    }

}
if (isset($_POST['submit'])) {
    $activite="Validation d'une contenue de la facture";
        $id_data_cc = $_POST['id_data'];
        $action = $_POST['action'];
        $sql="UPDATE `data_cc` SET `validation_facture`='$action', `user_validation_facture`='$validation_v' WHERE id_data_cc=$id_data_cc";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            insertLogs($conn, $userID, $activite);
            $_SESSION['toast_message'] = "Modification réussie.";
             header("Location: ./liste_contenu_facture.php?id=" . $id_data_cc);
             //header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    }
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
?>
<?php 

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <title>Ministere des mines</title>
    <style>
    .partie {
        display: inline;
    }

    .partie1 .partie2 {
        display: inline;
    }

    #agentTable {
        display: none;
    }

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

    .th .td {
        font-size: small;
    }

    .h4 {
        font-size: 20px;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    #infon1 #info2 {
        display: inline-block;
    }

    .info1 {
        width: 49%;
        float: left;

    }

    .link-dark {
        margin: 0;
        padding: 0;
    }

    .btn-sow-contenu,
    .btn-edit-contenu-facture,
    .link-dark {
        display: inline-block;
        vertical-align: middle;
    }

    .info2 {
        width: 49%;
        float: right;

    }

    @media screen and (max-width: 800px) {

        .infon1,
        .info2 {
            display: block;
        }

        .info1 {
            width: 100%;
        }

        .info2 {
            width: 100%;
        }
    }

    .bfooter {
        margin-left: 40%;
        position: fixed;
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
    <?php include_once('../../view/shared/navBar.php'); 
   
    $compte="";?>

    <div class=" info container">
        <?php  include('./add_contenu_facture.php'); ?>
        <div id="edit_contenu_facture_form"></div>
        <div id="ajout_pv_controle_form"></div>

        <div id="ajout_pv_contenu_dire"></div>
        <div id="ajout_pv_scellage_form"></div>
        <div id="sow_contenu_form"></div>
        <h6 style="text-align: center;">Liste des contenus de factures N° <?php echo $num_facture;?> du
            <?php echo date('d/m/Y', strtotime($date_facture));?></h6>
        <hr>
        <div class="partie d-flex justify-content-between align-items-center">
            <div class="partie1">
                <?php 
                    $query12 = "SELECT dcc.id_data_cc FROM contenu_facture cfac
                        INNER JOIN data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
                        WHERE dcc.id_data_cc = $id_data_cc";
                        
                        $result = $conn->query($query12);
                        if ($result->num_rows > 0) {
                            $compte="existe";
                            if($groupeID === 3){
                                if(!empty($num_pv_controle)&&!empty($num_pv_scellage)){
                                    echo '
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="../pv_scellage/detail.php?id=' . $id_data_cc.'">Voir PV de scellage</a></li>
                                                <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de controle</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="../pv_controle/detail.php?id=' . $id_data_cc.'">Voir la certificat de conformité</a></li>
                                            </ul>
                                        </div>
                                    ';
                                }else if(!empty($num_pv_controle)){
                                    echo '
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de controle</a></li>
                                            </ul>
                                        </div>
                                    ';
                                }else{
                                    if($validation_facture == 'Validé'){
                                        echo '<a class="btn btn-dark rounded-pill px-3 btn-ajout_pv_controle" data-id="' . $id_data_cc . '">Générer PV contrôle</a>';
                                    }
                                }
                            } else if($groupeID===1) {
                                if((empty($num_pv_controle) &&($validation_facture=='Validé'))){
                                    echo '
                                            <a class="btn btn-dark rounded-pill px-3 btn-ajout_pv_controle" data-id="' . $id_data_cc . '">Générer PV contrôle</a>
                                        ';
                                }else if($num_pv_controle){
                                    echo '<div class="dropdown">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de controle</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="../pv_controle/detail.php?id=' . $id_data_cc.'">Voir la certificat de conformité</a></li>
                                            </ul>
                                        </div>';
                                }
                            }else{
                                echo '
                                            <a class="btn btn-dark rounded-pill px-3 btn-ajout_pv_controle" data-id="' . $id_data_cc . '">Générer PV contrôle</a>
                                        ';
                                if(!empty($num_pv_controle)&&!empty($num_pv_scellage)){
                                    echo '
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="../pv_scellage/detail.php?id=' . $id_data_cc.'">Voir PV de scellage</a></li>
                                                <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de controle</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="../pv_controle/detail.php?id=' . $id_data_cc.'">Voir la certificat de conformité</a></li>
                                            </ul>
                                        </div>
                                    ';
                                }else if(!empty($num_pv_controle)){
                                    echo '
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de controle</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="../pv_controle/detail.php?id=' . $id_data_cc.'">Voir la certificat de conformité</a></li>
                                            </ul>
                                        </div>
                                    ';
                                }
                            }
                        }
                    
                    ?>
            </div>

            <div class=" partie2 text-end">
                <a class="btn btn-success rounded-pill px-3"
                    href="./exporter_contenu.php?id_data_cc=<?= $id_data_cc ?>"><i class="fas fa-file-excel"></i>
                    Exporter en excel</a>

                <?php
                        if ($groupeID !== 2) {
                            // $date_depart_obj = empty($date_depart) ? (new DateTime())->modify('+2 days') : new DateTime($date_depart);
                            // $date_today_obj = new DateTime('now');
                            //         // Comparer les dates
                            // if ( $date_depart_obj >  $date_today_obj ) {
                                if($validation_facture !='Validé'){
                                     if($groupeID===1){
                                        echo '
                                                <a class="btn btn-dark rounded-pill px-3 btn-add-contenu-dire"
                                                data-id-data-cc="' . $id_data_cc . '"><i
                                                class="fa-solid fa-add me-1"></i>Ajouter un contenu</a>';
                                        }else{
                                             echo '
                                            <a class="btn btn-dark rounded-pill px-3  btn-add-contenu-facture"
                                            data-id-data-cc="' . $id_data_cc . '"><i
                                            class="fa-solid fa-add me-1"></i>Ajouter un contenu</a>';
                                        }
                                }
                            // }
                            
                        }
                        ?>
            </div>
        </div>
        <hr>
        <?php if(!empty($compte)){
        if((empty($num_pv_controle)&&(($code_fonction=='A')||$code_fonction=='B')&&(($validation_facture =='En attente')||(empty($validation_facture))))){
        ?>
        <form action="" method="post">
            <?php
            // Supposons que $selectedValue contient la valeur récupérée de la base de données.
            $selectedValue = $validation_facture; // Exemple de valeur
            function isSelected($value, $selectedValue) {
                return $value === $selectedValue ? 'selected' : '';
            }
            ?>
            <div class="row">
                <div class="col">
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data" id="id_data">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire</option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php
        }else {
            if($groupeID===2){
               ?>
        <form action="" method="post">
            <?php
             function isSelected($value, $selectedValue) {
                return $value === $selectedValue ? 'selected' : '';
            }
                    // Supposons que $selectedValue contient la valeur récupérée de la base de données.
                    $selectedValue = $validation_facture; // Exemple de valeur
                    ?>
            <div class="row">
                <div class="col">
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data" id="id_data">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire</option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php 
            }else if(empty($validation_facture)||($validation_facture=='En attente')){
                echo '<p class="alert alert-info">Status: En attente.</p>';
            }else {
                echo '<p class="alert alert-info">Status: '.$validation_facture.', Validateur: '.$user_validation_facture.'.</p>';
            }
        }
    }?>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-light" role="alert">
                    <strong class="alert-heading">EXPEDITEUR </strong>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-light">
                            <strong>Nom du société: </strong> <?php echo $nom_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Adresse: </strong> <?php echo $adresse_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>NIF: </strong> <?php echo $nif_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Contact: </strong> <?php echo $contact_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>E-mail: </strong> <?php echo $email_societe_expediteur; ?>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-light" role="alert">
                    <strong class="alert-heading">IMPORTATEUR</strong>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-light">
                            <strong>Nom du société : </strong> <?php echo $nom_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Adresse : </strong> <?php echo $adresse_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Contact : </strong> <?php echo $contact_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Mail : </strong> <?php echo $email_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Pays de destination : </strong> <?php echo $pays_destination;?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>

        <?php
        $sql="SELECT * FROM contenu_facture WHERE id_data_cc = $id_data_cc";
        $result = $conn->query($sql);
        $montant=0;
        $poidsTotal = 0;$poidsTotal_g=0;$poidsTotal_kg=0;
        while($row1 = mysqli_fetch_assoc($result)){
            if ($row1['unite'] == "ct") {
                $montant += floatval($row1['prix_unitaire_facture']*5) * floatval($row1['poids_facture']);
            } else if($row1['unite'] == "g"){
                $montant += floatval($row1['prix_unitaire_facture']) * floatval($row1['poids_facture']);
                
            }
            else if($row1['unite'] == "g_pour_kg"){
                $montant += floatval($row1['prix_unitaire_facture']*5000) * floatval($row1['poids_facture']);
                
            }else{
                $montant += floatval($row1['prix_unitaire_facture']) * floatval($row1['poids_facture']);
                
            }
            if ($row1['unite_poids_facture'] == "ct") {
               $poidsTotal += floatval($row1['poids_facture']) * 5;
            } else if($row1['unite_poids_facture'] == "g"){
                $poidsTotal_g += floatval($row1['poids_facture']);
            }else{
              $poidsTotal_kg += floatval($row1['poids_facture']);
            }
        }
        if(($poidsTotal > 0)&&($poidsTotal_g> 0)&&($poidsTotal_kg> 0)){
            $poidsTotal =$poidsTotal / 1000 + $poidsTotal_g / 1000 + $poidsTotal_kg;
            $unite_affiche='kg';
            echo 'consulte';
        }else if(($poidsTotal > 0)&&($poidsTotal_g > 0)){
            $poidsTotal =$poidsTotal + $poidsTotal_g;
            $unite_affiche='g';
        }else if(($poidsTotal_g > 0)&&($poidsTotal_kg> 0)) {
            $poidsTotal =$poidsTotal_g / 1000 + $poidsTotal_kg;
            $unite_affiche='kg';
        }else if(($poidsTotal> 0)&&($poidsTotal_kg> 0)) {
            $poidsTotal =$poidsTotal / 1000 + $poidsTotal_kg;
            $unite_affiche='kg';
            
        }else if($poidsTotal > 0) {;
            $unite_affiche='g';
        }else if($poidsTotal_g > 0) {;
            $poidsTotal =$poidsTotal_g;

            $unite_affiche='g';
        }else if($poidsTotal_kg > 0) {;
            $poidsTotal =$poidsTotal_kg;
            $unite_affiche='kg';
        }
        $query = "
        SELECT  cfac.*, sds.*, s.*, g.*, sds.prix_substance
        FROM contenu_facture cfac
        INNER JOIN data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
        INNER JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON sds.id_substance = s.id_substance
        LEFT JOIN granulo g ON sds.id_granulo = g.id_granulo
        WHERE dcc.id_data_cc = $id_data_cc
        ORDER BY cfac.id_contenu_facture ASC
        ";
        
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
         ?>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="info1">
            <table id="agentTable" class="table  table-hover text-center" style="font-size: small;">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"> </th>
                        <th scope="col">Designation</th>
                        <th scope="col">Poids</th>
                        <th class="masque2" scope="col">Prix unitaire</th>
                        <th class="masque2" scope="col">Prix total</th>
                        <th class="masque" scope="col">P.U</th>
                        <th class="masque" scope="col">P.T</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        while($row = mysqli_fetch_assoc($result)){
        ?>
                    <tr>
                        <td>✅</td>
                        <td><?php echo htmlspecialchars($row['nom_substance']) ?></td>
                        <?php if($row['unite']=='ct'){ ?>
                        <td><?php echo htmlspecialchars($row['poids_facture']* 5) . ' ct' ?>
                            <?php } else{ ?>
                        <td><?php echo htmlspecialchars($row['poids_facture']) . ' ' . htmlspecialchars($row['unite_poids_facture']) ?>
                            <?php }?>

                        </td>
                        <?php
                if ($row['prix_unitaire_facture'] == $row['prix_substance']) {
                    echo '<td>' . number_format($row["prix_unitaire_facture"], 2, ',', ' ') . ' US $</td>';
                } else if($row['prix_unitaire_facture'] > $row['prix_substance']){
                    echo '<td style="color: green;">' . number_format($row["prix_unitaire_facture"], 2, ',', ' ') . ' US $</td>';
                } else {
                  if($row['unite'] == 'g_pour_kg'){
                     echo '<td>' . number_format($row["prix_unitaire_facture"], 2, ',', ' ') . ' US $</td>';
                  }else{
                    echo '<td style="color: red;">' . number_format($row["prix_unitaire_facture"], 2, ',', ' ') . ' US $</td>';
                 }
                }
                if($row['unite'] == 'ct'){ ?>
                        <td><?php echo number_format($row['poids_facture'] * $row["prix_unitaire_facture"]*5, 2, ',', ' ') . ' US $'; ?>
                        </td>
                        <?php }else if($row['unite'] == 'g_pour_kg'){ ?>
                        <td><?php echo number_format($row['poids_facture'] * $row["prix_unitaire_facture"]*5000, 2, ',', ' ') . ' US $'; ?>
                        </td>
                        <?php }else{ ?>
                        <td><?php echo number_format($row['poids_facture'] * $row["prix_unitaire_facture"], 2, ',', ' ') . ' US $'; ?>
                        </td>
                        <?php }
            ?>
                        <td>
                            <a class="link-dark"
                                href="./sow_contenu.php?id=<?php echo $row['id_contenu_facture']; ?>">détails</a>
                            <?php if($validation_facture !='Validé') { ?>
                            <a class="link-dark btn-edit-contenu-facture"
                                data-id-contenu-facture="<?php echo htmlspecialchars($row["id_contenu_facture"]) ?>">
                                <i class="fa-solid fa-pen-to-square me-2"></i>
                            </a><a href="#" onclick="confirmerSuppression(<?php echo $row['id_contenu_facture']?>)"
                                class="link-dark">
                                <i class="fa-solid fa-trash"></i></a>
                            <?php } else { ?>
                            <a href="#" class="link-dark" data-toggle="tooltip" title="La facture est déjà validée">
                                <i class="fa-solid fa-pen-to-square me-3"></i>
                            </a>
                            <a href="#" data-toggle="tooltip" title="La facture est déjà validée" class="link-dark">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo "MONTANT TOTAL: ".number_format($montant, 2, ',', ' ') . ' US $ POIDS TOTAL:'.number_format($poidsTotal, 3, ',', ' ') .$unite_affiche ?>
        </div>
        <?php
            $conn->close();
            } else {
                echo '<div class="info1"><p class="alert alert-info">Aucune contenu de la facture.</p></div>';
            }
            ?>

        <div class="info2">
            <div class="alert alert-light" role="alert">
                <?php
                    if(!empty($pj_facture)){
                            $pdfFilePath = $pj_facture;
                    include "../cdc/convert.php";
                    }else{
                        echo ' <p class="alert alert-info">Aucun scan de la facture trouvé.</p>';
                    }
                    
                ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    // Filtrer la table en fonction de la sélection du menu déroulant "Nom Direction"
    $('#searchDirection').on('change', function() {
        var selectedDirection = $(this).val().toLowerCase();
        filterTable(1,
            selectedDirection); // Utilisez le numéro de l'index de colonne pour le filtrage (0-based)
    });

    // Fonction pour filtrer la table
    function filterTable(columnIndex, filterValue) {
        var table, rows, i, x;
        table = document.querySelector('.table');
        rows = table.rows;

        for (i = 1; i < rows.length; i++) {
            x = rows[i].getElementsByTagName("TD")[columnIndex];

            if (filterValue === '' || x.innerHTML.toLowerCase().indexOf(filterValue) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }

    function toggleStatus(userId) {
        var confirmation = confirm("Voulez-vous vraiment terminer la mise à jour de la facture ?");
        if (confirmation) {
            $.ajax({
                url: 'update_validation.php',
                type: 'POST',
                data: {
                    userId: userId
                },
                success: function(response) {
                    location.reload(); // Par exemple, recharger la page pour refléter les changements
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la mise à jour du statut:', error);
                }
            });
        }
    }
    </script>
    <script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.querySelector('.table');
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;

                    x = rows[i].getElementsByTagName("TD")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }

        // Activer la fonctionnalité de tri sur la colonne "Num_LP"
        $('#numLP').on('click', function() {
            sortTable(0); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Nom Direction"
        $('#nomDirection').on('click', function() {
            sortTable(1); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Nom Convoyeur"
        $('#nomConvoyeur').on('click', function() {
            sortTable(2); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Nom Substance"
        $('#nomSubstance').on('click', function() {
            sortTable(3); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Status"
        $('#status').on('click', function() {
            sortTable(4); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $(".btn-sow-contenu").click(function() {
            var id_data = $(this).data('id');
            showEditForm('sow_contenu_form', './sow_contenu.php?id=' +
                id_data, 'staticBackdrop');

        });
        // Afficher le formulaire modal lorsqu'on clique sur le bouton
        $(".btn-add-contenu-facture").click(function() {
            id_data_cc = $(this).data('id');
            $("#add_contenu_facture").modal('show');
            $("#id_data_cc").val(id_data_cc);
        });
        $(".btn-add-contenu-dire").click(function() {
            id_data_cc = $(this).data('id-data-cc'); // Correction ici
            showEditForm('ajout_pv_contenu_dire', './add_contenu_direction.php?id=' + id_data_cc,
                'staticBackdrop3');
        });
        $(".btn-ajout_pv_controle").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('ajout_pv_controle_form', '../pv_controle/ajout_pv_controle.php?id=' +
                id_data_cc, 'staticBackdrop');

        });
        $(".btn-ajout_pv_scellage").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('ajout_pv_scellage_form', '../pv_scellage/ajout_pv.php?id=' +
                id_data_cc, 'staticBackdrop');

        });
    });

    function showEditForm(editFormId, scriptPath, modalId) {
        $("#" + editFormId).load(scriptPath, function() {
            // Après le chargement du contenu, initialisez le modal manuellement
            $("#" + modalId).modal('show');
        });
    }

    function confirmerSuppression(id) {
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");
        console.log(id);
        if (confirmation) {
            $.ajax({
                url: 'delete.php',
                method: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Suppression réussie.');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression : ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression : ' + error);
                    alert('Erreur lors de la suppression : ' + error);
                }
            });
        }
    }
    </script>

    <script>
    $(document).ready(function() {
        $('.toast').toast('show');

        $(".btn-edit-contenu-facture").click(function() {
            var id_contenu_facture = $(this).data('id-contenu-facture');
            // Assure-toi que les éléments HTML existent avant d'appeler showEditForm
            $("#edit_contenu_facture_form").load('edit_contenu_facture.php?id=' +
                id_contenu_facture,
                function() {
                    // Détache l'événement click du bouton après l'ouverture du modal
                    $(this).off('click');
                    // Initialise le modal
                    $("#edit_contenu_facture").modal('show');
                });
        });
    });
    </script>


    <!-- Inclure les fichiers JavaScript de Bootstrap 5 (pour le bon fonctionnement des composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>