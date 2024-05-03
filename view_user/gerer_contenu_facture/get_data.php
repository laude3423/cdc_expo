<?php
include "../scripts/db_connect.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Utilisation de requêtes préparées pour éviter les attaques par injection SQL
    $sql = "SELECT cfa.*, g.*,s.*,ts.*,tr.*,dim.*,di.*,cate.*,cou.*,dg.* FROM contenu_facture cfa
    INNER JOIN substance_detaille_substance AS sds ON cfa.id_detaille_substance=sds.id_detaille_substance
    LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
    LEFT JOIN substance AS s ON s.id_substance= sds.id_substance
    LEFT JOIN type_substance AS ts ON ts.id_type_substance=s.id_type_substance
    LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_couleur_substance
    LEFT JOIN granulo AS g ON sds.id_granulo=g.id_granulo
    LEFT JOIN dimension_diametre AS dim ON dim.id_dimension_diametre=sds.id_dimension_diametre
    LEFT JOIN transparence AS tr ON sds.id_transparence=tr.id_transparence
    LEFT JOIN degre_couleur AS dg ON sds.id_degre_couleur=dg.id_degre_couleur
    LEFT JOIN forme_substance AS f ON f.id_forme_substance=sds.id_forme_substance
    LEFT JOIN durete AS di ON di.id_durete=sds.id_durete WHERE id_contenu_facture = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'Erreur lors de la récupération des données get : ' . mysqli_error($conn)));
    }
} else {
    echo json_encode(array('error' => 'ID non spécifié.'));
}
?>
