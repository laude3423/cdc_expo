<?php
require '../../../scripts/db_connect.php';
$typeSubstanceId = intval($_GET['substanceId']);
$query = "SELECT * FROM substance WHERE id_substance=?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $typeSubstanceId);
$stmt->execute();
$result = $stmt->get_result();
$rowType = $result->fetch_assoc();
$idType=$rowType['id_type_substance'];

$query = "SELECT DISTINCT c.* FROM substance_detaille_substance sds 
    LEFT JOIN categorie  c ON c.id_categorie = sds.id_categorie
    WHERE sds.id_categorie IS NOT NULL AND sds.id_substance = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $typeSubstanceId);
$stmt->execute();
$result = $stmt->get_result();

$substances = [];
while ($row = $result->fetch_assoc()) {
    if ($row['id_categorie'] == 2 && $idType == 4) {
        $row['nom_categorie'] = "Travaillé";
    }
    $substances[] = $row;
}
// $query = "SELECT * FROM substance WHERE id_substance=?";
// $stmt = $conn->prepare($query);
// $stmt->bind_param('i', $typeSubstanceId);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_assoc();
if($idType!='4'){
    $substances[] = [
    'id_categorie' => 3,
    'nom_categorie' => 'Preformé'
];
}
echo json_encode($substances);

$stmt->close();
$conn->close();
?>