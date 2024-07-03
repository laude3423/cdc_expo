<?php
// Database connection
include '../../scripts/db_connect.php';

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les données POST
$id = $_POST['id'];
$prix_substance = $_POST['prix_substance'];
// Collect other fields as necessary
// Ex: $other_field = $_POST['other_field'];

$response = [];

if (isset($id) && isset($prix_substance)) {
    // Préparer la requête SQL
    $query = "UPDATE substance_detaille_substance SET prix_substance = ? WHERE id_detaille_substance = ?";
    $stmt = $conn->prepare($query);

    // Vérifier si la préparation de la requête a réussi
    if ($stmt) {
        // Lier les paramètres
        if ($stmt->bind_param('di', $prix_substance, $id)) { // Utilisez 'd' si prix_substance est un float

            // Exécuter la requête
            if ($stmt->execute()) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['error'] = 'Erreur lors de l\'exécution de la requête : ' . $stmt->error;
            }
        } else {
            $response['success'] = false;
            $response['error'] = 'Erreur lors de la liaison des paramètres : ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = 'Erreur lors de la préparation de la requête : ' . $conn->error;
    }
} else {
    $response['success'] = false;
    $response['error'] = 'Paramètres manquants';
}

echo json_encode($response);
$conn->close();
?>