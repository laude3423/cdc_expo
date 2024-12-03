<?php
$labelMoi = array();
$quantite = array();
$dataSubstance=array();

// Tableau associatif des mois en français
$moisFrancais = [
    1 => 'Janvier',
    2 => 'Février',
    3 => 'Mars',
    4 => 'Avril',
    5 => 'Mai',
    6 => 'Juin',
    7 => 'Juillet',
    8 => 'Août',
    9 => 'Septembre',
    10 => 'Octobre',
    11 => 'Novembre',
    12 => 'Décembre'
];

$sql = "SELECT 
            MONTH(date_creation) AS mois,
            SUM(CASE 
                WHEN unite = 'g' THEN poids / 1000 
                ELSE poids 
            END) AS quantite_kg
        FROM autorisation 
        WHERE YEAR(date_creation) = $annee
        GROUP BY mois
        ORDER BY mois";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Remplacer le numéro par le nom du mois en français
        $labelMoi[] = $moisFrancais[$row['mois']];
        $quantite[] = $row['quantite_kg'];
    }
}

// $sql = "SELECT autorisation
//                SUM(CASE 
//                 WHEN unite = 'g' THEN poids / 1000 
//                 ELSE poids 
//             END) AS quantite_kg
//         FROM autorisation
//         WHERE YEAR(date_creation) = $annee
//         GROUP BY sub.id_substance
//         ORDER BY quantite_kg DESC
//         LIMIT 10";  // Limite les résultats aux 9 substances avec les quantités les plus élevées

// $result = $conn->query($sql);
// $quantiteMax = 0; // Variable pour stocker la quantité maximale

// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $labelsSubstance[] = $row['nom_substance'];  // Stocke le nom de la substance
//         $dataSubstance[$row['nom_substance']] = isset($row['quantite_kg']) ? $row['quantite_kg'] : 0;
//         // Mettre à jour la quantité maximale
//         if ($dataSubstance[$row['nom_substance']] > $quantiteMax) {
//             $quantiteMax = $dataSubstance[$row['nom_substance']];
//         }
//     }
// }

// Conversion en JSON pour JavaScript
$data_jsonQuantite = json_encode($quantite);
$labels_jsonMoi = json_encode($labelMoi);


?>