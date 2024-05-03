<?php
include_once('../../../scripts/db_connect.php');
// require_once('../scripts/session_admin.php');
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire id_data_cc
    $id_data_cc = isset($_POST["id_data_cc"]) ? intval($_POST["id_data_cc"]) : null;
    $id_substance = isset($_POST["id_substance"]) ? intval($_POST["id_substance"]) : null;
    $id_couleur_substance = isset($_POST["id_couleur_substance"]) ? intval($_POST["id_couleur_substance"]) : null;
    $unite_poids_facture = isset($_POST["unite_poids_facture"]) ? ($_POST["unite_poids_facture"] === "ct" ? "g" : $_POST["unite_poids_facture"]) : null;

    if($_POST["unite_poids_facture"] === 'ct'){
        $poids_facture = floatval($_POST["poids_facture"])*0.2; //poids en gramme
    }else {
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
    
        // Requête pour obtenir le sigle de direction en fonction de l'ID de direction
        $query_detail_substance = "SELECT * FROM substance_detaille_substance 
        WHERE id_substance = ? 
        AND id_couleur_substance = ? 
        AND id_granulo = ? 
        AND id_transparence = ? 
        AND id_degre_couleur = ? 
        AND id_categorie = ?";
        
        // Préparation de la requête
        $stmt_detail_substance = $conn->prepare($query_detail_substance);
        
        // Liaison des paramètres
        $stmt_detail_substance->bind_param("iiiiii", $id_substance, $id_couleur_substance, $granulo_facture, $id_transparence, $id_degre_couleur, $id_categorie);
        
        // Exécution de la requête
        $stmt_detail_substance->execute();
        
        // Récupération des résultats
        $result_detail_substance = $stmt_detail_substance->get_result();
        if ($result_detail_substance) {
            // Vérifiez s'il y a des résultats
            if ($result_detail_substance->num_rows > 0) {
                // Obtenez la première ligne de résultat
                $row_detail_substance = $result_detail_substance->fetch_assoc();
                $id_detaille_substance = $row_detail_substance["id_detaille_substance"];
                echo "$id_detaille_substance: " .$id_detaille_substance;
        
            } else {
                echo "Aucun résultat trouvé. Id substance =>" .$id_substance. 
                " id_couleur_substance: =>".$id_couleur_substance.
                " id_granulo =>" .$granulo_facture.
                " id_transparence =>" .$id_transparence.
                " id_degre_couleur =>".$id_degre_couleur.
                " id_categorie =>" .$id_categorie;
            }
        } else {
            echo "Erreur lors de l'exécution de la requête : " . $stmt_detail_substance->error;
        }
    //recherche pour lp1
    $query = "SELECT lp.*, pd.* FROM lp_info AS lp INNER JOIN produits AS pd ON lp.id_produit= pd.id_produit WHERE id_lp=$num_lp1";
    $result1 = $conn_lp1->query($query);
    $row = $result1->fetch_assoc();
    $quantite_init=$row['quantite_en_chiffre'];
    $num_lp = $row['num_LP'];

    $queryR = "SELECT id_lp1_info, quantite_lp1_actuel_lp1_suivis FROM contenu_facture WHERE id_lp1_info=$num_lp1";
    $resultR = $conn_lp1->query($queryR);
    if ($resultR->num_rows > 0) {
        $rowR = $resultR->fetch_assoc();
        $qteR_lp1=$row['quantite_lp1_actuel_lp1_suivis'];
        $quantite_init=$qteR_lp1;
    }
    $qte_actuel =floatval($quantite_init) - floatval($poids_facture);
    if($qte_actuel < 0){
        $_SESSION['toast_message2'] = 'La quantité dans le Laissez-passer n°'.$num_lp.'sont exportée!';
            header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
        exit();
    }else{
        $query = "INSERT INTO contenu_facture (id_detaille_substance, id_lp1_info, id_data_cc, quantite_lp1_initial_lp1_suivis, quantite_lp1_actuel_lp1_suivis, prix_unitaire_facture, unite_poids_facture, poids_facture) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        // Liaison des paramètres avec bind_param
        $stmt->bind_param("iiisss", $id_detaille_substance, $id_lp1_info, $id_data_cc, $quantite_init, $qte_actuel, $prix_unitaire_facture, $unite_poids_facture, $poids_facture);

        // Exécution de la requête
        if ($stmt->execute()) {
            $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
            exit();
        } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    }

    
} else {
    // Redirection vers la page d'accueil ou une autre page si le formulaire n'a pas été soumis
    header("Location: ../view/commune_region.php");
    exit();
}