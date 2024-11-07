<?php
require_once('../../scripts/db_connect.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT COUNT(*) AS row_count, SUM(poids_facture) AS total_weight FROM contenu_facture WHERE unite_poids_facture='kg' AND id_data_cc = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$response = array();

if (intval($row['row_count']) > 0) {
    $response['row_count'] = $row['row_count'];
    $response['total_weight'] = $row['total_weight'];
    $response['unite'] = 'Kg';
    $sql = "SELECT COUNT(*) AS row_count2, SUM(poids_facture) AS total_weight2 FROM contenu_facture WHERE unite_poids_facture='g' AND id_data_cc = ?";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    if (intval($row2['row_count2']) > 0) {
        $response['row_count'] = floatval($row['row_count']) + floatval($row2['row_count2']);
        $response['total_weight'] = floatval($row['total_weight']) + floatval($row2['total_weight2']) / 1000;
    }
} else {
    $sql = "SELECT COUNT(*) AS row_count2, SUM(poids_facture) AS total_weight2 FROM contenu_facture WHERE unite_poids_facture='g' AND id_data_cc = ?";
    $stmt2 = $conn->prepare($sql);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    if (intval($row2['row_count2']) > 0) {
        $response['unite'] = 'g';
        $response['row_count'] = floatval($row2['row_count2']);
        $response['total_weight'] = floatval($row2['total_weight2']);
    }else{
        $response['row_count'] = 0;
        $response['total_weight'] = 0;
        $response['unite'] = ' ';
    }
    
}

echo json_encode($response);

$conn->close();
?>