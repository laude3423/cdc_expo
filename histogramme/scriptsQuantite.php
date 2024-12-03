<?php
$dataDroit = array();
$labelsDroit = array();
$id_DIRE = array(); // Tableau pour stocker les directions de la première requête

$id_DIRE_Count = array();
$labelsCountDire = array();  
$dataCountDire = array();
$dataSubstance=array();
$labelsSubstance = array();
// Première requête
$totalCC = 0;

$sql = "SELECT dir.*, dcc.date_cc,
               SUM(CASE 
                   WHEN cfac.unite_poids_facture = 'g' THEN cfac.poids_facture / 1000 
                   ELSE cfac.poids_facture 
               END) AS quantite_kg
        FROM contenu_facture AS cfac 
        LEFT JOIN data_cc AS dcc ON cfac.id_data_cc = dcc.id_data_cc 
        LEFT JOIN users AS u ON dcc.id_user = u.id_user 
        LEFT JOIN direction AS dir ON u.id_direction = dir.id_direction 
        WHERE YEAR(dcc.date_cc) = $annee
        GROUP BY dir.nom_direction";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_DIRE[] = $row['id_direction'];  // Stocke l'ID de la direction
        $labelsDroit[] = $row['sigle_direction'];  // Stocke le sigle de la direction
        $dataDroit[$row['nom_direction']] = isset($row['quantite_kg']) ? $row['quantite_kg'] : 0;
    }
}

// Stocke le sigle de la direction
$sql = "SELECT dir.*, dcc.date_cc, COUNT(dcc.num_cc) AS countCC FROM data_cc AS dcc
        LEFT JOIN users AS u ON dcc.id_user = u.id_user 
        LEFT JOIN direction AS dir ON u.id_direction = dir.id_direction 
        WHERE YEAR(dcc.date_cc) = $annee
        AND dcc.validation_chef = 'Validé' 
        AND dcc.validation_directeur = 'Validé'
        GROUP BY dir.nom_direction 
        ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id_DIRE_Count[] = $row['id_direction'];
        $labelsCountDire[] = $row['sigle_direction'];  
        $dataCountDire[$row['nom_direction']] = isset($row['countCC']) ? $row['countCC'] : 0;
    }
}
// Deuxième requête pour obtenir toutes les directions
$requete = $conn->prepare("SELECT * FROM direction WHERE sigle_direction IN ('DIR.ANSAND', 'DIR.A', 'DR.AA', 'DIR.M', 'DR.SAVA', 'DIR.TO', 'DR.VAK', 'DR.DIANA', 'GU')");
$requete->execute();
$resultat = $requete->get_result();

// Parcourir les résultats de la deuxième requête
while ($row = $resultat->fetch_assoc()) {
    // Si la direction n'est pas dans les résultats de la première requête, ajoutez-la avec une quantité de 0
    if (!in_array($row['id_direction'], $id_DIRE)) {
        $labelsDroit[] = $row['sigle_direction'];  // Ajouter le sigle à $labelsDroit
        $dataDroit[$row['nom_direction']] = 0;  // Ajouter la quantité 0 pour cette direction
    }
    if (!in_array($row['id_direction'], $id_DIRE_Count)) {
        $labelsCountDire[] = $row['sigle_direction'];  // Ajouter le sigle à $labelsDroit
        $dataCountDire[$row['nom_direction']] = 0;  // Ajouter la quantité 0 pour cette direction
    }
}
$sqlTotalCC = "SELECT COUNT(*) AS total 
FROM data_cc 
WHERE YEAR(date_cc) = $annee
  AND validation_chef = 'Validé' 
  AND validation_directeur = 'Validé'
";
$resultTotalCC = $conn->query($sqlTotalCC);

if ($resultTotalCC && $rowTotalCC = $resultTotalCC->fetch_assoc()) {
    $totalCC = (int)$rowTotalCC['total'];
} else {
    $totalCC = 0; // Par défaut à zéro s'il y a une erreur
}
$sql = "SELECT sub.*, dcc.date_cc,
               SUM(CASE 
                   WHEN cfac.unite_poids_facture = 'g' THEN cfac.poids_facture / 1000 
                   ELSE cfac.poids_facture 
               END) AS quantite_kg
        FROM contenu_facture AS cfac 
        LEFT JOIN data_cc AS dcc ON cfac.id_data_cc = dcc.id_data_cc 
        LEFT JOIN substance_detaille_substance AS sds ON sds.id_detaille_substance = cfac.id_detaille_substance
        LEFT JOIN substance AS sub ON sds.id_substance = sub.id_substance 
        WHERE YEAR(dcc.date_cc) = $annee
        GROUP BY sub.id_substance
        ORDER BY quantite_kg DESC
        LIMIT 9";  // Limite les résultats aux 9 substances avec les quantités les plus élevées

$result = $conn->query($sql);
$quantiteMax = 0; // Variable pour stocker la quantité maximale

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labelsSubstance[] = $row['nom_substance'];  // Stocke le nom de la substance
        $dataSubstance[$row['nom_substance']] = isset($row['quantite_kg']) ? $row['quantite_kg'] : 0;
        // Mettre à jour la quantité maximale
        if ($dataSubstance[$row['nom_substance']] > $quantiteMax) {
            $quantiteMax = $dataSubstance[$row['nom_substance']];
        }
    }
}

// Encodez les tableaux en JSON
$data_jsonDirection = json_encode(array_values($dataDroit));
$labels_jsonDirection = json_encode($labelsDroit);


// $data_jsonSubstance = json_encode(array_values($labelsSubstance));
// $labels_jsonSubstance = json_encode($dataSubstance);

?>