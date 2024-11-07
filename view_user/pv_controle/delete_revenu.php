<?php
include_once('../../scripts/db_connect.php');
// Vérifier si une requête POST est reçue
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_lp'])) {
    $id_lp = $conn->real_escape_string($_POST['id_lp']);

    // Supprimer l'enregistrement de la base de données
    $sql = "DELETE FROM revenu WHERE id_lp='$id_lp'";
    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression: " . $conn->error;
    }
}
$conn->close();
?>