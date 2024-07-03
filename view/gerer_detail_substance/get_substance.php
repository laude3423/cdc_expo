<?php
// Database connection
include '../../scripts/db_connect.php';

$id = $_POST['id'];
$query = "SELECT sub_detail.*, sub.*, trans.*,coul.*, dure.*, forme.*, cate.*, diam.*,gra.*, degre.*
                  FROM substance_detaille_substance sub_detail
                  INNER JOIN substance sub ON sub_detail.id_substance= sub.id_substance
                  LEFT JOIN transparence trans ON sub_detail.id_transparence= trans.id_transparence
                  LEFT JOIN categorie cate ON sub_detail.id_categorie= cate.id_categorie
                  LEFT JOIN durete dure ON sub_detail.id_durete= dure.id_durete
                  LEFT JOIN couleur_substance coul ON sub_detail.id_couleur_substance= coul.id_couleur_substance
                  LEFT JOIN forme_substance forme ON sub_detail.id_forme_substance= forme.id_forme_substance
                  LEFT JOIN dimension_diametre diam ON sub_detail.id_dimension_diametre= diam.id_dimension_diametre
                  LEFT JOIN granulo gra  ON sub_detail.id_granulo= gra.id_granulo
                  LEFT JOIN degre_couleur degre  ON sub_detail.id_degre_couleur= degre.id_degre_couleur WHERE sub_detail.id_detaille_substance = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$substance = $result->fetch_assoc();

echo json_encode($substance);
?>