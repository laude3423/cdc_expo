<?php 

date_default_timezone_set('Indian/Antananarivo');
$heure_actuelle = date('H:i:s');
//renommer l'heure
$heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
//pj_DOM
$numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $numDom);
$uploadDir = '../upload/';
$fileName_DOM = "SCAN_DOM_" .$numDom_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_dom']['name'],
PATHINFO_EXTENSION);
$uploadPath_DOM = $uploadDir . $fileName_DOM;
//pj declaration
$numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $declaration);
$fileName_DEC = "SCAN_DECLARATION_" .$numDec_cleaned.$heureExacte_cleaned.".".
pathinfo($_FILES['pj_declaration']['name'], PATHINFO_EXTENSION);
$uploadPath_DEC = $uploadDir . $fileName_DEC;
//pj lp3 e
$numLP3_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp3);
$fileName_LP3 = "SCAN_LPIIIE_" .$numLP3_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_lp3e']['name'],
PATHINFO_EXTENSION);
$uploadPath_LP3 = $uploadDir . $fileName_LP3;
    //deplacement des fichier
    if (move_uploaded_file($_FILES['pj_declaration']['tmp_name'], $uploadPath_DEC)) {

    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    if (move_uploaded_file($_FILES['pj_dom']['tmp_name'], $uploadPath_DOM)) {

    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    if (move_uploaded_file($_FILES['pj_lp3e']['tmp_name'], $uploadPath_LP3)) {

    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    //insertion sur l'agent
    if(!empty($chef)){
    $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$facture')";
    $result = mysqli_query($conn, $sql);
    }
    if(!empty($qualite)){
    $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$facture')";
    $result = mysqli_query($conn, $sql);
    }
    if(count($agent_scellage) > 0){
    for ($i = 0; $i < count($agent_scellage); $i++) {
        $query="INSERT INTO  `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES (?, ?)" ; $stmt=$conn->prepare($query);
        $stmt->bind_param("ii", $agent_scellage[$i], $facture);
        $stmt->execute();
        }

        }
    if(!empty($douane)){
        $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$douane','$facture')";
        $result = mysqli_query($conn, $sql);
    }
    if(!empty($police)){
        $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$police','$facture')";
        $result = mysqli_query($conn, $sql);
    }


        //modification pour l'insertion
        $sql = "UPDATE `data_cc` SET `id_societe_expediteur`='$expediteur', `id_societe_importateur`='$destination',
        `num_pv_scellage`='$num_pv', `nombre_colis`='$nombre',`type_colis`='$type_colis',
        `lieu_scellage_pv`='$lieu_sce',`lieu_embarquement_pv`='$lieu_emb', `num_domiciliation`='$numDom',
        `num_fiche_declaration_pv`='$declaration',`pj_domiciliation_pv`='$uploadPath_DOM',
        `date_fiche_declaration_pv`='$date_declaration',`pj_fiche_declaration_pv`='$uploadPath_DEC',`num_lp3e_pv`='$num_lp3',`date_lp3e`='$date_lp3',`pj_lp3e_pv`='$uploadPath_LP3',
        `date_creation_pv_scellage`='$date',`lien_pv_scellage`='$pathToSave',`pj_pv_scellage`='$pathToSavePDF',`date_modification_pv_scellage`='$date' WHERE id_data_cc='$facture'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
        $_SESSION['toast_message'] = "Insertion réussie.";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
        } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    
    ?>