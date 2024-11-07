<?php
// $dataDroit=array();
// $labelsDroit=array();
// $sql = "SELECT dir.nom_direction, SUM(dcc.droit_conformite) AS sommeDroit 
//         FROM data_cc AS dcc LEFT JOIN users AS u ON dcc.id_user=u.id_user
//         LEFT JOIN direction AS dir ON u.id_direction=dir.id_direction
//         WHERE YEAR(date_cc) = $annee 
//         GROUP BY dir.nom_direction";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $labelsDroit=['nom_direction'];
//         $dataDroit[$row['nom_direction']] = isset($row['droit_conformite']) ? $row['droit_conformite'] / $taux_conversion : 0;
//     }
// }

// // Encodez les tableaux en JSON
// $data_jsonDroit = json_encode($dataDroit);
// $labels_jsonNomDire = json_encode($labelsDroit);

// Initialiser un tableau avec 12 mois à 0
$dataDroit = array_fill(0, 12, 0);

$sql = "SELECT MONTH(date_cc) AS mois, SUM(droit_conformite) AS sommeDroit
        FROM data_cc WHERE YEAR(date_cc) = $annee 
        GROUP BY mois";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mois = $row['mois'] - 1; // Mois commence à 1, donc index 0 correspond à janvier
        $dataDroit[$mois] = isset($row['sommeDroit']) ? $row['sommeDroit'] / $taux_conversion : 0;
    }
}

// Encodez le tableau en JSON
$data_jsonDroit = json_encode(array_values($dataDroit));  // array_values pour garantir un tableau indexé

?>