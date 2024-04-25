<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $expediteur = htmlspecialchars($_POST['expediteur']);
        $importateur = htmlspecialchars($_POST["importateur"]);
        $id_data = htmlspecialchars($_POST["id"]);
        $mode_emballage = htmlspecialchars($_POST["mode_emballage"]);
        $lieu_controle = htmlspecialchars($_POST["lieu_controle"]);
        $lieu_embarquement = htmlspecialchars($_POST["lieu_emb"]);
        $num_domiciliation = htmlspecialchars($_POST["numDom"]);
        $num_fiche_declaration = htmlspecialchars($_POST["declaration"]);
        $date_declaration =$_POST["date_declaration"];
        $num_lp3e = htmlspecialchars($_POST["num_lp3"]);
        $date_lp3e = $_POST["date_lp3"];
        $chef = $_POST["chef"];
        $qualite = $_POST["qualite"];

        $id_data = $_POST['id'];
        $dateFormat = "Y-m-d";
        $dateInsert = date($dateFormat);

        $sql="SELECT * FROM direction WHERE id_direction=$id_direction";
        $resultD = mysqli_query($conn, $sql);
        $rowD = mysqli_fetch_assoc($resultD);
        $typeDirection=$rowD['type_direction'];
        $nomDirection=$rowD['nom_direction'];
        
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
            include "../generate_fichier/generate_insertControle.php";
        }
        
         //prendre l'eure du réseau
        date_default_timezone_set('Indian/Antananarivo');
        $heure_actuelle = date('H:i:s');
        //renommer l'heure
        $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
        //pj_DOM
        if (!empty($_FILES['pj_dom']['name'])) {
          $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $numDom);
        $uploadDir = '../upload/';
         $fileName_DOM = "SCAN_DOM_" .$numDom_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_dom']['name'], PATHINFO_EXTENSION);
         $uploadPath_DOM = $uploadDir . $fileName_DOM;  
         move_uploaded_file($_FILES['pj_dom']['tmp_name'], $uploadPath_DOM);
        }
        //pj declaration
        if (!empty($_FILES['pj_declaration']['name'])) {
            $numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $declaration);
         $fileName_DEC = "SCAN_DECLARATION_" .$numDec_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_declaration']['name'], PATHINFO_EXTENSION);
        $uploadPath_DEC = $uploadDir . $fileName_DEC;
        move_uploaded_file($_FILES['pj_declaration']['tmp_name'], $uploadPath_DEC);
        }
        //pj lp3 e
        if (!empty($_FILES['pj_lp3']['name'])) {
            $numLP3_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp3);
            $fileName_LP3 = "SCAN_LPIIIE_" .$numLP3_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_lp3']['name'], PATHINFO_EXTENSION);
            $uploadPath_LP3 = $uploadDir . $fileName_LP3;

            move_uploaded_file($_FILES['pj_lp3']['tmp_name'], $uploadPath_LP3);
        }
        //suppression sur la table agent
        $query = "DELETE FROM `pv_agent_assister` WHERE id_data_cc = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_data);
        $stmt->execute();
        //insertion sur le table agent
            if(!empty($chef)){
                $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$id_data')";
                $result = mysqli_query($conn, $sql);
            }
            if(!empty($qualite)){
                $sql2 = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$id_data')";
                $result = mysqli_query($conn, $sql2);
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

            $sql = "UPDATE `data_cc` SET `id_societe_expediteur`='$expediteur', `id_societe_importateur`='$importateur',
        `mode_emballage`='$mode_emballage',`lieu_controle_pv`='$lieu_controle',`lieu_embarquement_pv`='$lieu_embarquement', `num_domiciliation`='$num_domiciliation',
        `num_fiche_declaration_pv`='$num_fiche_declaration',
        `date_fiche_declaration_pv`='$date_declaration',`num_lp3e_pv`='$num_lp3e',`date_lp3e`='$date_lp3e',
        `lien_pv_controle`='$pathToSave',`pj_pv_controle`='$pathToSavePDF',`date_modification_pv_controle`='$dateInsert',
        `num_cc`='$num_cc',`date_cc`='$dateInsert',`lien_cc`='$lien_cc',`pj_cc`='$pj_cc' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                        $_SESSION['toast_message'] = "Modification réussie.";
                        header("Location: ./lister.php");
                        exit();
                } else {
                        echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
        
    }