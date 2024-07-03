<?php
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/session.php');
// require_once('https://cdc.minesmada.org/scripts/db_connect.php');
// require_once('https://cdc.minesmada.org/scripts/session.php');
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $num_facture = htmlspecialchars($_POST["num_facture"]); 
    $date_facture = htmlspecialchars($_POST["date_facture"]);
    $id_societe_expediteur = htmlspecialchars($_POST["id_societe_expediteur"]);
    $id_societe_importateur = htmlspecialchars($_POST["id_societe_importateur"]);

    $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_facture);
    $uploadDir = '../../upload/';
    $uploadDir2 = '../upload/';
    $fileName_DOM = "SCAN_FAC_" .$numDom_cleaned.".". pathinfo($_FILES['pj_facture']['name'],
    PATHINFO_EXTENSION);
    $uploadPath_FAC = $uploadDir . $fileName_DOM;
    $uploadPath_FAC2 = $uploadDir2 . $fileName_DOM;
    
    if (move_uploaded_file($_FILES['pj_facture']['tmp_name'], $uploadPath_FAC)) {

    } else {
    echo "Erreur lors de l'upload du fichier.";
    }
    $validation_fac="En attente";
    $query = "INSERT INTO data_cc (num_facture, date_facture, id_societe_expediteur, id_societe_importateur, pj_facture, validation_facture) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Liaison des paramètres avec bind_param
    $stmt->bind_param("ssiiss", $num_facture, $date_facture, $id_societe_expediteur, $id_societe_importateur, $uploadPath_FAC2,$validation_fac);
    if ($stmt->execute()) {
        $id_data_cc = $conn->insert_id;
        $_SESSION['toast_message'] = "Insertion réussie.";
        
        // Redirection vers la page souhaitée
        header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
        exit();
    } else {
        // Affichage du message d'erreur
        echo "Erreur d'enregistrement : " . $stmt->error;
    }

} else {
    // Redirection vers la page d'accueil ou une autre page si le formulaire n'a pas été soumis
    header("Location: ../view/commune_region.php");
    exit();
}