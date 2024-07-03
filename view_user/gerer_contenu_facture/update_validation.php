<?php
require_once('../../scripts/db_connect.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $status=1;
    $sql="UPDATE `data_cc` SET `validation_facture`='$status' WHERE id_data_cc=$userId";
    $result = mysqli_query($conn, $sql);
     if ($result) {
        $response['success'] = true;
        $response['message'] = 'Facture validé avec succès.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Échec de la validation : ' . mysqli_error($conn);
    }
    
}else {
    $response['success'] = false;
    $response['message'] = 'Requête invalide';
}

header('Content-Type: application/json');
echo json_encode($response);
?>