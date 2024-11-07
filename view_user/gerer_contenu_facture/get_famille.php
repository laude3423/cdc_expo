<?php
// get_districts.php
// session_start();
// Connexion à la base de données
if (isset($_POST['id_categorie'])) {
    $id_categorie = $_POST['id_categorie'];

    error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
require '../../scripts/db_connect.php';
$sql = "SELECT DISTINCT fa.* FROM substance_detaille_substance AS sds LEFT JOIN famille AS fa ON
sds.id_famille = fa.id_famille WHERE sds.id_categorie = ? AND sds.id_famille IS NOT NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_categorie);
$stmt->execute();
$resu = $stmt->get_result();
// Génération des options
$options_famille = "<option value=''>Sélectionner...</option>";
while ($row = $resu->fetch_assoc()) {
    if (isset($row["id_famille"])) {
        $options_famille .= "<option value='" . $row['id_famille'] . "'>" . $row['nom_famille'] ."</option>";
    }
}
$output = ob_get_clean();

if (!empty($output)) {
    echo "Unexpected output detected: $output";
}
// Réponse JSON
header('Content-Type: application/json');
echo json_encode([
    'options_famille' => $options_famille
]);
}
?>