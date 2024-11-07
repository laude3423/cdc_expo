<?php
require_once('../../scripts/db_connect.php');

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$limit = 10; // Nombre de lignes par page
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Requête SQL pour récupérer les substances avec la recherche
$sql = "SELECT sub_detail.*, sub.*, trans.*, forme.*, dure.*, cate.*, diam.*,gra.*, degre.*
        FROM substance_detaille_substance sub_detail
        INNER JOIN substance sub ON sub_detail.id_substance = sub.id_substance
        LEFT JOIN transparence trans ON sub_detail.id_transparence = trans.id_transparence
        LEFT JOIN categorie cate ON sub_detail.id_categorie = cate.id_categorie
        LEFT JOIN durete dure ON sub_detail.id_durete = dure.id_durete
        LEFT JOIN forme_substance forme ON sub_detail.id_forme_substance = forme.id_forme_substance
        LEFT JOIN dimension_diametre diam ON sub_detail.id_dimension_diametre = diam.id_dimension_diametre
        LEFT JOIN granulo gra ON sub_detail.id_granulo = gra.id_granulo
        LEFT JOIN degre_couleur degre ON sub_detail.id_degre_couleur = degre.id_degre_couleur
        WHERE sub.nom_substance LIKE '%$search%'
        LIMIT $start, $limit";

$result = $conn->query($sql);

$substances = array();
while ($row = $result->fetch_assoc()) {
    $substances[] = $row;
}

// Compter le nombre total de résultats pour la pagination
$sqlCount = "SELECT COUNT(*) AS total FROM substance_detaille_substance sub_detail
             INNER JOIN substance sub ON sub_detail.id_substance = sub.id_substance
             WHERE sub.nom_substance LIKE '%$search%'";
$countResult = $conn->query($sqlCount);
$countRow = $countResult->fetch_assoc();
$total = $countRow['total'];

$response = array(
    'substances' => $substances,
    'total' => $total,
    'limit' => $limit,
    'page' => $page
);

echo json_encode($response);

$conn->close();
?>