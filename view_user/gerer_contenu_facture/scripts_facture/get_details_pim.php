<?php
require '../../../scripts/db_connect.php';

if (isset($_POST['id_substance'])) {
    $id_substance = $_POST['id_substance'];
    $id_categorie = $_POST['id_categorie'];
    $id_famille = $_POST['id_famille'];

    // Initialize output variables
    $options_couleur = $options_dimension_diametre = $options_durete = $options_granulo = $options_forme_substance = $options_transparence = $options_unite = $options_direction = "<option value=''>Sélectionner...</option>";

    // Requête pour obtenir les couleurs
    $query = "SELECT DISTINCT cs.* FROM substance_detaille_substance sds 
              LEFT JOIN couleur_substance cs ON cs.id_couleur_substance = sds.id_couleur_substance
              WHERE sds.id_substance = $id_substance AND sds.id_categorie = $id_categorie AND sds.id_famille = $id_famille AND cs.id_couleur_substance IS NOT NULL";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $options_couleur .= "<option value='" . $row['id_couleur_substance'] . "'>" . $row['nom_couleur_substance'] . "</option>";
    }

    // Requête pour obtenir les dimensions
    $query_dd = "SELECT DISTINCT dd.* FROM substance_detaille_substance sds 
                 LEFT JOIN dimension_diametre dd ON dd.id_dimension_diametre = sds.id_dimension_diametre
                 WHERE sds.id_substance = $id_substance AND sds.id_categorie = $id_categorie AND sds.id_famille = $id_famille AND sds.id_dimension_diametre IS NOT NULL";
    $result_dd = $conn->query($query_dd);

    while ($row_dd = $result_dd->fetch_assoc()) {
        $options_dimension_diametre .= "<option value='" . $row_dd['id_dimension_diametre'] . "'>" . $row_dd['nom_dimension_diametre'] . "</option>";
    }

    // Requête pour obtenir les duretés
    $query_d = "SELECT DISTINCT d.* FROM substance_detaille_substance sds 
                LEFT JOIN durete d ON d.id_durete = sds.id_durete
                WHERE sds.id_substance = $id_substance AND sds.id_categorie = $id_categorie AND sds.id_famille = $id_famille AND d.id_durete IS NOT NULL";
    $result_d = $conn->query($query_d);

    while ($row_d = $result_d->fetch_assoc()) {
        $options_durete .= "<option value='" . $row_d['id_durete'] . "'>" . $row_d['nom_durete'] . "</option>";
    }

    // Requête pour obtenir les granulométries
    $query_g = "SELECT DISTINCT g.* FROM substance_detaille_substance sds 
                LEFT JOIN granulo g ON g.id_granulo = sds.id_granulo
                WHERE sds.id_substance = $id_substance AND sds.id_categorie = $id_categorie AND sds.id_famille = $id_famille AND sds.id_granulo IS NOT NULL";
    $result_g = $conn->query($query_g);

    while ($row_g = $result_g->fetch_assoc()) {
        $options_granulo .= "<option value='" . $row_g['id_granulo'] . "'>" . $row_g['nom_granulo'] . "</option>";
    }

    // Requête pour obtenir les formes de substance
    $query_fs = "SELECT DISTINCT fs.* FROM substance_detaille_substance sds 
                 LEFT JOIN forme_substance fs ON fs.id_forme_substance = sds.id_forme_substance
                 WHERE sds.id_substance = $id_substance AND sds.id_categorie = $id_categorie AND sds.id_famille = $id_famille AND sds.id_forme_substance IS NOT NULL";
    $result_fs = $conn->query($query_fs);

    while ($row_fs = $result_fs->fetch_assoc()) {
        $options_forme_substance .= "<option value='" . $row_fs['id_forme_substance'] . "'>" . $row_fs['nom_forme_substance'] . "</option>";
    }

    // Requête pour obtenir les transparences
    $query_t = "SELECT DISTINCT t.* FROM substance_detaille_substance sds 
                LEFT JOIN transparence t ON t.id_transparence = sds.id_transparence
                WHERE sds.id_substance = $id_substance AND sds.id_categorie = $id_categorie AND sds.id_famille = $id_famille AND sds.id_transparence IS NOT NULL";
    $result_t = $conn->query($query_t);

    while ($row_t = $result_t->fetch_assoc()) {
        $options_transparence .= "<option value='" . $row_t['id_transparence'] . "'>" . $row_t['nom_transparence'] . "</option>";
    }

    // Requête pour obtenir les unités de poids
    $query_unite = "SELECT * FROM substance_detaille_substance AS sds
                    WHERE id_substance = $id_substance AND sds.id_categorie = $id_categorie AND sds.id_famille = $id_famille AND unite_prix_substance IS NOT NULL
                    GROUP BY unite_prix_substance";
    $result_unite = $conn->query($query_unite);

    $ct_option_added = false;
    while ($row_unite = $result_unite->fetch_assoc()) {
        $options_unite .= "<option value='" . $row_unite['unite_prix_substance'] . "'>" . $row_unite['unite_prix_substance'] . "</option>";
        if ($row_unite['unite_prix_substance'] === 'g' && !$ct_option_added) {
            $options_unite .= "<option value='ct'>ct</option>";
            $ct_option_added = true;
        } elseif ($row_unite['unite_prix_substance'] === 'kg' && !$ct_option_added) {
            $options_unite .= "<option value='g_pour_kg'>g</option>";
            $ct_option_added = true;
        }
    }

    // Requête pour obtenir les directions
    $sql = "SELECT * FROM substance WHERE id_substance = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_substance);
    $stmt->execute();
    $resu = $stmt->get_result();
    $row = $resu->fetch_assoc();
    $nom_substance = $row['nom_substance'];
    $nom_substance = explode(' ', trim($nom_substance))[0];

    require '../../../scripts/connect_db_lp1.php';
    $query_direction = "SELECT DISTINCT dir.*
                        FROM lp_info AS lp
                        INNER JOIN produits pr ON lp.id_produit = pr.id_produit
                        INNER JOIN substance s ON pr.id_substance = s.id_substance
                        INNER JOIN directions dir ON lp.id_direction = dir.id_direction WHERE s.nom_substance LIKE ?";
    $stmt_lp1 = $conn_lp1->prepare($query_direction);
    $search_term = '%' . $conn_lp1->real_escape_string($nom_substance) . '%';
    $stmt_lp1->bind_param('s', $search_term);
    $stmt_lp1->execute();
    $result_dir = $stmt_lp1->get_result();

    while ($row_dir = $result_dir->fetch_assoc()) {
        $options_direction .= "<option value='" . $row_dir['id_direction'] . "'>" . $row_dir['nom_direction'] . "</option>";
    }

    header('Content-Type: application/json');
    echo json_encode([
        'options_couleur' => $options_couleur,
        'options_dimension_diametre' => $options_dimension_diametre,
        'options_durete' => $options_durete,
        'options_granulo' => $options_granulo,
        'options_forme_substance' => $options_forme_substance,
        'options_transparence' => $options_transparence,
        'options_unite' => $options_unite,
        'options_direction' => $options_direction,
    ]);
}
?>