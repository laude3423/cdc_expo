<?php
include_once('../../../scripts/db_connect.php');
// require_once('../scripts/session_admin.php');
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $id_data_cc = htmlspecialchars($_POST["id_data_cc"]);
    $num_facture = htmlspecialchars($_POST["num_facture_edit"]); 
    $date_facture = htmlspecialchars($_POST["date_facture_edit"]);

    $id_societe_expediteur = intval($_POST["id_societe_expediteur_edit"]);
    $id_societe_importateur = intval($_POST["id_societe_importateur_edit"]);

    // Valider les données (ajoutez d'autres validations si nécessaire)

    // Insertion des données dans la base de données
    $query = "UPDATE data_cc SET num_facture = ?, date_facture = ?, id_societe_expediteur = ?, id_societe_importateur = ? WHERE id_data_cc = ?";
    $stmt = $conn->prepare($query);

    // Liaison des paramètres avec bind_param
    $stmt->bind_param("ssiii", $num_facture, $date_facture, $id_societe_expediteur, $id_societe_importateur, $id_data_cc);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Utilisation de la fonction urlencode pour éviter les problèmes avec les espaces dans l'URL
        header("Location: ../liste_facture.php?success_district=" . urlencode("La facture a été modifiée avec succès."));
        exit();
    } else {
        // En cas d'échec, afficher une erreur ou rediriger vers une page d'erreur
        header("Location: ../pages/error.php");
        exit();
    }
} else {
    // Redirection vers la page d'accueil ou une autre page si le formulaire n'a pas été soumis
    header("Location: ../view/commune_region.php");
    exit();
}
