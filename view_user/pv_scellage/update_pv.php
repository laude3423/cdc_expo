<?php
    require_once('../../scripts/db_connect.php');
    require_once('../../scripts/session.php');
    require_once('../../histogramme/insert_logs.php');
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $facture=$_POST['id'];
        $id_data = $_POST['id'];
        $lieu_sce = htmlspecialchars($_POST['lieu_scellage']);
        $nombre = htmlspecialchars($_POST['nombre']);
        $type_colis= htmlspecialchars($_POST["type_colis"]);
        $agent_scellage= $_POST["agent_scellage"];
        $police = $_POST["police"];
        $douane = $_POST["douane"];
        $qualite = $_POST["qualite"];
        $fraude = $_POST["faude"];
        

        $requte="SELECT * FROM data_cc WHERE id_data_cc=$facture";
        $resultC = mysqli_query($conn, $requte);
        $rowA = mysqli_fetch_assoc($resultC);

        $monde_emballage = htmlspecialchars($rowA["mode_emballage"]);
        $lieu_emb = htmlspecialchars($rowA["lieu_embarquement_pv"]);
        $numDom = htmlspecialchars($rowA["num_domiciliation"]);
        $declaration = htmlspecialchars($rowA["num_fiche_declaration_pv"]);
        $date_declaration = $rowA["date_fiche_declaration_pv"];
        $num_lp3 = htmlspecialchars($rowA["num_lp3e_pv"]);
        $date_lp3 = $rowA["date_lp3e"];
        $date_dom = $rowA["date_dom"];
        $date_facture = $rowA["date_facture"];
        $expediteur = $rowA['id_societe_expediteur'];
        $destination = $rowA['id_societe_importateur'];
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);

        $agent1 = array();
        // Vérification et traitement de $chef


        //vérification et traitement de $agent_scellage
        // if(count($agent_scellage)> 0){
        //     for ($i = 0; $i < count($agent_scellage); $i++){
        //         $agent1[] = $agent_scellage[$i];
        //     }
        // }
        // Vérification et traitement de $douane
        if ($douane) {
            $agent1[] = $douane;
        }
        if ($police) {
            //$agent_scellage = array_push($agent_scellage, $police);
            $agent1[] = $police;
        }
        if ($fraude) {
            //$agent_scellage = array_push($agent_scellage, $police);
            $agent1[] = $fraude;
        }

        $num_pv='';
        $date_depart=null;
        $query = "SELECT num_pv_scellage FROM data_cc WHERE id_data_cc=$id_data";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        if(!empty($row['num_pv_scellage'])){
            $num_pv = $row['num_pv_scellage'];
        }
        include "../generate_fichier/generate_insert_scellage.php";
        //suppression sur la table agent
         $id_data_suppr=array();
          $requete = "SELECT * FROM pv_agent_assister WHERE id_data_cc = ?";
            $stmt = $conn->prepare($requete);
            if ($stmt) {
                // Liaison du paramètre
                $stmt->bind_param('i', $id_data);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id_data_suppr[] = $row['id_agent'];
                    }
                }else{
                    echo 'vide';
                } 
            }
           //suppression sur la table agent
          if (count($id_data_suppr) > 0) {
              $countAgents = count($id_data_suppr);
              for ($i = 0; $i < $countAgents; $i++) {
                  $query = "DELETE FROM `pv_agent_assister` WHERE id_data_cc = ? AND id_agent = ?";
                  $stmt = $conn->prepare($query);
                  $stmt->bind_param('ii', $id_data, $id_data_suppr[$i]);
                  $stmt->execute();
                  $stmt->close(); 
              }
          }else{
              echo 'Tsisy';
          }

        
            if (count($agent_scellage) > 0) {
                for ($i = 0; $i < count($agent_scellage); $i++) {
                    // Vérifiez d'abord si les données existent déjà
                    $checkQuery = "SELECT COUNT(*) FROM `pv_agent_assister` WHERE `id_agent` = ? AND `id_data_cc` = ?";
                    $stmt = $conn->prepare($checkQuery);
                    $stmt->bind_param("ii", $agent_scellage[$i], $facture);
                    $stmt->execute();
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $stmt->close();
                    
                    // Si l'entrée n'existe pas encore, insérez-la
                    if ($count == 0) {
                        $query = "INSERT INTO `pv_agent_assister` (`id_agent`, `id_data_cc`) VALUES (?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ii", $agent_scellage[$i], $facture);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
            if(!empty($qualite)){
                $requete="SELECT * FROM pv_agent_assister WHERE id_agent = $qualite AND id_data_cc=$facture";
                $result = $conn->query($requete);
                if ($result->num_rows > 0) {
                    //rien
                } else {
                    $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$facture')";
                    $result = mysqli_query($conn, $sql);
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
            if(!empty($fraude)){
                $sql3 = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$fraude','$facture')";
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
                    insertLogs($conn, $userID, $activite);
                        $_SESSION['toast_message'] = "Modification réussie.";
                        header("Location: https://cdc.minesmada.org/view_user/pv_scellage/detail.php?id=" . $id_data);
                        exit();
                } else {
                        echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
        
    }