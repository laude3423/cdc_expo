<?php
if (isset($_POST['id_categorie']) && isset($_POST['id_famille'])) {
    $id_categorie = $_POST['id_categorie'];
    $id_famille = $_POST['id_famille'];

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require '../../../scripts/db_connect.php';

    $query = "SELECT DISTINCT s.* FROM substance_detaille_substance AS sds
              LEFT JOIN substance AS s ON s.id_substance = sds.id_substance
              WHERE sds.id_famille = ? AND sds.id_categorie = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $id_famille, $id_categorie);
    $stmt->execute();
    $resu = $stmt->get_result();

    // Génération des options
    $options_sub = "<option value=''>Sélectionner...</option>";
    while ($row = $resu->fetch_assoc()) {
        if (isset($row["id_substance"])) {
            $options_sub .= "<option value='" . $row['id_substance'] . "'>" . $row['nom_substance'] . "</option>";
        }
    }

    // Réponse JSON
    header('Content-Type: application/json');
    echo json_encode([
        'options_sub' => $options_sub
    ]);
}
?>