<?php
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/session.php');
// require_once('https://cdc.minesmada.org/scripts/db_connect.php');
// require_once('https://cdc.minesmada.org/scripts/session.php');
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $num_facture = htmlspecialchars($_POST["num_facture"]); 
    $date_facture = htmlspecialchars($_POST["date_facture"]);

    $id_societe_expediteur = htmlspecialchars($_POST["id_societe_expediteur"]);
    $id_societe_importateur = htmlspecialchars($_POST["id_societe_importateur"]);

    // Valider les données (ajoutez d'autres validations si nécessaire)

    // Insertion des données dans la base de données
    $query = "INSERT INTO data_cc (num_facture, date_facture, id_societe_expediteur, id_societe_importateur, id_user) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Liaison des paramètres avec bind_param
    $stmt->bind_param("ssiii", $num_facture, $date_facture, $id_societe_expediteur, $id_societe_importateur, $userID);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Utilisation de la fonction urlencode pour éviter les problèmes avec les espaces dans l'URL
        header("Location: ../liste_facture.php?success_district=" . urlencode("Le facture a été ajoutée avec succès."));
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

