<?php
// Inclure la classe PHPSpreadsheet
require '../../vendor/autoload.php';
require('../../scripts/session.php');
require_once('../../scripts/db_connect.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Exécution de la requête SQL pour récupérer les données
$query = "SELECT  cate.nom_categorie, sub.nom_substance, typeSub.nom_type_substance,
    cate.nom_categorie, cou.nom_couleur_substance, gr.nom_granulo, tr.nom_transparence, dg.nom_degre_couleur,
    f.nom_forme_substance, di.nom_durete, dim.nom_dimension_diametre, sds.prix_substance, sds.unite_prix_substance
        FROM substance_detaille_substance AS sds
        LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
        LEFT JOIN substance AS sub ON sub.id_substance= sds.id_substance
        LEFT JOIN type_substance AS typeSub ON typeSub.id_type_substance=sub.id_type_substance
        LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_detaille_substance
        LEFT JOIN granulo AS gr ON sds.id_granulo=gr.id_granulo
        LEFT JOIN transparence AS tr ON sds.id_transparence=tr.id_transparence
        LEFT JOIN degre_couleur AS dg ON sds.id_degre_couleur=dg.id_degre_couleur
        LEFT JOIN forme_substance AS f ON f.id_forme_substance=sds.id_forme_substance
        LEFT JOIN durete AS di ON di.id_durete=sds.id_durete
        LEFT JOIN dimension_diametre AS dim ON dim.id_dimension_diametre=sds.id_dimension_diametre";

$result = $conn->query($query);

// Vérifier si la requête a réussi
if (!$result) {
    die("La requête a échoué : " . $conn->error);
}
//renommer
$prefix_name = "Liste_sds_export_";
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