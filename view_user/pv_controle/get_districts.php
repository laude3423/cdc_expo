<?php
include_once('../../scripts/connect_db_lp1.php');
if (isset($_POST['region_id'])) {
    $region_id = intval($_POST['region_id']);
    $query = "SELECT * FROM districts WHERE id_region = ?";
    $stmt = $conn_lp1->prepare($query);
    $stmt->bind_param("i", $region_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $districts = [];
    while ($row = $result->fetch_assoc()) {
        $districts[] = $row;
    }
    
    echo json_encode($districts);
}
?>