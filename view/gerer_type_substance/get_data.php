<?php
include "../db_connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM `type_substance` WHERE id_type_substance = $id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'Erreur lors de la récupération des données : ' . mysqli_error($conn)));
    }
} else {
    echo json_encode(array('error' => 'ID non spécifié.'));
}
?>
