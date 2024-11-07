<?php
include_once('../../../scripts/db_connect.php');
require_once('../../../histogramme/insert_logs.php');
include_once('../../../scripts/session.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $id_data_cc = htmlspecialchars($_POST["data_cc"]);
    $num_attestation = htmlspecialchars($_POST["num_attestation_edit"]); 
    $date_attestation = htmlspecialchars($_POST["date_attestation_edit"]);
    $id_societe_expediteur = $_POST['id_societe_expediteur_edit'];
    $id_societe_importateur = $_POST['id_societe_importateur_edit'];
    date_default_timezone_set('Indian/Antananarivo');
    $heure_actuelle = date('H:i:s');
    //renommer l'heure
    $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
    $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_attestation);
    $uploadDir = '../../upload/';
    $uploadDir2 = '../upload/';
    $fileName_DOM = "SCAN_AV_" .$numDom_cleaned.$id_direction.$heureExacte_cleaned.".". pathinfo($_FILES['pj_attestation']['name'],
    PATHINFO_EXTENSION);
    $uploadPath_FAC = $uploadDir . $fileName_DOM;
    $uploadPath_FAC2 = $uploadDir2 . $fileName_DOM;
    // Valider les données (ajoutez d'autres validations si nécessaire)
    if(isset($_FILES['pj_attestation']) && $_FILES['pj_attestation']['error'] == UPLOAD_ERR_OK){
        $sql = "UPDATE `data_cc` SET `pj_attestation`='$uploadPath_FAC2' WHERE id_data_cc='$id_data_cc'";
        $result = mysqli_query($conn, $sql);
            if (move_uploaded_file($_FILES['pj_attestation']['tmp_name'], $uploadPath_FAC)) {

            } else {
                echo "Erreur lors de l'upload du fichier.";
            }
    }
    $query = "UPDATE data_cc SET id_societe_importateur = ?, id_societe_expediteur = ?,num_attestation = ?, date_attestation = ?, validation_attestation=? WHERE id_data_cc = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissi", $id_societe_importateur, $id_societe_expediteur,$num_attestation, $date_attestation, $validate, $id_data_cc);

    if ($stmt->execute()) {
        $activite="Modification d'une contenue d'attestation";
        insertLogs($conn, $userID, $activite);
        $_SESSION['toast_message'] = "Modification réussie.";
             header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_attestation/liste_contenu_attestation.php?id=" . $id_data_cc);
        exit();
    } else {
            echo "Erreur d'enregistrement" . mysqli_error($conn);
    }
} else {
    // Redirection vers la page d'accueil ou une autre page si le formulaire n'a pas été soumis
    header("Location: ../view/commune_region.php");
    exit();
}