<?php
require_once('../../scripts/db_connect.php');

header('Content-Type: application/json'); // Set content type to JSON

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Préparez la requête de suppression
    $query = "UPDATE data_cc  SET `num_pv_scellage`='',`lien_pv_scellage`='',`pj_pv_scellage`='',`num_fiche_declaration_pv`='',`pj_fiche_declaration_pv`='',`date_fiche_declaration_pv`='',`date_creation_pv_scellage`='',`date_modification_pv_scellage`='' WHERE id_data_cc = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id); // 'i' indique un paramètre de type entier

    // Exécutez la requête
    if ($stmt->execute()) {
        $response = array('success' => true, 'message' => 'Suppression réussie');
        echo json_encode($response);
    } else {
        $response = array('success' => false, 'message' => 'Erreur lors de la suppression : ' . $stmt->error);
        echo json_encode($response);
    }
} else {
    $response = array('success' => false, 'message' => 'ID non spécifié');
    echo json_encode($response);
}
?>