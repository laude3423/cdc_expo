<?php
    require_once('../../scripts/db_connect.php');
    session_start();
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $expediteur = htmlspecialchars($_POST['expediteur_edit']);
        $destination = htmlspecialchars($_POST["destination_edit"]);
        $facture = htmlspecialchars($_POST["id_edit"]);
        $nombre = htmlspecialchars($_POST["nombre_edit"]);
        $lieu_sce = htmlspecialchars($_POST["lieu_sce_edit"]);
        $lieu_emb = htmlspecialchars($_POST["lieu_emb_edit"]);
        $numDom = htmlspecialchars($_POST["numDom_edit"]);
        $declaration = htmlspecialchars($_POST["declaration_edit"]);
        $date_declaration =$_POST["date_declaration_edit"];
        $num_lp3 = htmlspecialchars($_POST["num_lp3_edit"]);
        $date_lp3 = $_POST["date_lp3_edit"];
        $chef = $_POST["chef_edit"];
        $police = $_POST["police_edit"];
        $douane = $_POST["douane_edit"];
        $qualite = $_POST["qualite_edit"];
        $agent_scellage= $_POST["agent_scellage_edit"];
        $type_colis = htmlspecialchars($_POST["type_colis_edit"]);

        $id_data = $_POST['id_edit'];
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);

        $uploadPath_DOM="";
        $uploadPath_DEC="";
        $uploadPath_LP3="";
        $num_pv='';
        $query = "SELECT num_pv_scellage FROM data_cc WHERE id_data_cc=$id_data";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if(!empty($row['num_pv_scellage'])){
            $num_pv = $row['num_pv_scellage'];
        }
        if($num_pv){
            include "../generate_fichier/generate_insert_scellage.php";
        }
         //prendre l'eure du réseau
        date_default_timezone_set('Indian/Antananarivo');
        $heure_actuelle = date('H:i:s');
        //renommer l'heure
        $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
        //pj_DOM
        if (!empty($_FILES['pj_dom_edit']['name'])) {
          $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $numDom);
        $uploadDir = '../upload/';
         $fileName_DOM = "SCAN_DOM_" .$numDom_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_dom_edit']['name'], PATHINFO_EXTENSION);
         $uploadPath_DOM = $uploadDir . $fileName_DOM;  
         move_uploaded_file($_FILES['pj_dom_edit']['tmp_name'], $uploadPath_DOM);
        }
        //pj declaration
        if (!empty($_FILES['pj_declaration_edit']['name'])) {
            $numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $declaration);
         $fileName_DEC = "SCAN_DECLARATION_" .$numDec_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_declaration_edit']['name'], PATHINFO_EXTENSION);
        $uploadPath_DEC = $uploadDir . $fileName_DEC;
        move_uploaded_file($_FILES['pj_declaration_edit']['tmp_name'], $uploadPath_DEC);
        }
        //pj lp3 e
        if (!empty($_FILES['pj_lp3_edit']['name'])) {
            $numLP3_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp3);
            $fileName_LP3 = "SCAN_LPIIIE_" .$numLP3_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_lp3_edit']['name'], PATHINFO_EXTENSION);
            $uploadPath_LP3 = $uploadDir . $fileName_LP3;

            move_uploaded_file($_FILES['pj_lp3_edit']['tmp_name'], $uploadPath_LP3);
        }
        
    
        
        //suppression sur la table agent
        $query = "DELETE FROM `pv_agent_assister` WHERE id_data_cc = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $facture);
        $stmt->execute();
        //insertion sur le table agent
            if(!empty($chef)){
                $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$facture')";
                $result = mysqli_query($conn, $sql);
            }
            if(!empty($qualite)){
                $sql2 = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$facture')";
                $result = mysqli_query($conn, $sql2);
            }
            if(count($agent_scellage) > 0){
                for ($i = 0; $i < count($agent_scellage); $i++) {
                    $query = "INSERT INTO  `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES (?, ?)";
                         $stmt = $conn->prepare($query);
                        $stmt->bind_param("ii", $agent_scellage[$i], $facture);
                        $stmt->execute();
                }
                
            }
            if(!empty($douane)){
                $sql3 = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$douane','$facture')";
                $result = mysqli_query($conn, $sql3);
            }
            if(!empty($police)){
                $sql3 = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$police','$facture')";
                $result = mysqli_query($conn, $sql3);
            }
            if(!empty($uploadPath_DEC)){
                $sql = "UPDATE `data_cc` SET `pj_fiche_declaration_pv`='$uploadPath_DEC' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);
            }
            if(!empty($uploadPath_DOM)){
                $sql = "UPDATE `data_cc` SET `pj_domiciliation_pv`='$uploadPath_DOM' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);
            }
            if(!empty($uploadPath_LP3)){
                $sql = "UPDATE `data_cc` SET `pj_lp3e_pv`='$uploadPath_LP3' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);
            }

            $sql = "UPDATE `data_cc` SET `id_societe_expediteur`='$expediteur', `id_societe_importateur`='$destination', `nombre_colis`='$nombre',
            `type_colis`='$type_colis', `lieu_scellage_pv`='$lieu_sce',`lieu_embarquement_pv`='$lieu_emb',`lien_pv_scellage`='$pathToSave',`pj_pv_scellage`='$pathToSavePDF',
            `num_domiciliation`='$numDom', `num_fiche_declaration_pv`='$declaration', `date_fiche_declaration_pv`='$date_declaration',`num_lp3e_pv`='$num_lp3',
            `date_lp3e`='$date_lp3', `date_modification_pv_scellage`='$date' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                        $_SESSION['toast_message'] = "Modification réussie.";
                        header("Location: ./lister.php");
                        exit();
                } else {
                        echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
        
    }