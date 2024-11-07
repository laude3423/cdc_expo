<?php
if (isset($_POST['district_id'])) {
    include_once('../../scripts/connect_db_lp1.php');
    $district_id = intval($_POST['district_id']);

    $query = "SELECT * FROM communes WHERE id_district = ?";
    $stmt = $conn_lp1->prepare($query);
    $stmt->bind_param("i", $district_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $communes = [];
    while ($row = $result->fetch_assoc()) {
        $communes[] = $row;
    }
    
    echo json_encode($communes);
}
?>