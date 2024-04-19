<?php
// Inclure la classe PHPSpreadsheet
require '../../vendor/autoload.php';
require('../../scripts/session.php');
require_once('../../scripts/db_connect.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$query_direction = "SELECT nom_direction FROM direction WHERE id_direction = $id_direction";
$result = $conn->query($query_direction);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $nom_direction = $row["nom_direction"];
}
// Exécution de la requête SQL pour récupérer les données
$query='';
if($groupeID===2){
    $query = "SELECT dcc.num_cc, dcc.date_cc, dcc.num_facture, dcc.date_insertion_facture, dcc.date_modification_facture,
dcc.num_pv_scellage,dcc.date_creation_pv_scellage, dcc.date_modification_pv_scellage, dcc.lieu_scellage_pv,dcc.num_pv_controle,
dcc.date_creation_pv_controle, dcc.date_modification_pv_controle, dcc.lieu_controle_pv, dcc.lieu_embarquement_pv,
dcc.num_lp3e_pv, dcc.date_lp3e,dcc.num_domiciliation, dcc.num_fiche_declaration_pv, dcc.date_fiche_declaration_pv,
dcc.nombre_colis, dcc.type_colis, dcc.mode_emballage,se.nom_societe_expediteur,si.nom_societe_importateur,
GROUP_CONCAT(cou.nom_couleur_substance SEPARATOR ', ') AS noms_couleurs,GROUP_CONCAT(cate.nom_categorie SEPARATOR ', ') AS nom_categorie, sub.nom_substance, typeSub.nom_type_substance, di.nom_direction, SUM(contenu.poids_facture) AS somme_poids
        FROM contenu_facture AS contenu
        INNER JOIN data_cc dcc ON dcc.id_data_cc=contenu.id_data_cc
        LEFT JOIN substance_detaille_substance AS sds ON sds.id_detaille_substance= contenu.id_detaille_substance
        LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
        LEFT JOIN substance AS sub ON sub.id_substance= sds.id_substance
        LEFT JOIN type_substance AS typeSub ON typeSub.id_type_substance=sub.id_type_substance
        LEFT JOIN users AS us ON us.id_user=dcc.id_user
        LEFT JOIN direction AS di ON di.id_direction= us.id_direction
        LEFT JOIN societe_expediteur AS se ON se.id_societe_expediteur=dcc.id_societe_expediteur
        LEFT JOIN societe_importateur AS si ON si.id_societe_importateur=dcc.id_societe_importateur
        LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_detaille_substance
         GROUP BY dcc.id_data_cc";
}else{
    $query = "SELECT dcc.num_cc, dcc.date_cc, dcc.num_facture, dcc.date_insertion_facture, dcc.date_modification_facture,
    dcc.num_pv_scellage,dcc.date_creation_pv_scellage, dcc.date_modification_pv_scellage, dcc.lieu_scellage_pv,dcc.num_pv_controle,
    dcc.date_creation_pv_controle, dcc.date_modification_pv_controle, dcc.lieu_controle_pv, dcc.lieu_embarquement_pv,
    dcc.num_lp3e_pv, dcc.date_lp3e,dcc.num_domiciliation, dcc.num_fiche_declaration_pv, dcc.date_fiche_declaration_pv,
    dcc.nombre_colis, dcc.type_colis, dcc.mode_emballage,se.nom_societe_expediteur,si.nom_societe_importateur,
    GROUP_CONCAT(cou.nom_couleur_substance SEPARATOR ', ') AS noms_couleurs,GROUP_CONCAT(cate.nom_categorie SEPARATOR ', ') AS nom_categorie, sub.nom_substance, typeSub.nom_type_substance, di.nom_direction, SUM(contenu.poids_facture) AS somme_poids
        FROM contenu_facture AS contenu
        INNER JOIN data_cc dcc ON dcc.id_data_cc=contenu.id_data_cc
        LEFT JOIN substance_detaille_substance AS sds ON sds.id_detaille_substance= contenu.id_detaille_substance
        LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
        LEFT JOIN substance AS sub ON sub.id_substance= sds.id_substance
        LEFT JOIN type_substance AS typeSub ON typeSub.id_type_substance=sub.id_type_substance
        LEFT JOIN users AS us ON us.id_user=dcc.id_user
        LEFT JOIN direction AS di ON di.id_direction= us.id_direction
        LEFT JOIN societe_expediteur AS se ON se.id_societe_expediteur=dcc.id_societe_expediteur
        LEFT JOIN societe_importateur AS si ON si.id_societe_importateur=dcc.id_societe_importateur
        LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_detaille_substance
        WHERE di.id_direction=$id_direction GROUP BY dcc.id_data_cc";
}


$result = $conn->query($query);

// Vérifier si la requête a réussi
if (!$result) {
    die("La requête a échoué : " . $conn->error);
}
//renommer
$prefix_name = "Liste_cdc_". $nom_direction . "_export_";
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