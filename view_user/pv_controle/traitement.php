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
    // if(!empty($chef)){
    //     // Vérifier si les données existent déjà
    //     $check_sql = "SELECT * FROM `pv_agent_assister` WHERE `id_agent` = '$chef' AND `id_data_cc` = '$id_data'";
    //     $check_result = mysqli_query($conn, $check_sql);

    //     // Si les données n'existent pas, insérer
    //     if(mysqli_num_rows($check_result) == 0){
    //         $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$id_data')";
    //         $result = mysqli_query($conn, $sql);
    //     }
    // }

    // if(!empty($qualite)){
    //     // Vérifier si les données existent déjà
    //     $check_sql = "SELECT * FROM `pv_agent_assister` WHERE `id_agent` = '$qualite' AND `id_data_cc` = '$id_data'";
    //     $check_result = mysqli_query($conn, $check_sql);

    //     // Si les données n'existent pas, insérer
    //     if(mysqli_num_rows($check_result) == 0){
    //         $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$id_data')";
    //         $result = mysqli_query($conn, $sql);
    //     }
    // }
    // Modification pour l'insertion
    $id_data = intval($id_data); // S'assurer que $id_data est un entier

// S'assurer que $id_data est un entier
$id_data = intval($id_data); 
$attante="En attente";
// Préparation de la requête SQL avec 23 placeholders
$sql = "UPDATE `data_cc` SET 
    `num_pv_controle`=?, 
    `mode_emballage`=?, 
    `lieu_controle_pv`=?, 
    `lieu_embarquement_pv`=?, 
    `num_domiciliation`=?, 
    `num_fiche_declaration_pv`=?, 
    `pj_domiciliation_pv`=?, 
    `date_fiche_declaration_pv`=?, 
    `pj_fiche_declaration_pv`=?, 
    `num_lp3e_pv`=?, 
    `date_lp3e`=?, 
    `pj_lp3e_pv`=?, 
    `date_creation_pv_controle`=?, 
    `lien_pv_controle`=?, 
    `pj_pv_controle`=?, 
    `date_modification_pv_controle`=?, 
    `validation_directeur`=?, 
    `validation_chef`=?, 
    `validation_controle`=?, 
    `date_dom`=?, 
    `num_cc`=?, 
    `date_cc`=?, 
    `lien_cc`=?, 
    `pj_cc`=? 
    WHERE id_data_cc=?";

// Préparation de la requête
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die('Erreur de préparation de la requête: ' . mysqli_error($conn));
}

// Vérification du nombre de paramètres (23 types attendus)
$bind_result = mysqli_stmt_bind_param($stmt, 'ssssssssssssssssssssssssi', 
    $num_pv,               // string
    $mode_emballage,        // string
    $lieu_controle,         // string
    $lieu_embarquement,     // string
    $num_domiciliation,     // string
    $num_fiche_declaration, // string
    $uploadPath_DOM,        // string
    $date_declaration,      // string
    $uploadPath_DEC,        // string
    $num_lp3e,              // string
    $date_lp3e,             // string
    $uploadPath_LP3,        // string
    $dateInsert,            // string
    $pathToSave,            // string
    $pathToSavePDF,         // string
    $dateInsert,  
    $attante,
    $attante,
    $attante,           // string
    $dateDom,   // string
    $num_cc,                // string
    $dateInsert,            // string
    $lien_cc,               // string
    $pj_cc,                 // string
    $id_data                // int
);

if ($bind_result === false) {
    die('Erreur de liaison des paramètres: ' . mysqli_error($conn));
}

// Exécution de la requête
$result = mysqli_stmt_execute($stmt);

        if ($result) {
            $stmt = $conn->prepare("INSERT INTO `incrementation`( `id_data_cc`, `id_direction`, `incrementation`, `date_incrementation`) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $id_data, $id_direction, $nouvelle_incrementation, $dateInsert);
            $stmt->execute();
            insertLogs($conn, $userID, $activite);
            $_SESSION['toast_message'] = "Insertion réussie.";
            header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
            exit();
        } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    
    ?>