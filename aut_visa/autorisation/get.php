<?php
require '../../scripts/db_connect.php';
if (isset($_POST['continent'])) {
    $id_continent = $_POST['continent'];
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    ob_start();
    
    $query_t = "SELECT * FROM pays WHERE id_continent = ?";
    $stmt = $conn->prepare($query_t);
    $stmt->bind_param("i", $id_continent);
    $stmt->execute();
    $result_t = $stmt->get_result();
    
    $options_pays = "<option value=''>Sélectionner...</option>";
    while ($row_t = $result_t->fetch_assoc()) {
        if (isset($row_t["id_pays"])) {
            $options_pays .= "<option value='" . $row_t['id_pays'] . "'>" . $row_t['nom_pays'] . "</option>";
        }
    }
    
    $output = ob_get_clean();
    
    if (!empty($output)) {
        echo "Unexpected output detected: $output";
    }
    
    // Réponse JSON
    header('Content-Type: application/json');
    echo json_encode([
        'options_pays' => $options_pays ?? '',
    ]);
}
?>