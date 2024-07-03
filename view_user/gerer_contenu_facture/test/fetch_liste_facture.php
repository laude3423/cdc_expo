<?php
require_once('../../../scripts/db_connect.php');
require_once('../../../scripts/session.php');
// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$limit = 10; // Nombre de lignes par page
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start = ($page - 1) * $limit;
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Requête SQL pour récupérer les factures avec la recherche
$sql = "SELECT dcc.*, sexp.*, simp.*
FROM data_cc dcc
LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
LEFT JOIN users u ON dcc.id_user = u.id_user
LEFT JOIN direction dir ON dir.id_direction = u.id_direction 
WHERE dir.id_direction = $id_direction AND dcc.num_facture LIKE '%$search%'
        LIMIT $start, $limit
        ORDER BY dcc.date_modification_facture DESC";

$result = $conn->query($sql);

$factures = array();
while ($row = $result->fetch_assoc()) {
    $factures[] = $row;
}

// Compter le nombre total de résultats pour la pagination
$sqlCount = "SELECT COUNT(*) AS total FROM data_cc dcc
LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
LEFT JOIN users u ON dcc.id_user = u.id_user
LEFT JOIN direction dir ON dir.id_direction = u.id_direction 
WHERE dir.id_direction = $id_direction AND dcc.num_facture LIKE '%$search%'";
$countResult = $conn->query($sqlCount);
$countRow = $countResult->fetch_assoc();
$total = $countRow['total'];

$response = array(
    'factures' => $factures,
    'total' => $total,
    'limit' => $limit,
    'page' => $page
);

echo json_encode($response);

$conn->close();
?>
