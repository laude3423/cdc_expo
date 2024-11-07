<?php
require '../../scripts/db_connect.php';
header('Content-Type: application/json');

// Commencez la capture de la sortie
ob_start();

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_substance = $_POST['id_substance_dire'];
    $granulo_facture = isset($_POST["granulo_facture_dire"]) ? intval($_POST["granulo_facture_dire"]) : null;
    $id_transparence = isset($_POST["id_transparence_dire"]) ? intval($_POST["id_transparence_dire"]) : null;
    $id_famille = isset($_POST["famille"]) ? intval($_POST["famille"]) : null;
    $id_durete = isset($_POST["id_durete_dire"]) ? intval($_POST["id_durete_dire"]) : null;
    $id_couleur_substance = isset($_POST["id_couleur_substance_dire"]) ? intval($_POST["id_couleur_substance_dire"]) : null;
    $id_categorie = isset($_POST["id_categorie_dire"]) ? intval($_POST["id_categorie_dire"]) : null;
    $id_forme_substance = isset($_POST["id_forme_substance_dire"]) ? intval($_POST["id_forme_substance_dire"]) : null;
    $id_dimension_diametre = isset($_POST["id_dimension_diametre_dire"]) ? intval($_POST["id_dimension_diametre_dire"]) : null;

    $query_detail_substance = "SELECT * FROM substance_detaille_substance 
        WHERE id_substance = ? 
        AND (id_couleur_substance = ? OR id_couleur_substance IS NULL)
        AND (id_granulo = ? OR id_granulo IS NULL)
        AND (id_transparence = ? OR id_transparence IS NULL)
        AND (id_famille = ? OR id_famille IS NULL)
        AND (id_categorie = ? OR id_categorie IS NULL)
        AND (id_durete = ? OR id_durete IS NULL)
        AND (id_forme_substance = ? OR id_forme_substance IS NULL)
        AND (id_dimension_diametre = ? OR id_dimension_diametre IS NULL)";

    $stmt_detail_substance = $conn->prepare($query_detail_substance);
    $stmt_detail_substance->bind_param("iiiiiiiii", $id_substance, $id_couleur_substance, $granulo_facture, $id_transparence, $id_famille, $id_categorie, $id_durete, $id_forme_substance, $id_dimension_diametre);
    $stmt_detail_substance->execute();
    $result_detail_substance = $stmt_detail_substance->get_result();

    if ($result_detail_substance) {
        if ($result_detail_substance->num_rows > 0) {
            $row = $result_detail_substance->fetch_assoc();
            $response = ['status' => 'success', 'prix_substance' => $row['prix_substance']];
        } else {
            $response = ['status' => 'error', 'message' => 'Aucun prix trouvé pour cette combinaison.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Erreur lors de l\'exécution de la requête.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Méthode de requête invalide.'];
}

// Capturer toute sortie non désirée et la nettoyer
$output = ob_get_clean();
if (!empty($output)) {
    error_log("Unexpected output: " . $output);
}

// Envoyer la réponse JSON
echo json_encode($response);
?>