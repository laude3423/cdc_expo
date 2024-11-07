<?php
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/connect_db_lp1.php');
// require_once('../scripts/session_admin.php');
// Vérifie si le formulaire a été soumis
session_start();
$en_attente="En attente";
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = array("success" => false, "message" => "");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unite_poids_facture = "";
    // Récupérer les données du formulaire
    $id_data_cc = isset($_POST["num_data"]) ? intval($_POST["num_data"]) : null;
    $id_substance = isset($_POST["id_substance"]) ? intval($_POST["id_substance"]) : null;
    $id_couleur_substance = isset($_POST["id_couleur_substance"]) ? intval($_POST["id_couleur_substance"]) : null;
    $unite_poids_facture1 = $_POST["unite_poids_facture"] ?? "";
    $unite_monetaire = $_POST['unite_monetaire'] ?? "";

    if (($unite_poids_facture1 == "ct") || ($unite_poids_facture1 == "g")) {
        $unite_poids_facture = "g";
    } elseif (($unite_poids_facture1 == "g_pour_kg") || ($unite_poids_facture1 == "kg")) {
        $unite_poids_facture = "kg";
    }

    if ($_POST["unite_poids_facture"] === 'ct') {
        $poids_facture = floatval($_POST["poids_facture"]) * 0.2; // poids en gramme
    } elseif ($_POST["unite_poids_facture"] === 'g_pour_kg') {
        $poids_facture = floatval($_POST["poids_facture"]) * 0.001;
    } else {
        $poids_facture = isset($_POST["poids_facture"]) ? htmlspecialchars($_POST["poids_facture"]) : null;
    }

    $prix_unitaire_facture = isset($_POST["prix_unitaire_facture"]) ? htmlspecialchars($_POST["prix_unitaire_facture"]) : null;
    $granulo_facture = isset($_POST["granulo_facture"]) ? intval($_POST["granulo_facture"]) : null;
    $id_degre_couleur = isset($_POST["id_degre_couleur"]) ? intval($_POST["id_degre_couleur"]) : null;
    $id_transparence = isset($_POST["id_transparence"]) ? intval($_POST["id_transparence"]) : null;
    $id_durete = isset($_POST["id_durete"]) ? intval($_POST["id_durete"]) : null;
    $id_categorie = isset($_POST["id_categorie"]) ? intval($_POST["id_categorie"]) : null;
    $id_forme_substance = isset($_POST["id_forme_substance"]) ? intval($_POST["id_forme_substance"]) : null;
    $id_dimension_diametre = isset($_POST["id_dimension_diametre"]) ? intval($_POST["id_dimension_diametre"]) : null;
    $id_lp1_info = isset($_POST["id_lp1_info"]) ? intval($_POST["id_lp1_info"]) : null;
    $numero_lp1_info = $_POST['ancien_lp'] ?? '';
    $verified_lp1 = $_POST['verified_lp'];
    $typeSubstance = isset($_POST["typeSubstance"]) ? intval($_POST["typeSubstance"]) : null;
    
    $preforme=$id_categorie;
    if($id_categorie == 3){
        $id_categorie = 1;
    }
    switch ($unite_monetaire) {
        case 'yen':
            $prix_unitaire_facture *= 0.007;
            break;
        case 'euro':
            $prix_unitaire_facture *= 1.08;
            break;
        case 'dollar':
            // Ne rien faire car le prix ne change pas
            break;
        default:
            echo 'Unité monétaire non prise en charge';
            return;
    }
    if ($id_substance && $id_data_cc) {
        // Requête pour obtenir les détails de la substance
        $query_detail_substance = "SELECT * FROM substance_detaille_substance 
            WHERE id_substance = ? 
            AND (id_couleur_substance = ? OR id_couleur_substance IS NULL)
            AND (id_granulo = ? OR id_granulo IS NULL)
            AND (id_transparence = ? OR id_transparence IS NULL)
            AND (id_degre_couleur = ? OR id_degre_couleur IS NULL)
            AND (id_categorie = ? OR id_categorie IS NULL)
            AND (id_durete = ? OR id_durete IS NULL)
            AND (id_forme_substance = ? OR id_forme_substance IS NULL)
            AND (id_dimension_diametre = ? OR id_dimension_diametre IS NULL)";
        
        $stmt_detail_substance = $conn->prepare($query_detail_substance);
        $stmt_detail_substance->bind_param("iiiiiiiii", $id_substance, $id_couleur_substance, $granulo_facture, $id_transparence, $id_degre_couleur, $id_categorie, $id_durete, $id_forme_substance, $id_dimension_diametre);
        $stmt_detail_substance->execute();
        $result_detail_substance = $stmt_detail_substance->get_result();

        if ($result_detail_substance) {
            if ($result_detail_substance->num_rows > 0) {
                $row = $result_detail_substance->fetch_assoc();
                $id_detaille_substance = $row['id_detaille_substance'];

                // Requête pour le laissez-passer
                if ($verified_lp1=='nouveau') {
                    $queryLP1 = "SELECT lp.*, pd.* FROM lp_info AS lp INNER JOIN produits AS pd ON lp.id_produit = pd.id_produit WHERE id_lp = ?";
                    $stmtLP = $conn_lp1->prepare($queryLP1);
                    $stmtLP->bind_param("i", $id_lp1_info);
                    $stmtLP->execute();
                    $resultLP = $stmtLP->get_result();

                    if ($resultLP && $resultLP->num_rows > 0) {
                        $rowLP = $resultLP->fetch_assoc();
                        $quantite_init = $rowLP['quantite_en_chiffre'];
                        $unite_produit = $rowLP['unite'];
                        $num_lp = $rowLP['num_LP'];

                        $queryR = "SELECT id_lp1_info, quantite_lp1_actuel_lp1_suivis FROM contenu_facture WHERE id_lp1_info = ? 
                            AND id_contenu_facture = (SELECT MAX(id_contenu_facture) FROM contenu_facture WHERE id_lp1_info = ?)";
                        $stmtR = $conn->prepare($queryR);
                        $stmtR->bind_param("ii", $id_lp1_info, $id_lp1_info);
                        $stmtR->execute();
                        $resultR = $stmtR->get_result();

                        if ($resultR && $resultR->num_rows > 0) {
                            $rowR = $resultR->fetch_assoc();
                            $quantite_init2 = $rowR['quantite_lp1_actuel_lp1_suivis'];

                            $qte_actuel = calculateQuantity($unite_produit, $unite_poids_facture, $quantite_init2, $poids_facture);

                            if ($qte_actuel < 0) {
                                $response['message'] = 'La quantité dans le laissez-passer n°' . $num_lp . ' est insuffisante pour la quantité demandée !';
                            } else {
                                $response = processInsertion($conn, $id_data_cc, $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $quantite_init, $qte_actuel, $id_lp1_info, $id_detaille_substance, $unite_produit, $preforme,$unite_poids_facture1);
                            }
                        } else {
                            $qte_actuel = calculateQuantity($unite_produit, $unite_poids_facture, $quantite_init, $poids_facture);

                            if ($qte_actuel < 0) {
                                $response['message'] = 'La quantité dans le laissez-passer n°' . $num_lp . ' est insuffisante pour la quantité demandée !';
                            } else {
                                $response = processInsertion($conn, $id_data_cc, $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $quantite_init, $qte_actuel, $id_lp1_info, $id_detaille_substance, $unite_produit, $preforme, $unite_poids_facture1);
                            }
                        }
                    } else {
                        $response['message'] = "Aucune valeur trouvée pour le laissez-passer.";
                    }
                } elseif ($verified_lp1=='ancien') {
                    // Code pour l'ancien laissez-passer
                    $queryLP = "SELECT * FROM ancien_lp WHERE id_ancien_lp = ?";
                    $stmtLP = $conn->prepare($queryLP);
                    $stmtLP->bind_param("i", $numero_lp1_info);
                    $stmtLP->execute();
                    $resultLP = $stmtLP->get_result();

                    if ($resultLP && $resultLP->num_rows > 0) {
                        $rowLP = $resultLP->fetch_assoc();
                        $quantite_init = $rowLP['quantite'];
                        $unite_produit = $rowLP['unite'];
                        $num_lp = $rowLP['numero_lp'];

                        $queryR = "SELECT id_ancien_lp, quantite_lp1_actuel_lp1_suivis FROM contenu_facture WHERE id_ancien_lp = ? 
                            AND id_contenu_facture = (SELECT MAX(id_contenu_facture) FROM contenu_facture WHERE id_ancien_lp = ?)";
                        $stmtR = $conn->prepare($queryR);
                        $stmtR->bind_param("ii", $numero_lp1_info, $numero_lp1_info);
                        $stmtR->execute();
                        $resultR = $stmtR->get_result();

                        if ($resultR && $resultR->num_rows > 0) {
                            $rowR = $resultR->fetch_assoc();
                            $quantite_init2 = $rowR['quantite_lp1_actuel_lp1_suivis'];

                            $qte_actuel = calculateQuantity($unite_produit, $unite_poids_facture, $quantite_init2, $poids_facture);

                            if ($qte_actuel < 0) {
                                $response['message'] = 'La quantité dans le laissez-passer n°' . $num_lp . ' est insuffisante pour la quantité demandée !';
                            } else {
                               $response = processInsertion($conn, $id_data_cc, $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $quantite_init, $qte_actuel, $numero_lp1_info, $id_detaille_substance, $unite_produit, $preforme,$unite_poids_facture1, true);
                            }
                        } else {
                            $qte_actuel = calculateQuantity($unite_produit, $unite_poids_facture, $quantite_init, $poids_facture);

                            if ($qte_actuel < 0) {
                                $response['message'] = 'La quantité dans le laissez-passer n°' . $num_lp . ' est insuffisante pour la quantité demandée !';
                            } else {
                                $response = processInsertion($conn, $id_data_cc, $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $quantite_init, $qte_actuel, $numero_lp1_info, $id_detaille_substance, $unite_produit, $preforme,$unite_poids_facture1, true);
                            }
                        }
                    } else {
                        $response['message'] = "Aucune valeur trouvée pour l'ancien laissez-passer.";
                    }
                }
            } else {
                $response['message'] = "Aucun détail trouvé pour la substance sélectionnée.";
            }
        } else {
            $response['message'] = "Erreur lors de l'exécution de la requête : " . $conn->error;
        }
    } else {
        $response['message'] = "Données manquantes pour le formulaire.";
    }
} else {
    $response['message'] = "Requête non autorisée.";
}
echo json_encode($response);

// Fonction pour calculer la quantité actuelle
function calculateQuantity($unite_produit, $unite_poids_facture, $quantite_init, $poids_facture) {
   $quantite_init = floatval($quantite_init);
    $poids_facture = floatval($poids_facture);

    // Facteurs de conversion
    $kg_to_g = 1000;
    $g_to_ct = 5;

    if ($unite_produit == $unite_poids_facture) {
        return $quantite_init - $poids_facture;
    } elseif ($unite_produit == "kg" && $unite_poids_facture == "g") {
        $result = ($quantite_init * $kg_to_g) - $poids_facture;
        return $result / $kg_to_g;
    } elseif ($unite_produit == "g" && $unite_poids_facture == "kg") {
        $result = ($quantite_init / $kg_to_g) - $poids_facture;
        return $result * $kg_to_g;
    } elseif ($unite_produit == "g" && $unite_poids_facture == "ct") {
        $result = ($quantite_init * $g_to_ct) - $poids_facture;
        return $result / $g_to_ct;
    } else {
        // Si les unités ne correspondent à aucun des cas prévus
        return 0;
    }
}

// Fonction pour traiter l'insertion des données
function processInsertion($conn, $id_data_cc, $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $quantite_init, $qte_actuel, $id_lp1_info, $id_detaille_substance, $unite_produit, $preforme,$unite_poids_facture1, $isOldLP = false) {
    $query = $isOldLP ? "INSERT INTO contenu_facture (unite_substance_lp1, id_ancien_lp, id_data_cc, poids_facture, unite_poids_facture, prix_unitaire_facture, quantite_lp1_initial_lp1_suivis, quantite_lp1_actuel_lp1_suivis, preforme,unite, id_detaille_substance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                       : "INSERT INTO contenu_facture (unite_substance_lp1, id_lp1_info, id_data_cc, poids_facture, unite_poids_facture, prix_unitaire_facture, quantite_lp1_initial_lp1_suivis, quantite_lp1_actuel_lp1_suivis, preforme,unite, id_detaille_substance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($isOldLP) {
        $stmt->bind_param("siidsddddsi",$unite_produit, $id_lp1_info, $id_data_cc, $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $quantite_init, $qte_actuel, $preforme,$unite_poids_facture1, $id_detaille_substance);
    } else {
        $stmt->bind_param("siidsddddsi",$unite_produit, $id_lp1_info, $id_data_cc, $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $quantite_init, $qte_actuel, $preforme,$unite_poids_facture1, $id_detaille_substance);
    }
    if (!$stmt->execute()) {
        $response['message'] = "Erreur lors de l'exécution de la requête : " . $stmt->error;
        echo json_encode($response);
        exit;
    }else{
        $en_attente="En attente";
        $response = array("success" => true, "message" => "Données insérées avec succès.");
        $stmt3 = $conn->prepare("UPDATE data_cc SET `validation_facture`=? WHERE id_data_cc=?");
        $stmt3->bind_param("si", $en_attente,$id_data_cc);
        $stmt3->execute();
    }
    return $response;
    
}

?>