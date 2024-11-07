<?php
require_once('../scripts/db_connect.php');
require_once('../scripts/connect_db_lp1.php');
  $sql = "SELECT * FROM contenu_facture WHERE quantite_lp1_actuel_lp1_suivis=0 AND id_lp1_info IS NOT NULL";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $id_lp_info = $row['id_lp1_info'];

            $sql = "SELECT * FROM lp_info WHERE id_lp='$id_lp_info' AND expire_lp IS NULL";
            $result2 = $conn_lp1->query($sql);
            if($result2 && $result2->num_rows > 0){
                $row2 = $result2->fetch_assoc();
                $id_lp= $row2['id_lp'];

                $query = "UPDATE lp_info SET expire_lp = 'oui' WHERE id_lp = ?";
                $stmt = $conn_lp1->prepare($query);
                $stmt->bind_param("i", $id_lp);
                $stmt->execute();
            }
    }
}
?>