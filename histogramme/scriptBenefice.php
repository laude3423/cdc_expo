<?php
$dataRis = array_fill(0, 12, 0); // Initialiser un tableau avec 12 mois, tous à 0
$dataRed = array_fill(0, 12, 0); // Même chose pour les redevances
$taux_conversion = 4590;

// Récupérer les sommes par mois
$sql = "SELECT MONTH(date_cc) AS mois, SUM(ristourne) AS sommeRistourne, SUM(redevance) AS sommeRedevance 
        FROM data_cc WHERE YEAR(date_cc) = $annee 
        GROUP BY mois";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mois = $row['mois'] - 1; // Mois commence à 1, donc index 0 correspond à janvier
        $dataRis[$mois] = isset($row['sommeRistourne']) ? $row['sommeRistourne'] / $taux_conversion : 0;
        $dataRed[$mois] = isset($row['sommeRedevance']) ? $row['sommeRedevance'] / $taux_conversion : 0;
    }
}

// Encodez les tableaux en JSON
$data_jsonRis = json_encode($dataRis);
$data_jsonRed = json_encode($dataRed);
?>