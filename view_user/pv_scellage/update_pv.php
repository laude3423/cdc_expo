<?php
    require_once('../../scripts/db_connect.php');
    session_start();
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $facture=$_POST['id'];
        $id_data = $_POST['id'];
        $lieu_sce = $_POST['lieu_scellage'];
        $nombre = $_POST['nombre'];
        $type_colis= $_POST["type_colis"];
        $agent_scellage= $_POST["agent_scellage"];
        $police = $_POST["police"];
        $douane = $_POST["douane"];

        $requte="SELECT * FROM data_cc WHERE id_data_cc=$facture";
        $resultC = mysqli_query($conn, $requte);
        $rowA = mysqli_fetch_assoc($resultC);

        $monde_emballage = $rowA["mode_emballage"];
        $lieu_emb = $rowA["lieu_embarquement_pv"];
        $numDom = $rowA["num_domiciliation"];
        $declaration = $rowA["num_fiche_declaration_pv"];
        $date_declaration = $rowA["date_fiche_declaration_pv"];
        $num_lp3 = $rowA["num_lp3e_pv"];
        $date_lp3 = $rowA["date_lp3e"];
        $expediteur = $rowA['id_societe_expediteur'];
        $destination = $rowA['id_societe_importateur'];
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);

$agent1 = array();
// Vérification et traitement de $chef


//vérification et traitement de $agent_scellage
if(count($agent_scellage)> 0){
    for ($i = 0; $i < count($agent_scellage); $i++){
        $agent1[] = $agent_scellage[$i];
    }
}
// Vérification et traitement de $douane
if ($douane) {
    $agent1[] = $douane;
}
if ($police) {
    //$agent_scellage = array_push($agent_scellage, $police);
    $agent1[] = $police;
}

        $num_pv='';
        $date_depart=null;
        $query = "SELECT num_pv_scellage, date_depart FROM data_cc WHERE id_data_cc=$id_data";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if(!empty($row['num_pv_scellage'])){
            $num_pv = $row['num_pv_scellage'];
            $date_depart = $row['date_depart'];
        }
        include "../generate_fichier/generate_insert_scellage.php";
        //suppression sur la table agent
         if (count($agent1) > 0) {
            $countAgents = count($agent1);
            for ($i = 0; $i < $countAgents; $i++) {
                $query = "DELETE FROM `pv_agent_assister` WHERE id_data_cc = ? AND id_agent = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $facture, $agent1[$i]);
                $stmt->execute();
                
                $stmt->close(); 
            }
        }else{
            echo 'Tsisy';
        }

        //insertion sur le table agent
            // if(!empty($chef)){
            //     $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$facture')";
            //     $result = mysqli_query($conn, $sql);
            // }
            // if(!empty($qualite)){
            //     $sql2 = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$facture')";
            //     $result = mysqli_query($conn, $sql2);
            // }
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

            $sql = "UPDATE `data_cc` SET  `nombre_colis`='$nombre', `type_colis`='$type_colis', `lieu_scellage_pv`='$lieu_sce',
            `date_modification_pv_scellage`='$date', `validation_scellage`='En attente' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                        $_SESSION['toast_message'] = "Modification réussie.";
                        header("Location: https://cdc.minesmada.org/view_user/pv_scellage/detail.php?id=" . $id_data);
                        exit();
                } else {
                        echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
        
    }