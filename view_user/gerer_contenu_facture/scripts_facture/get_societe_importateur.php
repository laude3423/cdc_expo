<?php
// get_districts.php
// session_start();
// Connexion à la base de données
require '../../scripts/db_connect.php';

if (isset($_POST['id_data_cc'])) {
    $id_data_cc = $_POST['id_data_cc'];
    // $id_commune_origine = $_SESSION['id_commune_origine'];
    
    // Récupérer les districts en fonction de la région sélectionnée
    $query = "SELECT simp.id_societe_importateur , simp.nom_societe_importateur FROM societe_importateur simp 
    LEFT JOIN data_cc dcc ON dcc.id_societe_importateur = simp.id_societe_importateur 
    WHERE dcc.id_data_cc = $id_data_cc";
    // $query = "SELECT d.id_district, d.nom_district FROM districts  d
    // INNER JOIN communes c ON c.id_district = d.id_district WHERE d.id_region = $regionId AND c.id_commune = $id_commune_origine";
    $result = $conn->query($query);
    
    $options_district = "<option value=''>Sélectionner...</option>";
    while ($row = $result->fetch_assoc()) {
        $options_district .= "<option value='" . $row['id_societe_importateur'] . "'>" . $row['nom_societe_importateur'] . "</option>";
    }
    
    echo json_encode(['options_district' => $options_district]);
}

$conn->close();
?>
