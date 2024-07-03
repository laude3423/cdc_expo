<?php 

date_default_timezone_set('Indian/Antananarivo');
$heure_actuelle = date('H:i:s');
//renommer l'heure
$heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
//pj_DOM
$numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_domiciliation);
$uploadDir = '../upload/';
$fileName_DOM = "SCAN_DOM_" .$numDom_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_dom']['name'],
PATHINFO_EXTENSION);
$uploadPath_DOM = $uploadDir . $fileName_DOM;
//pj declaration
$numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_fiche_declaration);
$fileName_DEC = "SCAN_DECLARATION_" .$numDec_cleaned.$heureExacte_cleaned.".".
pathinfo($_FILES['pj_declaration']['name'], PATHINFO_EXTENSION);
$uploadPath_DEC = $uploadDir . $fileName_DEC;
//pj lp3 e
$numLP3_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp3e);
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
    $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$id_data')";
    $result = mysqli_query($conn, $sql);
    }
    if(!empty($qualite)){
    $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$id_data')";
    $result = mysqli_query($conn, $sql);
    }
        //modification pour l'insertion
        $sql = "UPDATE `data_cc` SET `id_societe_expediteur`='$expediteur', `id_societe_importateur`='$importateur',
        `num_pv_controle`='$num_pv', `mode_emballage`='$mode_emballage',
        `lieu_controle_pv`='$lieu_controle',`lieu_embarquement_pv`='$lieu_embarquement', `num_domiciliation`='$num_domiciliation',
        `num_fiche_declaration_pv`='$num_fiche_declaration',`pj_domiciliation_pv`='$uploadPath_DOM',
        `date_fiche_declaration_pv`='$date_declaration',`pj_fiche_declaration_pv`='$uploadPath_DEC',`num_lp3e_pv`='$num_lp3e',`date_lp3e`='$date_lp3e',`pj_lp3e_pv`='$uploadPath_LP3',
        `date_creation_pv_controle`='$dateInsert',`lien_pv_controle`='$pathToSave',`pj_pv_controle`='$pathToSavePDF',`date_modification_pv_controle`='$dateInsert',
        `premiere_validation_cdc`='En attente',`deuxieme_validation_cdc`='En attente',`validation_controle`='En attente',
        `num_cc`='$num_cc',`date_cc`='$dateInsert',`lien_cc`='$lien_cc',`pj_cc`='$pj_cc', `date_depart`='$date_depart' WHERE id_data_cc='$id_data'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
        $_SESSION['toast_message'] = "Insertion réussie.";
        header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
        exit();
        } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    
    ?>