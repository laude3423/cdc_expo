<?php
// Inclure la classe PHPSpreadsheet
require '../../vendor/autoload.php';
require('../../scripts/session.php');
require_once('../../scripts/db_connect.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$id_data_cc= $_GET['id_data_cc'];
$query_direction = "SELECT nom_direction FROM direction WHERE id_direction = $id_direction";
$result = $conn->query($query_direction);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $nom_direction = $row["nom_direction"];
}
// Exécution de la requête SQL pour récupérer les données
$query="";
if($groupeID===2){
    $query = "SELECT sub.nom_substance,cou.nom_couleur_substance, typeSub.nom_type_substance,gr.nom_granulo, tr.nom_transparence, dg.nom_degre_couleur, 
    contenu.poids_facture, contenu.unite_poids_facture,contenu.prix_unitaire_facture, prix_unitaire_facture * poids_facture AS prix_total,
    cate.nom_categorie, di.nom_durete, dim.nom_dimension_diametre, f.nom_forme_substance
        FROM contenu_facture AS contenu
        INNER JOIN data_cc dcc ON dcc.id_data_cc=contenu.id_data_cc
        LEFT JOIN substance_detaille_substance AS sds ON sds.id_detaille_substance= contenu.id_detaille_substance
        LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
        LEFT JOIN substance AS sub ON sub.id_substance= sds.id_substance
        LEFT JOIN type_substance AS typeSub ON typeSub.id_type_substance=sub.id_type_substance
        LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_couleur_substance
        LEFT JOIN dimension_diametre AS dim ON dim.id_dimension_diametre=sds.id_dimension_diametre
        LEFT JOIN granulo AS gr ON sds.id_granulo=gr.id_granulo
        LEFT JOIN transparence AS tr ON sds.id_transparence=tr.id_transparence
        LEFT JOIN degre_couleur AS dg ON sds.id_degre_couleur=dg.id_degre_couleur
        LEFT JOIN forme_substance AS f ON f.id_forme_substance=sds.id_forme_substance
        LEFT JOIN users AS us ON us.id_user=dcc.id_user
        LEFT JOIN direction AS dir ON dir.id_direction= us.id_direction
        LEFT JOIN durete AS di ON di.id_durete=sds.id_durete
        WHERE dcc.id_data_cc=$id_data_cc GROUP BY contenu.id_contenu_facture";

    }else{
    $query = "SELECT sub.nom_substance,cou.nom_couleur_substance, typeSub.nom_type_substance,gr.nom_granulo, tr.nom_transparence, dg.nom_degre_couleur, 
    contenu.poids_facture, contenu.unite_poids_facture,contenu.prix_unitaire_facture, prix_unitaire_facture * poids_facture AS prix_total,
    cate.nom_categorie, di.nom_durete, dim.nom_dimension_diametre, f.nom_forme_substance
        FROM contenu_facture AS contenu
        INNER JOIN data_cc dcc ON dcc.id_data_cc=contenu.id_data_cc
        LEFT JOIN substance_detaille_substance AS sds ON sds.id_detaille_substance= contenu.id_detaille_substance
        LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
        LEFT JOIN substance AS sub ON sub.id_substance= sds.id_substance
        LEFT JOIN type_substance AS typeSub ON typeSub.id_type_substance=sub.id_type_substance
        LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_couleur_substance
        LEFT JOIN dimension_diametre AS dim ON dim.id_dimension_diametre=sds.id_dimension_diametre
        LEFT JOIN granulo AS gr ON sds.id_granulo=gr.id_granulo
        LEFT JOIN transparence AS tr ON sds.id_transparence=tr.id_transparence
        LEFT JOIN degre_couleur AS dg ON sds.id_degre_couleur=dg.id_degre_couleur
        LEFT JOIN forme_substance AS f ON f.id_forme_substance=sds.id_forme_substance
        LEFT JOIN users AS us ON us.id_user=dcc.id_user
        LEFT JOIN direction AS dir ON dir.id_direction= us.id_direction
        LEFT JOIN durete AS di ON di.id_durete=sds.id_durete
        WHERE dir.id_direction=$id_direction AND dcc.id_data_cc=$id_data_cc GROUP BY contenu.id_contenu_facture";

    }
$result = $conn->query($query);

// Vérifier si la requête a réussi
if (!$result) {
    die("La requête a échoué : " . $conn->error);
}
//renommer
$prefix_name = "Liste_contenu_facture_". $nom_direction . "_export_";
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