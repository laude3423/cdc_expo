<?php
require_once('../../../scripts/db_connect.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT COUNT(*) AS row_count, SUM(poids_attestation) AS total_weight FROM contenu_attestation WHERE id_attestation = ? AND poids_attestation IS NOT NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$response = array();

if (intval($row['row_count']) > 0) {
    $response['row_count'] = $row['row_count'];
    $response['total_weight'] = $row['total_weight'];
    $response['unite'] = 'g';
    
}else{
    $response['row_count'] = 0;
    $response['total_weight'] = 0;
    $response['unite'] = ' ';
}

echo json_encode($response);

$conn->close();
?>