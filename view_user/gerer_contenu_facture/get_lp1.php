<?php
// get_districts.php
// session_start();
// Connexion à la base de données
if (isset($_POST['id_substance'])) {
    $id_substance = $_POST['id_substance'];
    $id_direction = $_POST['id_direction'];
    $date_lp = $_POST['date_lp'];
    $date_lp = $_POST['date_lp'];
    $date = new DateTime($date_lp);
    $date->modify('-1 day');
    $new_date_lp = $date->format('Y-m-d');
    error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
// Connexion à la deuxième base de données
require '../../scripts/connect_db_lp1.php';
require '../../scripts/db_connect.php';
$sql = "SELECT * FROM substance WHERE id_substance = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_substance);
$stmt->execute();
$resu = $stmt->get_result();
$row = $resu->fetch_assoc();
$nom_substance = $row['nom_substance'];
$nom_substance = explode(' ', trim($nom_substance))[0];

$query = "SELECT lp.*, s.*, pr.*, dir.* 
FROM lp_info AS lp
INNER JOIN produits AS pr ON lp.id_produit = pr.id_produit
INNER JOIN substance AS s ON pr.id_substance = s.id_substance
INNER JOIN directions AS dir ON lp.id_direction = dir.id_direction
WHERE lp.validation_admin = 1 
  AND dir.id_direction = ? 
  AND DATE(lp.date_modification) = ? 
  AND s.nom_substance LIKE ? 
  AND lp.expire_lp IS NULL";
    $stmt_lp1 = $conn_lp1->prepare($query);
    $search_term = '%' . $conn_lp1->real_escape_string($nom_substance) . '%';
    $stmt_lp1->bind_param('iss',$id_direction, $new_date_lp, $search_term);
    $stmt_lp1->execute();
    $result = $stmt_lp1->get_result();

// Génération des options
$options_lp1 = "<option value=''>Sélectionner...</option>";
while ($row = $result->fetch_assoc()) {
    if (isset($row["id_lp"])) {
        $options_lp1 .= "<option value='" . $row['id_lp'] . "'>" . $row['num_LP'] . " (" . $row['nom_substance'] . ") (" . $row['unite'] . ")</option>";
    }
}
$output = ob_get_clean();

if (!empty($output)) {
    echo "Unexpected output detected: $output";
}
// Réponse JSON
header('Content-Type: application/json');
echo json_encode([
    'options_lp1' => $options_lp1
]);
}
?>