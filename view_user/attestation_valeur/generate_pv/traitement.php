<?php 

date_default_timezone_set('Indian/Antananarivo');
$heure_actuelle = date('H:i:s');
//renommer l'heure
$heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
//pj_DOM
$uploadDir2 = '../../upload/';
$uploadDir = '../upload/';
$fileName_DOM = "SCAN_AUTO_" .$heureExacte_cleaned.".". pathinfo($_FILES['pj_demande_autorisation']['name'],
PATHINFO_EXTENSION);
$uploadPath_DA = $uploadDir . $fileName_DOM;
$uploadPath_DA2 = $uploadDir2 . $fileName_DOM;
//pj declaration
$numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_fiche_declaration);
$fileName_DEC = "SCAN_DECLARATION_" .$numDec_cleaned.$heureExacte_cleaned.".".
pathinfo($_FILES['pj_declaration']['name'], PATHINFO_EXTENSION);
$uploadPath_DEC = $uploadDir . $fileName_DEC;
$uploadPath_DEC2 = $uploadDir2 . $fileName_DEC;
//pj lp3 e
$fileName_LP3 = "SCAN_ENG_" .$heureExacte_cleaned.".". pathinfo($_FILES['pj_engagement']['name'],
PATHINFO_EXTENSION);
$uploadPath_EN = $uploadDir . $fileName_LP3;
$uploadPath_EN2 = $uploadDir2 . $fileName_LP3;
    //deplacement des fichier
    if (move_uploaded_file($_FILES['pj_declaration']['tmp_name'], $uploadPath_DEC2)) {

    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    if (move_uploaded_file($_FILES['pj_demande_autorisation']['tmp_name'], $uploadPath_DA2)) {

    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    if (move_uploaded_file($_FILES['pj_engagement']['tmp_name'], $uploadPath_EN2)) {

    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    $update_validate='Exporté';
        //modification pour l'insertion
        $sql = "UPDATE `data_cc` SET 
        `num_pv_controle`='$num_pv', `mode_emballage`='$mode_emballage',
        `lieu_controle_pv`='$lieu_controle',`lieu_embarquement_pv`='$lieu_embarquement',
        `num_fiche_declaration_pv`='$num_fiche_declaration',`scan_demande_autorisation`='$uploadPath_DA',
        `date_fiche_declaration_pv`='$date_fiche_declaration',`pj_fiche_declaration_pv`='$uploadPath_DEC',`id_ancien_lp`='$id_ancien_lp',`date_demande_autorisation`='$date_demande',
        `date_engagement`='$dateEngagement',`scan_engagement`='$uploadPath_EN',
        `date_creation_pv_controle`='$dateInsert',`lien_pv_controle`='$pathToSavePV',`pj_pv_controle`='$pathToSavePDFPV',`date_modification_pv_controle`='$dateInsert',
        `validation_directeur`='En attente',`validation_chef`='En attente',`validation_controle`='En attente',
        `num_cc`='$num_cc',`date_cc`='$dateInsert',`lien_cc`='$lien_CDC',`pj_cc`='$pj_CDC' WHERE id_data_cc='$id_data'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $updateQuery = "UPDATE contenu_attestation SET validation_contenu = ? WHERE id_data_cc = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $update_validate, $id_data_cc);
            $updateStmt->execute();
            insertLogs($conn, $userID, $activite);
            $_SESSION['toast_message'] = "Insertion réussie.";
            header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
            exit();
        } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    
    ?>