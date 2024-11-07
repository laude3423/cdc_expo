<?php
// Inclure le fichier de connexion à la base de données
include "../../scripts/db_connect.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json; charset=UTF-8');

$response = array();
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM contenu_facture WHERE id_contenu_facture=?";
    $stmt23 = $conn->prepare($sql);
    $stmt23->bind_param("i", $id);
    $stmt23->execute();
    $resu23 = $stmt23->get_result();
    $row23 = $resu23->fetch_assoc();
    $id_data_cc=$row23['id_data_cc'];

    $query = "DELETE FROM contenu_facture WHERE id_contenu_facture = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        $response = array('success' => false, 'message' => 'Erreur de préparation de la requête : ' . $conn->error);
    } else {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $response = array('success' => true, 'message' => 'Suppression réussie');

            // Mettre à jour `validation_facture` dans `data_cc` après suppression
            $stmt3 = $conn->prepare("UPDATE data_cc SET `validation_facture` = 'En attente' WHERE id_data_cc = ?");
            if ($stmt3) {
                $stmt3->bind_param('i', $id_data_cc);
                $stmt3->execute();
                $stmt3->close();
            } else {
                $response['message'] .= ' (Erreur lors de la mise à jour : ' . $conn->error . ')';
            }
        } else {
            $response = array('success' => false, 'message' => 'Erreur lors de la suppression : ' . $stmt->error);
        }

        // Fermer la déclaration préparée
        $stmt->close();
    }
} else {
    $response = array('success' => false, 'message' => 'ID non spécifié');
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