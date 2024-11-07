<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    require('../../histogramme/insert_logs.php');
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activite ="Modification d'un PV de contrôle";
        $expediteur = htmlspecialchars($_POST['expediteur']);
        $importateur = htmlspecialchars($_POST["importateur"]);
        $id_data = htmlspecialchars($_POST["id"]);
        $mode_emballage = htmlspecialchars($_POST["mode_emballage"]);
        $lieu_controle = htmlspecialchars($_POST["lieu_controle"]);
        $lieu_embarquement = htmlspecialchars($_POST["lieu_emb"]);
        $num_domiciliation = htmlspecialchars($_POST["numDom"]);
        $num_fiche_declaration = htmlspecialchars($_POST["declaration"]);
        $date_declaration =htmlspecialchars($_POST["date_declaration"]);
        $num_lp3e = htmlspecialchars($_POST["num_lp3"]);
        $date_lp3e = $_POST["date_lp3"];
        // $chef = $_POST["chef"];
        // $qualite = $_POST["qualite"];
        $dateDom=$_POST['date_dom'];

        $id_data = htmlspecialchars($_POST['id']);
        $dateFormat = "Y-m-d";
        $dateInsert = date($dateFormat);

        $sql="SELECT * FROM direction WHERE id_direction=$id_direction";
        $resultD = mysqli_query($conn, $sql);
        $rowD = mysqli_fetch_assoc($resultD);
        $typeDirection=$rowD['type_direction'];
        $nomDirection=$rowD['nom_direction'];
        $lieu_emission = $rowD['lieu_emission'];
        
        $uploadPath_DOM="";
        $uploadPath_DEC="";
        $uploadPath_LP3="";
        $num_pv='';
        $num_cc='';
        $query = "SELECT num_pv_controle, num_cc FROM data_cc WHERE id_data_cc=$id_data";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if(!empty($row['num_pv_controle'])){
            $num_pv = $row['num_pv_controle'];
            $num_cc = $row['num_cc'];
        }
        if($num_pv){
            //include "../generate_fichier/exemple.php";
            include "../generate_fichier/generate_insertControle.php";
        }

        //   $agent = array();
        //   if ($chef) {
        //       $agent[] = $chef;
        //   }
        //   if ($qualite) {
        //       $agent[] = $qualite;
        //   }
        
        //    // prendre l'eure du réseau
        //   date_default_timezone_set('Indian/Antananarivo');
        //   $heure_actuelle = date('H:i:s');
        //   // renommer l'heure
        //   $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
        //   // pj_DOM
        //   if (($_FILES['pj_dom']) && $_FILES['pj_dom']['error'] == UPLOAD_ERR_OK) {
        //     $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $numDom);
        //     $uploadDir = '../upload/';
        //     $fileName_DOM = "SCAN_DOM_" .$numDom_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_dom']['name'], PATHINFO_EXTENSION);
        //     $uploadPath_DOM = $uploadDir . $fileName_DOM;  
        //     move_uploaded_file($_FILES['pj_dom']['tmp_name'], $uploadPath_DOM);
        //     $sql = "UPDATE `data_cc` SET `pj_domiciliation_pv`='$uploadPath_DOM' WHERE id_data_cc='$id_data'";
        //     $result = mysqli_query($conn, $sql);

        //   }
        //   // pj declaration
        //   if (($_FILES['pj_declaration']) && $_FILES['pj_declaration']['error'] == UPLOAD_ERR_OK) {
        //         $numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $declaration);
        //         $fileName_DEC = "SCAN_DECLARATION_" .$numDec_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_declaration']['name'], PATHINFO_EXTENSION);
        //         $uploadPath_DEC = $uploadDir . $fileName_DEC;
        //         move_uploaded_file($_FILES['pj_declaration']['tmp_name'], $uploadPath_DEC);
        //         $sql = "UPDATE `data_cc` SET `pj_fiche_declaration_pv`='$uploadPath_DEC' WHERE id_data_cc='$id_data'";
        //         $result = mysqli_query($conn, $sql);
        //   }
        //    //pj lp3 e
        //   if (isset($_FILES['pj_lp3']) && $_FILES['pj_lp3']['error'] == UPLOAD_ERR_OK) {
        //       $numLP3_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp3);
        //       $fileName_LP3 = "SCAN_LPIIIE_" .$numLP3_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_lp3']['name'], PATHINFO_EXTENSION);
        //       $uploadPath_LP3 = $uploadDir . $fileName_LP3;
        //       move_uploaded_file($_FILES['pj_lp3']['tmp_name'], $uploadPath_LP3);
        //         $sql = "UPDATE `data_cc` SET `pj_lp3e_pv`='$uploadPath_LP3' WHERE id_data_cc='$id_data'";
        //         $result = mysqli_query($conn, $sql);
        //   }
        //   $id_data_suppr=array();
        //   $requete = "SELECT pv.*  FROM pv_agent_assister AS pv 
        //     LEFT JOIN agent AS ag ON pv.id_agent = ag.id_agent 
        //     WHERE (fonction_agent = 'Chef de section scellage' OR fonction_agent = 'Responsable de la qualité du Laboratoire des Mines') 
        //     AND id_data_cc = ?";
        //     $stmt = $conn->prepare($requete);
        //     if ($stmt) {
        //         // Liaison du paramètre
        //         $stmt->bind_param('i', $id_data);
        //         $stmt->execute();
        //         $result = $stmt->get_result();
        //         if ($result->num_rows > 0) {
        //             while ($row = $result->fetch_assoc()) {
        //                 $id_data_suppr[] = $row['id_agent'];
        //             }
        //         }else{
        //             echo 'vide';
        //         } 
        //     }
        //    //suppression sur la table agent
        //   if (count($id_data_suppr) > 0) {
        //       $countAgents = count($id_data_suppr);
        //       for ($i = 0; $i < $countAgents; $i++) {
        //           $query = "DELETE FROM `pv_agent_assister` WHERE id_data_cc = ? AND id_agent = ?";
        //           $stmt = $conn->prepare($query);
        //           $stmt->bind_param('ii', $id_data, $id_data_suppr[$i]);
        //           $stmt->execute();
        //           $stmt->close(); 
        //       }
        //   }else{
        //       echo 'Tsisy';
        //   }
        //    //insertion sur le table agent
        //       if(!empty($chef)){
        //           $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$id_data')";
        //           $result = mysqli_query($conn, $sql);
        //       }
        //       if(!empty($qualite)){
        //           $sql2 = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$id_data')";
        //           $result = mysqli_query($conn, $sql2);
        //       }

        //       //Préparer la requête SQL avec des placeholders (?)
        //         $sql = "UPDATE `data_cc` SET 
        //         `date_dom`=?, 
        //         `mode_emballage`=?, 
        //         `lieu_controle_pv`=?, 
        //         `lieu_embarquement_pv`=?, 
        //         `num_domiciliation`=?, 
        //         `num_fiche_declaration_pv`=?, 
        //         `date_fiche_declaration_pv`=?, 
        //         `num_lp3e_pv`=?, 
        //         `date_lp3e`=?, 
        //         `lien_pv_controle`=?, 
        //         `pj_pv_controle`=?, 
        //         `date_modification_pv_controle`=?, 
        //         `validation_directeur`='En attente', 
        //         `validation_chef`='En attente', 
        //         `validation_controle`='En attente', 
        //         `num_cc`=?, 
        //         `date_cc`=?, 
        //         `lien_cc`=?, 
        //         `pj_cc`=? 
        //         WHERE id_data_cc=?";

        // // Préparer la requête
        // $stmt = mysqli_prepare($conn, $sql);

        // // Liaison des paramètres (s pour string)
        // mysqli_stmt_bind_param($stmt, 'sssssssssssssssss', 
        //     $dateDom, 
        //     $mode_emballage, 
        //     $lieu_controle, 
        //     $lieu_embarquement, 
        //     $num_domiciliation, 
        //     $num_fiche_declaration, 
        //     $date_declaration, 
        //     $num_lp3e, 
        //     $date_lp3e, 
        //     $pathToSave, 
        //     $pathToSavePDF, 
        //     $dateInsert, 
        //     $num_cc, 
        //     $dateInsert, 
        //     $lien_cc, 
        //     $pj_cc, 
        //     $id_data
        // );

        // // Exécuter la requête
        // $result = mysqli_stmt_execute($stmt);

        //     if ($result) {
        //     insertLogs($conn, $userID, $activite);
        //             $_SESSION['toast_message'] = "Modification réussie.";
        //             header("Location:https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
        //             exit();
        //     } else {
        //         echo "Erreur d'enregistrement" . mysqli_error($conn);
        // }
        
    }