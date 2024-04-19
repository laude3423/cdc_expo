<?php
include "../db_connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql="SELECT sub_detail.*, sub.*, trans.*, dure.*, forme.*, cate.*, diam.*,gra.*, degre.*
                  FROM substance_detaille_substance sub_detail
                  INNER JOIN substance sub ON sub_detail.id_substance= sub.id_substance
                  LEFT JOIN transparence trans ON sub_detail.id_transparence= trans.id_transparence
                  LEFT JOIN categorie cate ON sub_detail.id_categorie= cate.id_categorie
                  LEFT JOIN durete dure ON sub_detail.id_durete= dure.id_durete
                  LEFT JOIN forme_substance forme ON sub_detail.id_forme_substance= forme.id_forme_substance
                  LEFT JOIN dimension_diametre diam ON sub_detail.id_dimension_diametre= diam.id_dimension_diametre
                  LEFT JOIN granulo gra  ON sub_detail.id_granulo= gra.id_granulo
                  LEFT JOIN degre_couleur degre  ON sub_detail.id_degre_couleur= degre.id_degre_couleur WHERE sub.id_substance=$id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'Erreur lors de la récupération des données : ' . mysqli_error($conn)));
    }
} else {
    echo json_encode(array('error' => 'ID non spécifié.'));
}
?>