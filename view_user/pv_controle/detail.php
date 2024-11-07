<?php
    require_once('../../scripts/db_connect.php');
    include_once('../../scripts/connect_db_lp1.php');
    require_once('../generate_fichier/nombreEnLettre.php');
    require('../../scripts/session.php');
    include '../../histogramme/insert_logs.php';
    $validation_v = $fonctionUsers. ' ' . $nom_user. ' '.$prenom_user;
    $activite="Validation d'un CDC";
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql = "SELECT datacc.*, societe_imp.*, societe_exp.*
        FROM data_cc datacc
        LEFT JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
        LEFT JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
        WHERE id_data_cc = $id_data_cc";
        $sql1 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Chef de Division Exportation Minière' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql2 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Responsable qualité Laboratoire des Mines' AND assiste_agent.id_data_cc=$id_data_cc";
        
        $sql4 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Douanier' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql5 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Officier de Police' AND assiste_agent.id_data_cc=$id_data_cc";

        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        //
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $resu1 = $stmt1->get_result();
        $row1 = $resu1->fetch_assoc();
        //
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $resu2 = $stmt2->get_result();
        $row2 = $resu2->fetch_assoc();
        //
        $sql3 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de Scellage' AND assiste_agent.id_data_cc=$id_data_cc";
        $result3= mysqli_query($conn, $sql3);
        //
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute();
        $resu4 = $stmt4->get_result();
        $row4 = $resu4->fetch_assoc();
        //
        $stmt5 = $conn->prepare($sql5);
        $stmt5->execute();
        $resu5 = $stmt5->get_result();
        $row5 = $resu5->fetch_assoc();
        
        $num_domiciliation = $row["num_domiciliation"] ?? "";
        $pj_domiciliation = $row["pj_domiciliation"] ?? "";
        $nombre_colis = $row["nombre_colis"] ?? "";   
        $lieu_scellage = $row["lieu_scellage_pv"] ?? "";   
        $lieu_embarquement = $row["lieu_embarquement_pv"] ?? "";   
        $type_colis = $row["type_colis"] ?? "";   
        $num_fiche_declaration = $row["num_fiche_declaration_pv"] ?? "";   
        $date_fiche_declaration = $row["date_fiche_declaration_pv"] ?? "";   
        $num_lp3e = $row["num_lp3e_pv"] ?? "";  
        $date_lp3e = $row["date_lp3e"] ?? "";   
        $pj_lp3e = $row["pj_lp3e_pv"] ?? ""; 
        $num_cc = $row['num_cc'] ?? "";
        $num_facture = $row['num_facture'] ?? "";
        $date_cc = $row['date_cc'] ?? "";
        $lien_cc = $row['lien_cc'] ?? "";
        $pj_cc = $row['pj_cc'] ?? "";
        $num_pv_scellage =$row['num_pv_scellage'] ?? "";
        $pj_fiche_declaration = $row["pj_fiche_declaration_pv"] ?? "";
        $id_societe_expediteur = $row["id_societe_expediteur"] ?? "";
        $id_societe_importateur = $row["id_societe_importateur"] ?? "";
        $validation_chef_services =$row['validation_chef'] ?? "En attente";
        $validation_directeur =$row['validation_directeur'] ?? "En attente";
        $user_directeur = $row['user_validation_directeur'];
        $user_chef_services = $row['user_validation_chef'];
        $scan = $row['scan_controle'];
        //echo "Num:".$row['num_quittance'];
        //
        $id_agent_chef = $row1["id_agent"] ?? "";
        $id_agent_qualite = $row2["id_agent"] ?? "";
        $id_agent_scellage = $row3["id_agent"] ?? "";
        $id_agent_douane = $row4["id_agent"] ?? "";
        $id_agent_police = $row5["id_agent"] ?? "";

        // $sql6 = "SELECT unite_poids_facture, sum(poids_facture) AS somme FROM contenu_facture WHERE id_data_cc= $id_data_cc";
        // $stmt6 = $conn->prepare($sql6);
        // $stmt6->execute();
        // $resu6 = $stmt6->get_result();
        // $row6 = $resu6->fetch_assoc();
        // $somme= $row6['somme'];
        // $unite_poids_facture = $row6['unite_poids_facture'];
        // $redevance = $assiette * $somme * 0.006; // 0.6%
        // $ristourne = $assiette * $somme * 0.014; // 1.4%
        
        $id_agent_scellage = array();

        if($result3){
            while($row3 = mysqli_fetch_assoc($result3)){
                $id_agent_scellage[] = $row3['id_agent'];
            }
        }

       $query = "SELECT * FROM data_cc WHERE  num_facture  IS NOT NULL AND id_data_cc=$id_data_cc";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $facturation = 'avec';
        }
        $query2 = "SELECT * FROM data_cc WHERE  num_facture  IS NULL AND id_data_cc=$id_data_cc";
        $result = $conn->query($query2);
        if ($result->num_rows > 0) {
            $attestation = 'avec';
        }
        $query3 = "SELECT * FROM revenu WHERE  id_data_cc=$id_data_cc";
        $result = $conn->query($query3);
        if ($result->num_rows > 0) {
            $revenu = 'avec';
        }
        
        $texte ="";
        if(!empty($attestation)){
            $query = "SELECT sub.nom_substance FROM contenu_attestation AS catt 
          LEFT JOIN substance2 AS sub ON sub.id_substance = catt.id_substance 
          WHERE  catt.id_data_cc=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_data_cc);
            $stmt->execute();
            $result = $stmt->get_result();

            $substances = [];
            while ($row_2 = $result->fetch_assoc()) {
                $nom_substance = $row_2['nom_substance'];
                
                // Vérifie si le premier caractère est une voyelle (en minuscule ou majuscule)
                if (preg_match('/^[aeiouAEIOU]/', $nom_substance)) {
                    $substances[] = "d'" . $nom_substance;
                } else {
                    $substances[] = "de " . $nom_substance;
                }
            }

            // Join the substance names with commas, and replace the last comma with ' et '
            $substances_sentence = implode(', ', $substances);
            $substances_sentence = preg_replace('/, ([^,]+)$/', ' et $1', $substances_sentence);

            $query= "SELECT SUM(poids_attestation) AS somme, unite FROM contenu_attestation WHERE id_data_cc='$id_data_cc'";
            $result= mysqli_query($conn, $query);
            $row_2= mysqli_fetch_assoc($result);
            $somme = floatval($row_2['somme']);
            $unite = $row_2['unite'];
            $nombre = nombreEnLettres($somme);
            $unite_affiche="";
            if(($unite=='kg')&&($somme < 2)){
                $unite_affiche="kilogramme";
            }else if(($unite=='kg')&&($somme >= 2)){
                $unite_affiche="kilogrammes";
            }else if(($unite=='g')&&($somme < 2)){
                $unite_affiche="gramme";
            }else if(($unite=='g')&&($somme >= 2)){
                $unite_affiche="grammes";
            }
            $texte = $nombre .' '.$unite_affiche.' '.$substances_sentence.'.';

            $query= "SELECT an.* FROM data_cc AS dcc LEFT JOIN ancien_lp AS an ON an.id_ancien_lp = dcc.id_ancien_lp  WHERE dcc.id_data_cc=$id_data_cc";
            $result= mysqli_query($conn, $query);
            $row_4= mysqli_fetch_assoc($result);
        }
        
        $stmt->close();
        $stmt1->close();
        $stmt2->close();
        $stmt4->close();
        $stmt5->close();
   } else {
        echo "<p>Aucune information trouvée pour cet ID LP.</p>";
    }
if (isset($_POST['submit'])) {
    $id = $_POST['id_data'];
    $action = $_POST['action'];
    $sql = "UPDATE `data_cc` SET `validation_chef`='$action', `user_validation_chef`='$validation_v' WHERE id_data_cc=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        insertLogs($conn, $userID, $activite);
        $_SESSION['toast_message'] = "Modification réussie.";
        header("Location: https://cdc.minesmada.org/view_user/pv_controle/detail.php?id=" . $id);
        exit();
    } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
    }
}  

if (isset($_POST['submit2'])) {
    $id = $_POST['id_data_v2'];
    $action = $_POST['action_v2'];
    $sql = "UPDATE `data_cc` SET `validation_directeur`='$action', `user_validation_directeur`='$validation_v' WHERE id_data_cc=$id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        insertLogs($conn, $userID, $activite);
        $_SESSION['toast_message'] = "Modification réussie.";
        header("Location: https://cdc.minesmada.org/view_user/pv_controle/detail.php?id=" . $id);
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <style>
    body {
        margin: 0;
    }
    </style>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
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

    .info {
        padding-left: 8.5%;
        padding-right: 8.5%;
        font-size: small;
    }

    #infon1 #info2 {
        display: inline-block;
    }

    .info1 {
        width: 40%;
        float: left;

    }

    .info2 {
        width: 57%;
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
    </style>
    <title>Information sur un PV</title>
    <?php include_once('../../view/shared/navBar.php'); ?>
    <?php 
    function ecrire($totalEnGramme, $type_substance, $unite1, $unite){
        $totalePoidsFormate = number_format($totalEnGramme, 2, '.', '');

        $nombreExplode = explode(".", $totalePoidsFormate);
        $nombreAvant = $nombreExplode[0];
        $nombreApres = $nombreExplode[1];
        $nombreCompare='';
        $nombreCompareLettre='';
        if($nombreApres > 0) {
            $nombreCompare = comparer($nombreApres);
            $nombreCompareLettre=nombreEnLettres($nombreCompare);
        }
                        
        $totalePoidsEnLettres = nombreEnLettres($nombreAvant);
            if($nombreApres=='00'){
                $poidsEnLettre = $totalePoidsFormate.$unite1.'('. $totalePoidsEnLettres.''.$nombreCompareLettre.' '.$unite.' de '.$type_substance.')';
            }else{
                $poidsEnLettre = $totalePoidsFormate.$unite1.'('. $totalePoidsEnLettres.' virgule '.$nombreCompareLettre.' '.$unite.' de '.$type_substance.')';
            }

            return $poidsEnLettre;
    }
    ?>
</head>


<body>
    <div class="info  container">
        <p class="text-center mb-0">Détails du certificat de conformité</p>
        <hr>
        <div class="partie1">
            <?php 
                        if($groupeID === 2){
                            // echo '<a class="btn btn-dark rounded-pill px-3  btn-modifier-scan"
                            //                                             data-id="' . $id_data_cc . '">Modifier scan</a>';
                             echo '<a class="btn btn-dark rounded-pill px-3  btn-nouveau-scan"
                                                                        data-id="' . $id_data_cc . '">Insérer scan</a>';
                                    echo '
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">';
                                            if(!empty($num_pv_scellage)){
                                                echo '<li><a class="dropdown-item" href="../pv_scellage/detail.php?id=' . $id_data_cc.'">Voir PV de scellage</a></li>
                                                <li><hr class="dropdown-divider"></li>';
                                            }
                                                echo '<li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de contrôle</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>';
                                                if(!empty($facturation)){
                                                    echo'<a class="dropdown-item" href="../gerer_contenu_facture/liste_contenu_facture.php?id=' . $id_data_cc.'">Voir contenus de facture</a>';
                                                }else{
                                                    echo'<a class="dropdown-item" href="../attestation_valeur/liste_contenu_attestation.php?id=' . $id_attestation.'">Contenu de l\'Attestation</a>';
                                                }
                                                echo '</li>
                                            </ul>
                                        </div>
                                    ';
                            } else if($groupeID===1) {
                                    echo '<div class="row">
                                    <div class="dropdown col">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">
                                                 <li>';
                                                if(!empty($facturation)){
                                                    echo'<a class="dropdown-item" href="../gerer_contenu_facture/liste_contenu_facture.php?id=' . $id_data_cc.'">Voir contenus de facture</a>';
                                                }else{
                                                    echo'<a class="dropdown-item" href="../attestation_valeur/liste_contenu_attestation.php?id=' . $id_attestation.'">Contenu de l\'Attestation</a>';
                                                }
                                                echo '</li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de contrôle</a></li>
                                            </ul>
                                        </div>
                                    <div class="col text-end">';
                                    if(empty($num_facture)){
                                        if($userID===7){
                                            if(($validation_chef_services!='Validé')||($validation_directeur!='Validé')){
                                                if(!empty($revenu)){
                                                    echo '<a class="btn btn-dark rounded-pill px-3  btn-nouveau-ov"
                                                                                            data-id="' . $id_data_cc . '">Générer O.V</a>';
                                                }else{
                                                    echo '<a class="btn btn-dark rounded-pill px-3  btn-modifier-ov"
                                                                                            data-id="' . $id_data_cc . '">Modifier O.V</a>';
                                                }
                                            }
                                        }
                                         if(empty($scan)){
                                            echo '<a class="btn btn-dark rounded-pill px-3  btn-nouveau-scan_nc"
                                                                                data-id="' . $id_data_cc . '">Insérer scan</a>';
                                        }else if(($validation_chef_services=="À Refaire")||($validation_directeur=="À Refaire")){
                                                echo '<a class="btn btn-dark rounded-pill px-3  btn-modifier-scan_nc"
                                                                                data-id="' . $id_data_cc . '">Modifier scan</a>';
                                        }
                                    }else{
                                        if($userID===7){
                                            //if(($validation_chef_services!='Validé')||($validation_directeur!='Validé')){
                                                if(empty($revenu)){
                                                    echo '<a class="btn btn-dark rounded-pill px-3  btn-nouveau-ov"
                                                                                            data-id="' . $id_data_cc . '">Générer O.V</a>';
                                                }else{
                                                    echo '<a class="btn btn-dark rounded-pill px-3  btn-modifier-ov"
                                                                                            data-id="' . $id_data_cc . '">Modifier O.V</a>';
                                                }
                                            //}
                                        }else{
                                        }
                                        if(empty($scan)){
                                            echo '<a class="btn btn-dark rounded-pill px-3  btn-nouveau-scan"
                                                                                data-id="' . $id_data_cc . '">Insérer scan</a>';
                                            }else if(($validation_chef_services=="À Refaire")||($validation_directeur=="À Refaire")){
                                                echo '<a class="btn btn-dark rounded-pill px-3  btn-modifier-scan"
                                                                                data-id="' . $id_data_cc . '">Modifier scan</a>';
                                            } 
                                    }
                                    echo '</div>
                                    </div>';
                            }else{
                                    echo '<div class="row">
                                    <div class="dropdown col">
                                            <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Voir les détails associer
                                            </button>
                                            <ul class="dropdown-menu">
                                                 <li>';
                                                if(!empty($facturation)){
                                                    echo'<a class="dropdown-item" href="../gerer_contenu_facture/liste_contenu_facture.php?id=' . $id_data_cc.'">Voir contenus de facture</a>';
                                                }else{
                                                    echo'<a class="dropdown-item" href="../attestation_valeur/liste_contenu_attestation.php?id=' . $id_attestation.'">Contenu de l\'Attestation</a>';
                                                }
                                                echo '</li>
                                                <li><hr class="dropdown-divider"></li>';
                                                if(!empty($num_pv_scellage)){
                                                    echo'<li><a class="dropdown-item" href="../pv_scellage/detail.php?id=' . $id_data_cc.'">Voir PV de scellage</a></li>';
                                                }
                                                
                                                echo'<li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de contrôle</a></li>
                                            </ul>
                                            </div>
                                    <div class="col text-end">';
                                              if(empty($num_facture)){
                                                    if(empty($scan)){
                                                        echo '<a class="btn btn-dark rounded-pill px-3  btn-nouveau-scan_nc"
                                                                                            data-id="' . $id_data_cc . '">Insérer scan</a>';
                                                    }else if(($validation_chef_services=="À Refaire")||($validation_directeur=="À Refaire")){
                                                            echo '<a class="btn btn-dark rounded-pill px-3  btn-modifier-scan_nc"
                                                                                            data-id="' . $id_data_cc . '">Modifier scan</a>';
                                                    }
                                                }else{
                                                        if(empty($scan)){
                                                        echo '<a class="btn btn-dark rounded-pill px-3  btn-nouveau-scan"
                                                                                            data-id="' . $id_data_cc . '">Insérer scan</a>';
                                                        }else if(($validation_chef_services=="À Refaire")||($validation_directeur=="À Refaire")){
                                                            echo '<a class="btn btn-dark rounded-pill px-3  btn-modifier-scan"
                                                                                            data-id="' . $id_data_cc . '">Modifier scan</a>';
                                                        } 
                                                }
                                        echo '</div>
                                        </div>';
                            }
                    
                    ?>
        </div>
        <hr>
        <?php 
        if($groupeID !==2){
            if(!empty($scan)){
                    if ($code_fonction == 'B') {?>
        <form action="" method="post">
            <?php
                    // Supposons que $selectedValue contient la valeur récupérée de la base de données.
                    $selectedValueD = $validation_chef_services; // Exemple de valeur
                    function isSelected($value, $selectedValueD) {
                        return $value === $selectedValueD ? 'selected' : '';
                    }
                    ?>
            <div class="row">
                <div class="col">
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data" id="id_data">
                    <select class="form-select" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValueD) ?>>À Refaire</option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValueD) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValueD) ?>>En attente
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
            }else if ($code_fonction == 'A'){
                ?>
        <form action="" method="post">
            <?php
                    // Supposons que $selectedValue contient la valeur récupérée de la base de données.
                    $selectedValue = $validation_directeur; // Exemple de valeur
                    function isSelected($value, $selectedValue) {
                        return $value === $selectedValue ? 'selected' : '';
                    }
                    ?>
            <div class="row">
                <div class="col">
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data_v2" id="id_data_v2">
                    <select class="form-select" name="action_v2" id="action_v2" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire</option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit2"
                        name="submit2">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php
            }else if(($code_fonction == 'B')&&($validation_chef_services=="Validé")&&($validation_directeur=="Validé")){
                echo '<p class="alert alert-info">Validation du Directeur:'.$validation_directeur.',
                 Validateur: '.$user_directeur.'<br>Validation du Chef de service:'.$validation_chef_services.', Validateur:'.$user_chef_services.'</p>';
            }else if(($code_fonction == 'A')&&($validation_directeur=="Validé")&&($validation_chef_services=="Validé")){
                 echo '<p class="alert alert-info">Validation du Directeur:'.$validation_directeur.',
                 Validateur: '.$user_directeur.'<br>Validation du Chef de service:'.$validation_chef_services.', Validateur:'.$user_chef_services.'</p>';
            }else if(($code_fonction == 'C')&&($validation_directeur=="Validé")&&($validation_chef_services=="Validé")){
                echo '<p class="alert alert-info">Validation du Directeur:'.$validation_directeur.',
                 Validateur: '.$user_directeur.'<br>Validation du Chef de service:'.$validation_chef_services.', Validateur:'.$user_chef_services.'</p>';
            }else if((($code_fonction == 'A')||($code_fonction == 'B')||($code_fonction == 'C'))&&(($validation_directeur!="Validé")||($validation_chef_services!="Validé"))){
                 echo '<p class="alert alert-info">Validation du Directeur:'.$validation_directeur.', Validation du Chef de service:'.$validation_chef_services.'</p>';
            }
        }else{
                 echo '<p class="alert alert-info">Aucun scan correspondant!.</p>';
        }
            
    }else if($groupeID ===2){ ?>
        <div class="row">
            <div class="col">
                <form action="" method="post">
                    <?php
            // Supposons que $selectedValue contient la valeur récupérée de la base de données.
            $selectedValue2 = $validation_chef_services; // Exemple de valeur
            function isSelected($value, $selectedValue2) {
                return $value === $selectedValue2 ? 'selected' : '';
            }
            ?>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating">
                                <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data" id="id_data">
                                <select class="form-select" name="action" id="action" required>
                                    <option value="">Séléctionner</option>
                                    <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue2) ?>>À Refaire
                                    </option>
                                    <option value="Validé" <?= isSelected('Validé', $selectedValue2) ?>>Validé</option>
                                    <option value="En attente" <?= isSelected('En attente', $selectedValue2) ?>>En
                                        attente</option>
                                </select>
                                <label for="floatingSelectGrid">Chef de services</label>
                            </div>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                                name="submit">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col">
                <form action="" method="post">
                    <?php
            // Supposons que $selectedValue contient la valeur récupérée de la base de données.
            $selectedValue = $validation_directeur; // Exemple de valeur
            ?>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating">
                                <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data_v2"
                                    id="id_data_v2">
                                <select class="form-select" name="action_v2" id="action_v2" required>
                                    <option value="">Séléctionner</option>
                                    <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire
                                    </option>
                                    <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                                    <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En
                                        attente</option>
                                </select>
                                <label for="floatingSelectGrid">Directeur</label>
                            </div>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                                name="submit2">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
    }
    $sumAssiette = 0;
    $sumRedevance = 0;
    $sumRistourne = 0;

    // $sql = "SELECT DISTINCT id_lp1_info FROM contenu_facture WHERE id_data_cc=$id_data_cc AND id_lp1_info IS NOT NULL";
    // $result = $conn->query($sql);
    // if ($result->num_rows > 0) {
    //     // Boucler à travers les colonnes et afficher les noms
    //     while ($row_lp = $result->fetch_assoc()) {
    //         $id_lp1_info = $row_lp['id_lp1_info'];
    //         $sql = "SELECT lp.*, rv.*, tr.* FROM lp_info AS lp 
    //                 LEFT JOIN revenu AS rv ON lp.id_revenu = rv.id_revenu 
    //                 LEFT JOIN tresor AS tr ON lp.id_tresor = tr.id_tresor 
    //                 WHERE id_lp=$id_lp1_info";
    //         $result_lp = $conn_lp1->query($sql);

    //         // Vérifier si des colonnes existent
    //         if ($result_lp->num_rows > 0) {
    //             // Boucler à travers les colonnes et récupérer les sommes
    //             while ($row_lp = $result_lp->fetch_assoc()) {
    //                 $sumAssiette += $row_lp['assiette_rrm'];
    //                 $sumRedevance += $row_lp['redevance'];
    //                 $sumRistourne += $row_lp['ristourne'];

    //             }
    //         } else {
    //             echo "Aucun résultat pour id_lp=$id_lp1_info.";
    //         }
    //     }
    // }
    if((!empty($row['redevance']))&&(!empty($ristourne))){
        $sumRedevance = $sumRedevance + intval($row['redevance']);
        $sumRistourne = $sumRistourne + intval($row['ristourne']);
    }
    $sql_revenu = "SELECT * FROM revenu WHERE id_data_cc=$id_data_cc";
    $result_revenu = $conn->query($sql_revenu);
    if ($result_revenu->num_rows > 0) {
        while ($row_revenu = $result_revenu->fetch_assoc()) {
            $sumAssiette += $row_revenu['assiette'];
            $sumRedevance +=$row_revenu['redevance'];
            $sumRistourne +=$row_revenu['ristourne'];
        }
    }
    ?>
        <hr>
        <div class="info1">
            <?php 
                if (!empty($num_cc)) {
                    echo '<div class="alert alert-light" role="alert">
                            <h5 id="list-item-1">Information sur le certificat de conformité</h5>
                            <hr>
                            <p><strong>Numéro de certificat de conformité:</strong> ' . $row["num_cc"] . '</p>
                            <p><strong>Date de création:</strong> ' . date("d/m/Y", strtotime($row["date_cc"])) . '</p>';
                            if($sumAssiette > 0){
                                echo '<p><strong>Assiette:</strong> ' . $sumAssiette. ' Ariary</p>
                                <p><strong>Redevance:</strong> ' . $sumRedevance. ' Ariary</p>
                                <p><strong>Ristourne:</strong> ' . $sumRistourne. ' Ariary</p>';
                            }else if($sumRedevance > 0){
                                echo '<p><strong>Redevance:</strong> ' . $sumRedevance. ' Ariary</p>
                                <p><strong>Ristourne:</strong> ' . $sumRistourne. ' Ariary</p>';
                            }
                            if(!empty($row["droit_conformite"])){
                                echo '<p><strong>Droit de conformité:</strong> ' . $row["droit_conformite"] . ' Ariary</p>';
                            if(!empty($row['description'])){
                                echo '<p><strong>Description:</strong> ' . $row["description"] . '</p>';
                            }
                            ?>

            <p><strong>Scan de l'OV:</strong> <a
                    href="../view_user/<?php echo $row['scan_ov']; ?>"><?php echo $row['num_ov']; ?>.pdf</a>
                du
                <?php echo date('d/m/Y', strtotime($row['date_ov'])); ?></p>
            <p><strong>Scan de la quittance:</strong> <a
                    href="../view_user/<?php echo $row['scan_quittance']; ?>"><?php echo $row['num_quittance']; ?>.pdf</a>
                du
                <?php echo date('d/m/Y', strtotime($row['date_quittance'])); ?></p>
            <?php 
                            }
                            if(($validation_chef_services=='Validé')&&($validation_directeur=='Validé')){
                             echo '<p><strong>Télécharger:</strong> <a href="../view_user/' . htmlspecialchars($row["pj_cc"], ENT_QUOTES, "UTF-8") . '">' . htmlspecialchars($row["num_cc"], ENT_QUOTES, "UTF-8") . '.pdf</a></p>';
                            }
                        echo '</div>';
                }
            ?>

            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur le PV de contrôle</h5>
                <hr>
                <p><strong>Numéro de PV de controle:</strong> <?php echo $row['num_pv_controle']; ?></p>
                <p><strong>Date de création:</strong>
                    <?php echo date('d/m/Y', strtotime($row['date_creation_pv_controle'])); ?>
                </p>
                <p><strong>Date de modification:</strong>
                    <?php echo date('d/m/Y', strtotime($row['date_modification_pv_controle'])); ?>
                </p>
                <p><strong>Lieu de contrôle:</strong><?php echo $row['lieu_controle_pv']; ?></p>
                <p><strong>Nombre et d'emballage:</strong><?php echo $row['mode_emballage']; ?></p>
                <p><strong>Lieu d'embarquement:</strong><?php echo $row['lieu_embarquement_pv']; ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../fichier/<?php echo htmlspecialchars(basename($row['pj_pv_controle']), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['num_pv_controle'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
            </div>
            <?php 
                if (!empty($num_pv_scellage)) {
                    echo '<div class="alert alert-light" role="alert">
                            <h5 id="list-item-1">Information sur le PV de scellage</h5>
                            <hr>
                            <p><strong>Numéro de PV de scellage:</strong> ' . $row["num_pv_scellage"] . '</p>
                            <p><strong>Lieu de scellage:</strong>'.$row["lieu_scellage_pv"]. '</p>
                            <p><strong>Nombre de colis:</strong>'.$row["nombre_colis"]. '</p>
                            <p><strong>Type de colis:</strong>'.$row["type_colis"]. '</p>
                            <p><strong>Date de création:</strong> ' . date("d/m/Y", strtotime($row["date_creation_pv_scellage"])) . '</p>
                            <p><strong>Date de modification:</strong> ' . date("d/m/Y", strtotime($row["date_modification_pv_scellage"])) . '</p>
                            <p><strong>Télécharger:</strong> <a href="../view_user/' . htmlspecialchars($row["pj_pv_scellage"], ENT_QUOTES, "UTF-8") . '">' . htmlspecialchars($row["num_pv_scellage"], ENT_QUOTES, "UTF-8") . '.pdf</a></p>
                        </div>';
                }
            ?>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur les sociétés</h5>
                <hr>
                <p><strong>Société:</strong>Expéditeur</p>
                <p><strong>Nom de la société:</strong><?php echo $row['nom_societe_expediteur']; ?></p>
                <p><strong>Adresse de la société:</strong><?php echo $row['adresse_societe_expediteur']; ?></p>
                <p><strong>Nif de la société:</strong><?php echo $row['nif_societe_expediteur']; ?></p>
                <p><strong>Contact de la société:</strong><?php echo $row['contact_societe_expediteur']; ?></p>
                <p><strong>Email de la société:</strong> <?php echo $row['email_societe_expediteur']; ?></p>
                <hr>
                <p><strong>Société:</strong> Importateur</p>
                <p><strong>Nom de la société:</strong><?php echo $row['nom_societe_importateur']; ?></p>
                <p><strong>Adresse de la société:</strong><?php echo $row['adresse_societe_importateur']; ?></p>
                <p><strong>Contact de la société:</strong><?php echo $row['contact_societe_importateur']; ?></p>
                <p><strong>Email de la société:</strong> <?php echo $row['email_societe_importateur']; ?></p>
                <p><strong>Pays de destination:</strong> <?php echo $row['pays_destination']; ?></p>

            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-3">CONTENUS</h5>
                <hr>
                <div class="contenu">
                    <?php 
                    
                    $nom_substance = array();
                    $couleur_substance = array();
                    $afficheWord= array();
                    $substances_couleurs = array();
                    $queryR = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $id_data_cc";
                    $resultR = mysqli_query($conn, $queryR);
                    $id_detaille_substance = array();

                    $index1 = 0;
                    while($rowR = mysqli_fetch_assoc($resultR)){
                        $id_detaille_substance[$index1] = $rowR['id_detaille_substance'];
                        $index1++;
                    }
                    if (count($id_detaille_substance) > 0) {
                        $tableau_resultats = array();
                        for ($i = 0; $i < count($id_detaille_substance); $i++) {
                            $queryD = "SELECT sub.*, couleur.*, cate.*
                            FROM substance_detaille_substance AS detail
                            LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                            LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                            LEFT JOIN categorie AS cate ON cate.id_categorie = detail.id_categorie WHERE cate.nom_categorie='Taillée' AND detail.id_detaille_substance = " . $id_detaille_substance[$i] . " GROUP BY couleur.nom_couleur_substance";
                            $resultD = mysqli_query($conn, $queryD);
                            if ($rowD = mysqli_fetch_assoc($resultD)) {
                                $nom_substance[] = $rowD['nom_substance'];
                                if (!empty($rowD['nom_couleur_substance'])) {
                                    $couleur_substance[] = $rowD['nom_couleur_substance'];
                                } else {
                                    $couleur_substance[] = "vide";
                                }
                            }
                        
                        }
                        }
                    if (count($nom_substance) > 0) {
                        for ($i = 0; $i < count($nom_substance); $i++) {
                            $substance = $nom_substance[$i];
                            $couleur = $couleur_substance[$i];
                            // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
                            if (array_key_exists($substance, $substances_couleurs)) {
                                $substances_couleurs[$substance][] = $couleur;
                            } else {
                                $substances_couleurs[$substance] = array($couleur);
                            }
                        }

                        // Affichage des résultats
                        foreach ($substances_couleurs as $substance => $couleurs) {
                            $couleurs_uniques = array_unique($couleurs);
                            if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
                                $afficheWord[] = $substance;
                            } else {
                                $afficheWord[] = $substance . '(' . implode(', ', $couleurs_uniques) . ')';
                            }
                        }
                    }
                    
                    ?>
                    <?php
                    $nom_substance_brute = array();
                    $couleur_substance_brute = array();
                    $afficheWord_brute= array();
                    $substances_couleurs_brute = array();
                    $queryR = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $id_data_cc";
                    $resultR = mysqli_query($conn, $queryR);
                    $id_detaille_substance = array();

                    $index1 = 0;
                    while($rowR = mysqli_fetch_assoc($resultR)){
                        $id_detaille_substance[$index1] = $rowR['id_detaille_substance'];
                        $index1++;
                    }
                    if (count($id_detaille_substance) > 0) {
                        $tableau_resultats = array();
                        for ($i = 0; $i < count($id_detaille_substance); $i++) {
                            $queryD = "SELECT sub.*, couleur.*, cate.*
                            FROM substance_detaille_substance AS detail
                            LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                            LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                            LEFT JOIN categorie AS cate ON cate.id_categorie = detail.id_categorie WHERE cate.nom_categorie='Brute' AND detail.id_detaille_substance = " . $id_detaille_substance[$i] . " GROUP BY couleur.nom_couleur_substance";
                            $resultD = mysqli_query($conn, $queryD);
                            if ($rowD = mysqli_fetch_assoc($resultD)) {
                                $nom_substance_brute[] = $rowD['nom_substance'];
                                if (!empty($rowD['nom_couleur_substance'])) {
                                    $couleur_substance_brute[] = $rowD['nom_couleur_substance'];
                                } else {
                                    $couleur_substance_brute[] = "vide";
                                }
                            }
                        
                        }
                        }
                        if (count($nom_substance_brute) > 0) {
                        for ($i = 0; $i < count($nom_substance_brute); $i++) {
                            $substance_brute = $nom_substance_brute[$i];
                            $couleur_brute = $couleur_substance_brute[$i];
                            // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
                            if (array_key_exists($substance_brute, $substances_couleurs_brute)) {
                                $substances_couleurs_brute[$substance_brute][] = $couleur_brute;
                            } else {
                                $substances_couleurs_brute[$substance_brute] = array($couleur_brute);
                            }
                        }

                        // Affichage des résultats
                        foreach ($substances_couleurs_brute as $substance_brute => $couleurs_brute) {
                            $couleurs_uniques_brute = array_unique($couleurs_brute);
                            if (empty($couleurs_uniques_brute) || in_array('vide', $couleurs_uniques_brute, true)) {
                                $afficheWord_brute[] = $substance_brute;
                            } else {
                                $afficheWord_brute[] = $substance_brute . '(' . implode(', ', $couleurs_uniques_brute) . ')';
                            }
                        }
                    }
                    
                    
                    ?>
                </div>
                <?php
                if(empty($facturation)){
                    echo $texte;
                }else{
                    include '../pv_scellage/recherche.php';
                    if(count($afficheWord_brute) > 0) {
                        echo "Catégorie Brute : ";
                        for ($i = 0; $i < count($afficheWord_brute); $i++) {
                            echo $afficheWord_brute[$i] .', ';
                        }
                        echo '</br> POIDS: '.$ecrit_b;
                    }
                    if(count($afficheWord) > 0) {
                        echo "Catégorie Taillée ou Travaillée : ";
                        for ($i = 0; $i < count($afficheWord); $i++) {
                            if($i == count($afficheWord) - 1){
                                echo $afficheWord[$i];
                            } else {
                                echo $afficheWord[$i] . ', ';
                            }
                        }
                        echo '<br> POIDS: '.$ecrit_t;
                    }
                }
                ?>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-4">Informations sur le fichier de déclaration</h5>
                <hr>
                <p><strong>Numéro de fiche de déclaration :</strong> <?php echo $row['num_fiche_declaration_pv']; ?></p>
                <p><strong>Scan de fiche de déclaration :</strong> <a
                        href="../view_user/<?php echo $row['pj_fiche_declaration_pv']; ?>"><?php echo $row['num_fiche_declaration_pv']; ?>.pdf</a>
                    du <?php echo date('d/m/Y', strtotime($row['date_fiche_declaration_pv'])); ?></p>

            </div>
            <?php if(!empty($facturation)){ ?>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-4">Informations sur DOM</h5>
                <hr>
                <p><strong>Numéro de domiciliation:</strong> <?php echo $row['num_domiciliation']; ?></p>
                <p><strong>Scan du fichier DOM:</strong> <a
                        href="../view_user/<?php echo htmlspecialchars($row['pj_domiciliation_pv'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['num_domiciliation'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-5">Information sur LP3 E</h5>
                <hr>
                <p><strong>Numéro LP3 E:</strong> <?php echo $row['num_lp3e_pv']; ?></p>
                <p><strong>Scan de LP III E:</strong> <a
                        href="../view_user/<?php echo $row['pj_lp3e_pv']; ?>"><?php echo $row['num_lp3e_pv']; ?>.pdf</a>
                    du
                    <?php echo date('d/m/Y', strtotime($row['date_lp3e'])); ?></p>

            </div>
            <?php }else{ ?>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-5">Information sur l'attestation de valeur</h5>
                <hr>
                <p><strong>Numéro de l'attestation de valeur:</strong> <?php echo $row['num_attestation']; ?></p>
                <p><strong>Scan de l'attestation de valeur:</strong> <a
                        href="../view_user/<?php echo $row['pj_attestation']; ?>"><?php echo $row['num_attestation']; ?>.pdf</a>
                    du
                    <?php echo date('d/m/Y', strtotime($row['date_attestation'])); ?></p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-5">Information sur la fiche de contrôle</h5>
                <hr>
                <p><strong>Numéro de fiche de contrôle:</strong> <?php echo $row_4['numero_lp']; ?></p>
                <p><strong>Scan de la fiche de contrôle:</strong> <a
                        href="../view_user/<?php echo $row_4['scan_lp']; ?>"><?php echo $row_4['numero_lp']; ?>.pdf</a>
                    du
                    <?php echo date('d/m/Y', strtotime($row_4['date_creation'])); ?></p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-5">Information sur demande d'autorisation</h5>
                <hr>
                <p><strong>Scan de demande d'autorisation:</strong> <a
                        href="../view_user/<?php echo $row['scan_demande_autorisation']; ?>">Scan.pdf</a>
                    du
                    <?php echo date('d/m/Y', strtotime($row['date_demande_autorisation'])); ?></p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-5">Information sur l'engagement de responsabilité</h5>
                <hr>
                <p><strong>Scan de l'engagement:</strong> <a
                        href="../view_user/<?php echo $row['scan_engagement']; ?>">Scan.pdf</a>
                    du
                    <?php echo date('d/m/Y', strtotime($row['date_engagement'])); ?></p>
            </div>
            <?php } ?>
        </div>
        <div class="info2">
            <div class="alert alert-light" role="alert">
                <?php
                        // Emplacement du fichier PDF
                    if (($validation_chef_services != "Validé")|| ($validation_directeur != "Validé")) {
                        $pdfFilePath=$row['lien_cc'];
                        include "../cdc/convert2.php";
                    }else{
                        //$pj=$pj_cc;
                        $pdfFilePath=$row['pj_cc'];
                        include "../cdc/convert4.php";
                    }
                        
                    ?>
            </div>
        </div>
        <div id="modifier_scan_form"></div>
        <div id="nouveau_scan_form"></div>
        <div id="modifier_scan_form_nc"></div>
        <div id="nouveau_scan_form_nc"></div>
        <div id="nouveau_form_ov"></div>
        <div id="modifier_form_ov"></div>
    </div>
    <?php
    
    ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    function refreshIframe(pj) {
        var pj_pv_scellage = pj.replace('../', '');
        var pdfFilePathSc = 'cdc.minesmada.org/view_user/' + pj_pv_scellage;
        // Mettre à jour l'attribut src de l'iframe avec le nouveau lien PDF
        $('#pdfIframe').attr('src', 'https://docs.google.com/gview?url=' + encodeURIComponent(
                pdfFilePathSc) +
            '&embedded=true');
    }
    $(document).ready(function() {
        $('.toast').toast('show');
        $(".btn-nouveau-scan").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('nouveau_scan_form', '../pv_controle_gu/nouveau_scan.php?id=' + id_data_cc,
                'staticBackdrop3');

        });
        $(".btn-modifier-scan").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('modifier_scan_form', '../pv_controle_gu/edit_scan.php?id=' + id_data_cc,
                'staticBackdrop3');

        }); //btn-nouveau-ov
        $(".btn-nouveau-ov").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('nouveau_form_ov', './generate_ov.php?id=' +
                id_data_cc,
                'staticBackdrop_ov');

        });
        $(".btn-modifier-ov").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('modifier_form_ov', './modifier_ov.php?id=' +
                id_data_cc,
                'staticBackdrop_ov2');

        });
        $(".btn-nouveau-scan_nc").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('nouveau_scan_form_nc', '../pv_controle_gu/nouveau_scan_nc.php?id=' +
                id_data_cc,
                'staticBackdrop3');

        });
        $(".btn-modifier-scan_nc").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('modifier_scan_form_nc', '../pv_controle_gu/edit_scan_nc.php?id=' + id_data_cc,
                'staticBackdrop3');

        });


        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });

    function closeModal() {
        console.log('consulter');
        var myModal = bootstrap.Modal.getInstance(document.getElementById('staticBackdrop_ov2'));
        if (myModal) {
            myModal.hide();
        } else {
            console.log('consulter2');
        }
    }
    </script>
</body>

</html>