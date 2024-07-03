<?php
require '../../../scripts/db_connect.php';
$typeSubstanceId = intval($_GET['substanceId']);
$query = "SELECT DISTINCT c.* FROM substance_detaille_substance sds 
    LEFT JOIN categorie  c ON c.id_categorie = sds.id_categorie
    WHERE sds.id_categorie IS NOT NULL AND sds.id_substance = ?";
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