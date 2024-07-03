<?php
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/connect_db_lp1.php');
// require_once('../scripts/session_admin.php');
// Vérifie si le formulaire a été soumis
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unite_poids_facture="";
    // Récupérer les données du formulaire id_data_cc
    $id_data_cc = isset($_POST["num_data"]) ? intval($_POST["num_data"]) : null;
    $id_contenu_facture= isset($_POST["id_contenu"]) ? intval($_POST["id_contenu"]) : null;
    $id_substance = isset($_POST["id_substance"]) ? intval($_POST["id_substance"]) : null;
    $id_couleur_substance = isset($_POST["id_couleur_substance"]) ? intval($_POST["id_couleur_substance"]) : null;
    $unite_poids_facture1 = $_POST["unite_poids_facture"] ?? "";


    if(($unite_poids_facture1=="ct")||($unite_poids_facture1=="g")){
        $unite_poids_facture="g";
    }else if(($unite_poids_facture1=="g_pour_kg")||($unite_poids_facture1=="kg")){
        $unite_poids_facture="kg";
    }
    if($_POST["unite_poids_facture"] === 'ct'){
        $poids_facture = floatval($_POST["poids_facture"])*0.2; //poids en gramme
    }else if($_POST["unite_poids_facture"] === 'g_pour_kg'){
       $poids_facture = floatval($_POST["poids_facture"])*0.001;
    }else{
         $poids_facture = isset($_POST["poids_facture"]) ? htmlspecialchars($_POST["poids_facture"]) : null;
    }
    $prix_unitaire_facture = isset($_POST["prix_unitaire_facture2"]) ? htmlspecialchars($_POST["prix_unitaire_facture2"]) : null;
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
        AND (id_couleur_substance = ? OR id_couleur_substance IS NULL)
        AND (id_granulo = ? OR id_granulo IS NULL)
        AND (id_transparence = ? OR id_transparence IS NULL)
        AND (id_degre_couleur = ? OR id_degre_couleur IS NULL)
        AND (id_categorie = ? OR id_categorie IS NULL)
        AND (id_durete= ? OR id_durete IS NULL)
        AND (id_forme_substance = ? OR id_forme_substance IS NULL)
        AND (id_dimension_diametre = ? OR id_dimension_diametre IS NULL)";
        $stmt_detail_substance = $conn->prepare($query_detail_substance);
        $stmt_detail_substance->bind_param("iiiiiiiii", $id_substance, $id_couleur_substance, $granulo_facture, $id_transparence, $id_degre_couleur, $id_categorie, $id_durete, $id_forme_substance, $id_dimension_diametre);
        
        $stmt_detail_substance->execute();
        $result_detail_substance = $stmt_detail_substance->get_result();
        if ($result_detail_substance) {
            // Vérifiez s'il y a des résultats
            if ($result_detail_substance->num_rows > 0) {
                $row = $result_detail_substance->fetch_assoc();
                $id_detaille_substance = $row['id_detaille_substance'];
                // Obtenez la première ligne de résultat
                //recherche pour lp1
                $queryLP = "SELECT lp.*, pd.* FROM lp_info AS lp INNER JOIN produits AS pd ON lp.id_produit= pd.id_produit WHERE id_lp=$id_lp1_info";
                $resultLP = $conn_lp1->query($queryLP);
                $rowLP = $resultLP->fetch_assoc();
                $quantite_init=$rowLP['quantite_en_chiffre'];
                $unite_produit = $rowLP['unite'];
                $num_lp = $rowLP['num_LP'];

                $queryR = "SELECT id_lp1_info,poids_facture, quantite_lp1_actuel_lp1_suivis FROM contenu_facture WHERE id_contenu_facture=$id_contenu_facture";
                $resultR = $conn->query($queryR);$conn->query($queryR);
                if ($resultR->num_rows > 0) {
                    $rowR = $resultR->fetch_assoc();
                    $id_lp1_info_base=$rowR['id_lp1_info'];
                    $poids_facture_base=$rowR['poids_facture'];
                    $qteR_lp1=$rowR['quantite_lp1_actuel_lp1_suivis'];
                    
                    if ($id_lp1_info == $id_lp1_info_base) {
                        $differencePoids=0;
                        if ($poids_facture_base > $poids_facture) {
                            if (strtolower($unite_produit) == strtolower($unite_poids_facture)) {
                                $differencePoids = floatval($poids_facture_base) - floatval($poids_facture);
                            } elseif (strtolower($unite_produit) == "kg" && strtolower($unite_poids_facture) == "g") {
                                $differencePoids = floatval($poids_facture_base) - floatval($poids_facture);
                                $differencePoids /=1000;
                            } elseif (strtolower($unite_produit) == "g" && strtolower($unite_poids_facture) == "kg") {
                                $differencePoids = floatval($poids_facture_base)  - floatval($poids_facture);
                                $differencePoids *=1000; 
                            }
                            $qteR_lp1 +=$differencePoids;
                            
                            $stmt = $conn->prepare("UPDATE contenu_facture SET quantite_lp1_actuel_lp1_suivis = ? WHERE id_contenu_facture = ?");
                            $stmt->bind_param("si", $qteR_lp1, $id_contenu_facture);
                            $stmt->execute();
                        } elseif ($poids_facture_base < $poids_facture) {
                            if (strtolower($unite_produit) == strtolower($unite_poids_facture)) {
                                $differencePoids = floatval($poids_facture_base) - floatval($poids_facture);
                            } elseif (strtolower($unite_produit) == "kg" && strtolower($unite_poids_facture) == "g") {
                                    $differencePoids =  floatval($poids_facture) - floatval($poids_facture_base);
                                    $differencePoids /=1000; 
                            } elseif (strtolower($unite_produit) == "g" && strtolower($unite_poids_facture) == "kg") {
                                    $differencePoids =  floatval($poids_facture) - floatval($poids_facture_base);
                                    $differencePoids *=1000; 
                            }
                            
                            if($qteR_lp1 < 0){
                               $_SESSION['toast_message2'] = 'La quantité dans le laissez-passer n°'.$num_lp.' est insuffisante pour la quantité demandée !';
                                    header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
                                exit();
                            }else{
                                $stmt = $conn->prepare("UPDATE contenu_facture SET quantite_lp1_actuel_lp1_suivis = ? WHERE id_contenu_facture = ?");
                                $stmt->bind_param("si", $qteR_lp1, $id_contenu_facture);
                                $stmt->execute();
                                
                                $stmt2 = $conn->prepare("UPDATE `contenu_facture` SET `poids_facture` = ?, `unite_poids_facture` = ?, `prix_unitaire_facture` = ?, 
                                                            `id_lp1_info` = ?, `id_detaille_substance` = ? WHERE `id_contenu_facture` = ?");
                                $stmt2->bind_param("ssdiii", $poids_facture, $unite_poids_facture, $prix_unitaire_facture, $id_lp1_info, $id_detaille_substance, $id_contenu_facture);
                                if ($stmt2->execute()) {
                                                $_SESSION['toast_message'] = "Modification réussie.";
                                                header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
                                                exit();
                                } else {
                                                echo "Erreur d'enregistrement" . mysqli_error($conn);
                                }
                            }
                        }else{
                            //mitovy ny poids_facture sy base
                            $stmt2 = $conn->prepare("UPDATE `contenu_facture` SET  `unite_poids_facture` = ?, `prix_unitaire_facture` = ?, 
                                                            `id_lp1_info` = ?, `id_detaille_substance` = ? WHERE `id_contenu_facture` = ?");
                            $stmt2->bind_param("sdiii", $unite_poids_facture, $prix_unitaire_facture, $id_lp1_info, $id_detaille_substance, $id_contenu_facture);
                            if ($stmt2->execute()) {
                                $_SESSION['toast_message'] = "Modification réussie.";
                                header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
                                exit();
                            } else {
                                    echo "Erreur d'enregistrement" . mysqli_error($conn);
                            }
                        }
                    }else{
                        $queryR = "SELECT id_lp1_info, quantite_lp1_actuel_lp1_suivis FROM contenu_facture WHERE id_lp1_info=$id_lp1_info 
                        AND id_contenu_facture = (SELECT MAX(id_contenu_facture) 
                                       FROM contenu_facture 
                                       WHERE id_lp1_info = $id_lp1_info)";
                        $resultR = $conn->query($queryR);
                        if ($resultR->num_rows > 0) {
                            $rowR = $resultR->fetch_assoc();
                            $quantite_init2=$rowR['quantite_lp1_actuel_lp1_suivis'];
                            if (strtolower($unite_produit) == strtolower($unite_poids_facture)) {
                                $differencePoids = floatval($quantite_init2) - floatval($poids_facture);
                            } elseif (strtolower($unite_produit) == "kg" && strtolower($unite_poids_facture) == "g") {
                                    $differencePoids = floatval($quantite_init2)* 1000 - floatval($poids_facture);
                                    $differencePoids /= 1000;
                            } elseif (strtolower($unite_produit) == "g" && strtolower($unite_poids_facture) == "kg") {
                                    $differencePoids = floatval($quantite_init2)/ 1000  - floatval($poids_facture);
                                    $differencePoids *=1000;
                            }
                            if($differencePoids < 0){
                                $_SESSION['toast_message2'] = 'La quantité dans le Laissez-passer n°'.$num_lp.'sont exportée!';
                                    header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
                                exit();
                            }else{
                                $sql="UPDATE `contenu_facture` SET `poids_facture`='$poids_facture',`unite_poids_facture`='$unite_poids_facture',`prix_unitaire_facture`='$prix_unitaire_facture',
                                `quantite_lp1_initial_lp1_suivis`='$quantite_init',`unite_substance_lp1`='$unite_produit',
                                `quantite_lp1_actuel_lp1_suivis`='$differencePoids',`id_lp1_info`='$id_lp1_info',`id_detaille_substance`='$id_detaille_substance',`id_data_cc`='$id_data_cc' WHERE id_contenu_facture='$id_contenu_facture'";
                                $result = mysqli_query($conn, $sql);

                                if ($result) {
                                        $_SESSION['toast_message'] = "Modification réussie.";
                                            header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
                                            exit();
                                    } else {
                                        echo "Erreur d'enregistrement" . mysqli_error($conn);
                                    }
                                    
                            }
                        }else{
                            //tsy mbola anaty base ilay LP1
                            if (strtolower($unite_produit) == strtolower($unite_poids_facture)) {
                                    $differencePoids = floatval($quantite_init) - floatval($poids_facture);
                            } elseif (strtolower($unite_produit) == "kg" && strtolower($unite_poids_facture) == "g") {
                                    $differencePoids = floatval($quantite_init)* 1000 - floatval($poids_facture);
                                    $differencePoids /= 1000;
                            } elseif (strtolower($unite_produit) == "g" && strtolower($unite_poids_facture) == "kg") {
                                    $differencePoids = floatval($quantite_init)/ 1000  - floatval($poids_facture);
                                    $differencePoids *=1000;
                            }
                        
                            if($differencePoids < 0){
                                    $_SESSION['toast_message2'] = 'La quantité dans le Laissez-passer n°'.$num_lp.'sont exportée!';
                                        header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
                                    exit();
                            }else{
                                // // $differencePoid= $qteR_lp1 + $poids_facture_base;
                                // // $stmt = $conn->prepare("UPDATE contenu_facture SET quantite_lp1_actuel_lp1_suivis = ? WHERE id_lp1_info = ?");
                                // // $stmt->bind_param("si", $differencePoid, $id_lp1_info_base);
                                // // $stmt->execute();

                                $sql="UPDATE `contenu_facture` SET `poids_facture`='$poids_facture',`unite_poids_facture`='$unite_poids_facture',`prix_unitaire_facture`='$prix_unitaire_facture',
                                `quantite_lp1_initial_lp1_suivis`='$quantite_init',`unite_substance_lp1`='$unite_produit',
                                `quantite_lp1_actuel_lp1_suivis`='$differencePoids',`id_lp1_info`='$id_lp1_info',`id_detaille_substance`='$id_detaille_substance',`id_data_cc`='$id_data_cc' WHERE id_contenu_facture='$id_contenu_facture'";
                                $result = mysqli_query($conn, $sql);

                                if ($result) {
                                        $_SESSION['toast_message'] = "Modification réussie.";
                                            header("Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_contenu_facture.php?id=" . $id_data_cc);
                                            exit();
                                    } else {
                                        echo "Erreur d'enregistrement" . mysqli_error($conn);
                                    }
                                    
                            }
                        }
                        
                    }
                 }
                
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
    

    
} else {
    // Redirection vers la page d'accueil ou une autre page si le formulaire n'a pas été soumis
    header("Location: ../view/commune_region.php");
    exit();
}