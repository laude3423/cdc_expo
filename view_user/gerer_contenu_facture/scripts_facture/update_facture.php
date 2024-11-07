<?php
include_once('../../../scripts/db_connect.php');
// require_once('../scripts/session_admin.php');
include_once('../../../scripts/session.php');
include_once('../../../histogramme/insert_logs.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activite="Modification d'une facture";
    // Récupérer les données du formulaire
    $id_data_cc = htmlspecialchars($_POST["id_data_cc"]);
    $num_facture = htmlspecialchars($_POST["num_facture_edit"]); 
    $date_facture = htmlspecialchars($_POST["date_facture_edit"]);

    $id_societe_expediteur = intval($_POST["id_societe_expediteur_edit"]);
    $id_societe_importateur = intval($_POST["id_societe_importateur_edit"]);
    $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_facture);
    $uploadDir = '../../upload/';
    $uploadDir2 = '../upload/';
    $fileName_DOM = "SCAN_FAC_" .$numDom_cleaned.".". pathinfo($_FILES['pj_facture']['name'],
    PATHINFO_EXTENSION);
    $uploadPath_FAC = $uploadDir . $fileName_DOM;
    $uploadPath_FAC2 = $uploadDir2 . $fileName_DOM;
    // Valider les données (ajoutez d'autres validations si nécessaire)
    if(isset($_FILES['pj_facture']) && $_FILES['pj_facture']['error'] == UPLOAD_ERR_OK){
        $sql = "UPDATE `data_cc` SET `pj_facture`='$uploadPath_FAC2' WHERE id_data_cc='$id_data_cc'";
        $result = mysqli_query($conn, $sql);
            if (move_uploaded_file($_FILES['pj_facture']['tmp_name'], $uploadPath_FAC)) {

            } else {
                echo "Erreur lors de l'upload du fichier.";
            }
    }
    // Insertion des données dans la base de données
    $validate="En attente";
    $query = "UPDATE data_cc SET num_facture = ?, date_facture = ?, id_societe_expediteur = ?, id_societe_importateur = ?, id_user = ?, validation_facture=? WHERE id_data_cc = ?";
    $stmt = $conn->prepare($query);

    // Liaison des paramètres avec bind_param
    $stmt->bind_param("ssiiisi", $num_facture, $date_facture, $id_societe_expediteur, $id_societe_importateur,$num_userID,$validate, $id_data_cc);

    // Exécution de la requête
    if ($stmt->execute()) {
        insertLogs($conn, $userID, $activite);
        $_SESSION['toast_message'] = "Modification réussie.";
             header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
        exit();
    } else {
            echo "Erreur d'enregistrement" . mysqli_error($conn);
    }
} else {
    // Redirection vers la page d'accueil ou une autre page si le formulaire n'a pas été soumis
    header("Location: ../view/commune_region.php");
    exit();
}