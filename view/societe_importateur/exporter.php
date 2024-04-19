<?php
// Inclure la classe PHPSpreadsheet
require '../../vendor/autoload.php';
require('../../scripts/session.php');
require_once('../../scripts/db_connect.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Exécution de la requête SQL pour récupérer les données

$query = "SELECT * FROM societe_expediteur";
$result = $conn->query($query);

// Vérifier si la requête a réussi
if (!$result) {
    die("La requête a échoué : " . $conn->error);
}
//renommer
$prefix_name = "Liste_societe_importateur";
$timezone = new DateTimeZone('Indian/Antananarivo');
$date4 = new DateTime('now', $timezone);
$dateTextFormat = "Y-m-d H:i:s";
$dateText = $date4->format($dateTextFormat);
$new_name="$prefix_name $dateText";
$Filename = preg_replace('/[^a-zA-Z0-9]/', '_', $new_name);
// Créer une nouvelle feuille de calcul
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Entêtes de colonnes
$columns = [];
while ($column = $result->fetch_field()) {
    $columns[] = $column->name;
}
$columnIndex = 'A';
foreach ($columns as $header) {
    $sheet->setCellValue($columnIndex . '1', $header);
    $columnIndex++;
}

// Données
$row = 2;
while ($row_data = $result->fetch_assoc()) {
    $columnIndex = 'A';
    foreach ($row_data as $cell_data) {
        $sheet->setCellValue($columnIndex . $row, $cell_data);
        $columnIndex++;
    }
    $row++;
}


$writer = new Xlsx($spreadsheet);

// Créer un tampon de sortie
ob_start();

// Enregistrer le fichier Excel dans le tampon de sortie
$writer->save('php://output');

// Définir les en-têtes pour indiquer qu'il s'agit d'un fichier Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$Filename.'.xlsx');
header('Cache-Control: max-age=0');

// Envoyer le tampon de sortie au navigateur
ob_end_flush();

// Arrêter l'exécution du script
exit;
?>