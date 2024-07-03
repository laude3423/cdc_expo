<?php
require '../../../scripts/db_connect.php';
$typeSubstanceId = intval($_GET['typeSubstanceId']);
$query = "SELECT * FROM substance WHERE id_type_substance = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $typeSubstanceId);
$stmt->execute();
$result = $stmt->get_result();

$substances = [];
while ($row = $result->fetch_assoc()) {
    $substances[] = $row;
}

echo json_encode($substances);

$stmt->close();
$conn->close();
?>