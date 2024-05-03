<?php
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'], $_POST['action'])) {
    $userId = $_POST['userId'];
    $action = $_POST['action'];
    $status=0;
    if($action=='active'){
        $status = 1;
    }
    $sql="UPDATE `users` SET `status_user`='$status' WHERE id_user=$userId";
    $result = mysqli_query($conn, $sql);
     if ($result) {
        $response['success'] = true;
        $response['message'] = 'Statut mis à jour avec succès.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Échec de la mise à jour du statut : ' . mysqli_error($conn);
    }
    
}else {
    $response['success'] = false;
    $response['message'] = 'Requête invalide';
}

header('Content-Type: application/json');
echo json_encode($response);
?>