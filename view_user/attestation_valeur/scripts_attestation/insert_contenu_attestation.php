<?php
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/connect_db_lp1.php');
include_once('../../../scripts/session.php');
include_once('../../../histogramme/insert_logs.php');

header('Content-Type: application/json');

$response = array("success" => false, "message" => "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_contenu = "";
    $id_attestation = isset($_POST["id_attestation"]) ? intval($_POST["id_attestation"]) : null;
    $id_substance = isset($_POST["id_substance"]) ? intval($_POST["id_substance"]) : null;
    $poids = isset($_POST["poids"]) ? floatval($_POST["poids"]) : null;
    $attente = "attente";
    // Vérification si toutes les données nécessaires sont présentes
    if ($id_attestation && $id_substance && $poids !== null) {

        // Récupération de l'attestation liée au contenu
        $query = "SELECT * FROM contenu_attestation WHERE id_substance = ? AND id_attestation=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_substance, $id_attestation);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_attestation = $row['id_attestation']; // Récupération de l'id_attestation
            $qte_actuel = $row['qte_actuel'] - $poids;
            $id_contenu = $row['id_contenu_attestation'];

            if($qte_actuel < 0){
                $response["success"] = true;
                $response["message"] = "La quantité du substance est insuffisante pour la quantité demandée";
            }else{
                $query2 = "SELECT * FROM contenu_attestation WHERE id_substance = ? AND id_attestation=? AND poids_attestation > 0 AND validation_contenu='attente'";
                $stmt = $conn->prepare($query2);
                $stmt->bind_param("ii", $id_substance, $id_attestation);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0){
                    $response["success"] = true;
                    $response["message"] = "Problème de redondance, vous pouvez le modifier.";
                }else{
                    $updateQuery = "UPDATE contenu_attestation SET qte_actuel = ?, poids_attestation = ?, validation_contenu = ? WHERE id_contenu_attestation = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("ddsi", $qte_actuel, $poids, $attente, $id_contenu);

                    if ($updateStmt->execute()) {
                        $activite="Insertion d'une nouvelle attestation";
                        $response["success"] = true;
                        $response["message"] = "Contenu mis à jour avec succès.";
                        insertLogs($conn, $userID, $activite);
                    } else {
                        $response["message"] = "Erreur lors de la mise à jour : " . $updateStmt->error;
                    }
                    $updateStmt->close();
                }
            }
          
        } else {
            $response["message"] = "Aucun contenu trouvé pour cette attestation.";
        }
        $stmt->close();
    } else {
        $response["message"] = "Données manquantes ou incorrectes.";
    }
} else {
    $response["message"] = "Requête non autorisée.";
}

echo json_encode($response);
?>