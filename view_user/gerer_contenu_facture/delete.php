<?php
// Inclure le fichier de connexion à la base de données
include "../../scripts/db_connect.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=UTF-8');

ob_start();

$response = array();
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM contenu_facture WHERE id_contenu_facture = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        $response = array('success' => false, 'message' => 'Erreur de préparation de la requête : ' . $conn->error);
    } else {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Suppression réussie');
        } else {
            $response = array('success' => false, 'message' => 'Erreur lors de la suppression : ' . $stmt->error);
        }

        // Fermer la déclaration préparée
        $stmt->close();
    }
} else {
    $response = array('success' => false, 'message' => 'ID non spécifié');
}

$output = ob_get_clean();

if (!empty($output)) {
    $response['output'] = $output;
}

// Encoder la réponse en JSON
$json_response = json_encode($response);

// Vérifier si l'encodage JSON a échoué
if ($json_response === false) {
    $json_response = json_encode(array('success' => false, 'message' => 'Erreur JSON : ' . json_last_error_msg(), 'original_response' => $response));
}

// Envoyer la réponse JSON
echo $json_response;

// Fermer la connexion à la base de données
$conn->close();
?>