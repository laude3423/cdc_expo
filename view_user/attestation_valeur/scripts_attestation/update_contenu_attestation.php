<?php 
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/session.php');
include_once('../../../histogramme/insert_logs.php');
include_once('../../../scripts/connect_db_lp1.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_substance = intval($_POST['id_substance']);
    $poids = floatval($_POST['poids']);
    $id_data_cc = intval($_POST['num_data']);
    $id_contenu = intval($_POST['id_contenu']);
    $numero_lp = $_POST['numero_lp'];
    $id_lp = $_POST['id_lp'];
    $unite = $_POST['unite'];

    if((!empty($numero_lp))&&(isset($_FILES['scan_lp']) && $_FILES['scan_lp']['error'] == UPLOAD_ERR_OK)){
        $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $numero_lp);

        $uploadDir2 = '../upload/';
        $fileName_DOM = "SCAN_LP_" .$numDom_cleaned.".". pathinfo($_FILES['scan_lp']['name'],
        PATHINFO_EXTENSION);
        $uploadPath_LP = $uploadDir2 . $fileName_DOM;
        if (move_uploaded_file($_FILES['scan_lp']['tmp_name'], $uploadPath_LP)) {
            $query = "UPDATE lp_scan SET scan_lp=? WHERE id_lp_scan = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $uploadPath_LP, $id_lp);
            $stmt->execute();
        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
    }else if(!empty($numero_lp)){
        $query = "UPDATE lp_scan SET numero_lp=? WHERE id_lp_scan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $numero_lp, $id_lp);
        $stmt->execute();
    }else if(empty($numero_lp)){
        $id_lp= NULL;
    }
        $query = "UPDATE contenu_attestation SET id_substance = ?, poids_attestation = ?, unite = ?, id_lp_scan=? WHERE id_contenu_attestation = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("idsii", $id_substance,  $poids,$unite, $id_lp, $id_contenu);
        if ($stmt->execute()) {
            $activite="Modification d'une contenue de l'attestation";
            insertLogs($conn, $userID, $activite);
            header("Location: https://cdc.minesmada.org/view_user/attestation_valeur/liste_contenu_attestation.php?id=" . $id_data_cc);
            exit();
        } else {
            echo "Erreur d'enregistrement : " . $stmt->error;
        }
    
}