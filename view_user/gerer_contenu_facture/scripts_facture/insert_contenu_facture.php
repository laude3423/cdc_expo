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
        
   
    // Valider les données (ajoutez d'autres validations si nécessaire)

    // Insertion des données dans la base de données
    $query = "INSERT INTO contenu_facture (id_detaille_substance, id_lp1_info, id_data_cc, prix_unitaire_facture, unite_poids_facture, poids_facture) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Liaison des paramètres avec bind_param
    $stmt->bind_param("iiisss", $id_detaille_substance, $id_lp1_info, $id_data_cc, $prix_unitaire_facture, $unite_poids_facture, $poids_facture);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Utilisation de la fonction urlencode pour éviter les problèmes avec les espaces dans l'URL
        header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
        exit();
    } else {
        // En cas d'échec, afficher une erreur ou rediriger vers une page d'erreur
        header("Location: ../pages/error.php");
        exit();
    }
} else {
    // Redirection vers la page d'accueil ou une autre page si le formulaire n'a pas été soumis
    header("Location: ../view/commune_region.php");
    exit();
}

