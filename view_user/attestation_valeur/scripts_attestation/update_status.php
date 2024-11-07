<?php
require '../../../scripts/db_connect.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validation = $_POST['validation'];
    $id_contenu = $_POST['id_contenu_validation'];
    $id_attestation = $_POST['id_attestation'];
    
    // Récupérer tous les id_contenu_attestation où validation_contenu est 'attente'
    $sql4 = "SELECT * FROM contenu_attestation WHERE validation_contenu = 'attente' AND id_attestation = ?";
    $stmt = $conn->prepare($sql4);
    $stmt->bind_param("i", $id_attestation);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
    while ($row = $result->fetch_assoc()) {
        $id_contenu = $row['id_contenu_attestation'];
        $update_sql = "UPDATE contenu_attestation SET validation_contenu = ? WHERE id_contenu_attestation = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $validation, $id_contenu);

        if ($update_stmt->execute()) {
            $update_sql = "UPDATE attestation SET validation_attestation = ? WHERE id_attestation = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $validation, $id_attestation);
            $update_stmt->execute();

            $_SESSION['toast_message'] = "Modification réussie";
        } else {
            echo "Erreur d'enregistrement pour l'ID $id_contenu: " . $update_stmt->error;
        }
    }

    header("Location: https://cdc.minesmada.org/view_user/attestation_valeur/liste_contenu_attestation.php?id=" . $id_attestation);
    exit();
} else {
    echo "Aucun enregistrement trouvé.";
}
}
?>