<?php
// get_districts.php
// session_start();
// Connexion à la base de données
require '../../../scripts/db_connect.php';
if (isset($_POST['id_substance'])) {
    $id_substance = $_POST['id_substance'];
    $id_categorie = $_POST['id_categorie'];
    error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();
    // Récupérer les districts en fonction de la région sélectionnée
    // $query = "SELECT * FROM substance_detaille_substance sds 
    // LEFT JOIN couleur_substance cs ON cs.id_couleur_substance = sds.id_couleur_substance
    // LEFT JOIN degre_couleur  dc ON dc.id_degre_couleur = sds.id_degre_couleur
    // LEFT JOIN dimension_diametre dd ON dd.id_dimension_diametre = sds.id_dimension_diametre
    // LEFT JOIN durete d ON d.id_durete = sds.id_durete
    // LEFT JOIN granulo g ON g.id_granulo = sds.id_granulo
    // LEFT JOIN forme_substance fs ON fs.id_forme_substance = sds.id_forme_substance
    // LEFT JOIN categorie  c ON c.id_categorie = sds.id_categorie
    // WHERE id_substance = $id_substance";
    $query = "SELECT DISTINCT cs.* FROM substance_detaille_substance sds 
    LEFT JOIN couleur_substance cs ON cs.id_couleur_substance = sds.id_couleur_substance
    WHERE sds.id_substance = $id_substance AND sds.id_categorie =$id_categorie AND cs.id_couleur_substance IS NOT NULL";
    
    $result = $conn->query($query);
    
    $options_couleur = "<option value=''>Sélectionner...</option>";
    while ($row = $result->fetch_assoc()) {
        $options_couleur .= "<option value='" . $row['id_couleur_substance'] . "'>" . $row['nom_couleur_substance'] . "</option>";
    }

    $query_degre_couleur = "SELECT DISTINCT dc.* FROM substance_detaille_substance sds 
    LEFT JOIN degre_couleur dc ON dc.id_degre_couleur = sds.id_degre_couleur
    WHERE sds.id_substance = $id_substance AND sds.id_categorie =$id_categorie AND sds.id_degre_couleur IS NOT NULL";
    
    $result_degre_couleur = $conn->query($query_degre_couleur);
    
    $options_degre_couleur = "<option value=''>Sélectionner...</option>";
    while ($row_degre_couleur = $result_degre_couleur->fetch_assoc()) {
        $options_degre_couleur .= "<option value='" . $row_degre_couleur['id_degre_couleur'] . "'>" . $row_degre_couleur['nom_degre_couleur'] . "</option>";
    }

    $query_dd = "SELECT DISTINCT dd.* FROM substance_detaille_substance sds 
    LEFT JOIN dimension_diametre dd ON dd.id_dimension_diametre = sds.id_dimension_diametre
    WHERE sds.id_substance = $id_substance AND sds.id_categorie =$id_categorie AND sds.id_dimension_diametre IS NOT NULL";

    $result_dd = $conn->query($query_dd);

    $options_dimension_diametre = "<option value=''>Sélectionner...</option>";

    if ($result_dd->num_rows > 0) {
        while ($row_dd = $result_dd->fetch_assoc()) {
            if (isset($row_dd["id_dimension_diametre"])) {
                $options_dimension_diametre .= "<option value='" . $row_dd['id_dimension_diametre'] . "'>" . $row_dd['nom_dimension_diametre'] . "</option>";
            }
        }
    }
    
    $query_d = "SELECT DISTINCT d.* FROM substance_detaille_substance sds 
    LEFT JOIN durete d ON d.id_durete = sds.id_durete
    WHERE sds.id_substance = $id_substance AND sds.id_categorie =$id_categorie AND d.id_durete IS NOT NULL";
    
    $result_d = $conn->query($query_d);

    $options_durete = "<option value=''>Sélectionner...</option>";
    while ($row_d = $result_d->fetch_assoc()) {
        if (isset($row_d["id_durete"])) {
            $options_durete .= "<option value='" . $row_d['id_durete'] . "'>" . $row_d['nom_durete'] . "</option>";
        }
        
    }

    $query_g = "SELECT DISTINCT g.*, sds.id_substance FROM substance_detaille_substance sds 
    LEFT JOIN granulo g ON g.id_granulo = sds.id_granulo
    WHERE sds.id_substance = $id_substance AND sds.id_categorie =$id_categorie AND sds.id_granulo IS NOT NULL";
    
    $result_g = $conn->query($query_g);

    $options_granulo = "<option value=''>Sélectionner...</option>";
    if ($result_g ->num_rows > 0) {
        while ($row_g = $result_g->fetch_assoc()) {
            if (isset($row_g["id_granulo"])) {
                $options_granulo .= "<option value='" . $row_g['id_granulo'] . "'>" . $row_g['nom_granulo'] . "</option>";
            } 
            
        }
    }

    $query_fs = "SELECT DISTINCT fs.* FROM substance_detaille_substance sds 
    LEFT JOIN forme_substance fs ON fs.id_forme_substance = sds.id_forme_substance
    WHERE sds.id_substance = $id_substance AND sds.id_categorie =$id_categorie AND sds.id_forme_substance IS NOT NULL";
    
    $result_fs = $conn->query($query_fs);

    $options_forme_substance = "<option value=''>Sélectionner...</option>";
        while ($row_fs = $result_fs->fetch_assoc()) {
            if (isset($row_fs["id_forme_substance"])) {
                $options_forme_substance .= "<option value='" . $row_fs['id_forme_substance'] . "'>" . $row_fs['nom_forme_substance'] . "</option>";
            }else {
                $options_forme_substance .= "<option value=''>NULL</option>";
            }
            
        }

    // $query_c = "SELECT DISTINCT c.* FROM substance_detaille_substance sds 
    // LEFT JOIN categorie  c ON c.id_categorie = sds.id_categorie
    // WHERE sds.id_substance = $id_substance AND sds.id_categorie IS NOT NULL";
    
    // $result_c = $conn->query($query_c);

    // $options_categorie = "<option value=''>Sélectionner...</option>";
    // while ($row_c = $result_c->fetch_assoc()) {
    //     if (isset($row_c["id_categorie"])) {
    //         $options_categorie .= "<option value='" . $row_c['id_categorie'] . "'>" . $row_c['nom_categorie'] . "</option>";
    //     }
        
    // }

    $query_t = "SELECT DISTINCT t.* FROM substance_detaille_substance sds 
    LEFT JOIN transparence t ON t.id_transparence = sds.id_transparence
    WHERE sds.id_substance = $id_substance AND sds.id_categorie =$id_categorie AND sds.id_transparence IS NOT NULL";
    
    $result_t = $conn->query($query_t);
    
    $options_transparence = "<option value=''>Sélectionner...</option>";
    while ($row_t = $result_t->fetch_assoc()) {
        if (isset($row_t["id_transparence"])) {
            $options_transparence .= "<option value='" . $row_t['id_transparence'] . "'>" . $row_t['nom_transparence'] . "</option>";
        }
    }

    $query_unite = "SELECT * FROM substance_detaille_substance AS sds
    WHERE id_substance = $id_substance AND  sds.id_categorie =$id_categorie AND unite_prix_substance IS NOT NULL
    GROUP BY unite_prix_substance";
    
    $result_unite = $conn->query($query_unite);
    $ct_option_added = false;
    $options_unite = "<option value=''>Sélectionner...</option>";
    while ($row_unite = $result_unite->fetch_assoc()) {
        if (isset($row_unite["id_detaille_substance"])) {
            $options_unite .= "<option value='" . $row_unite['unite_prix_substance'] . "'>" . $row_unite['unite_prix_substance'] . "</option>";
            // Vérifier si $row_unite["id_transparence"] est égal à 'g'
            if ($row_unite["unite_prix_substance"] === 'g' && !$ct_option_added) {
                // Ajouter l'option 'CT' si elle n'a pas encore été ajoutée
                $options_unite .= "<option value='ct'>ct</option>";
                $ct_option_added = true; // Mettre à true pour indiquer que l'option 'CT' a été ajoutée
            }else if($row_unite["unite_prix_substance"] === 'kg' && !$ct_option_added){
                $options_unite .= "<option value='g_pour_kg'>g</option>";
                $ct_option_added = true;
            }
        }
    }
// Requête pour obtenir les options
$sql = "SELECT * FROM substance WHERE id_substance = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_substance);
$stmt->execute();
$resu = $stmt->get_result();
$row = $resu->fetch_assoc();
$nom_substance = $row['nom_substance'];
$nom_substance = explode(' ', trim($nom_substance))[0];

// Connexion à la deuxième base de données
require '../../../scripts/connect_db_lp1.php';

$query = "SELECT lp.*, s.*, pr.* 
          FROM lp_info AS lp
          INNER JOIN produits pr ON lp.id_produit = pr.id_produit
          INNER JOIN substance s ON pr.id_substance = s.id_substance
          WHERE validation_admin = 1 AND s.nom_substance LIKE ?";
$stmt_lp1 = $conn_lp1->prepare($query);
$search_term = '%' . $conn_lp1->real_escape_string($nom_substance) . '%';
$stmt_lp1->bind_param('s', $search_term);
$stmt_lp1->execute();
$result = $stmt_lp1->get_result();

// Génération des options
$options_lp1 = "<option value=''>Sélectionner...</option>";
while ($row = $result->fetch_assoc()) {
    if (isset($row["id_lp"])) {
        $options_lp1 .= "<option value='" . $row['id_lp'] . "'>" . $row['num_LP'] . " (" . $row['nom_substance'] . ") (" . $row['unite'] . ")</option>";
    }
}
$output = ob_get_clean();

if (!empty($output)) {
    echo "Unexpected output detected: $output";
}
// Réponse JSON
header('Content-Type: application/json');
echo json_encode([
    'options_couleur' => $options_couleur ?? '',
    'options_degre_couleur' => $options_degre_couleur ?? '',
    'options_dimension_diametre' => $options_dimension_diametre ?? '',
    'options_durete' => $options_durete ?? '',
    'options_granulo' => $options_granulo ?? '',
    'options_forme_substance' => $options_forme_substance ?? '',
    'options_categorie' => $options_categorie ?? '',
    'options_transparence' => $options_transparence ?? '',
    'options_unite' => $options_unite ?? '',
    'options_lp1' => $options_lp1
]);
}
?>