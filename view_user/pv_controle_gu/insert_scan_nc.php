<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    require_once '../../vendor/autoload.php';
    use \setasign\Fpdi\Tcpdf\Fpdi;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id_data'];
        
        if($groupeID===3){
            $num_pv_scellage = $_POST['num_pv_scellage'];
        }else{
            $num_pv_scellage = "";
            $uploadPath_SCE="";
        }
        $num_pv_controle = $_POST['num_pv_controle'];
        $droit_conformite =$_POST['droit_conformite'];
        $id_data = $_POST['id_data'];
        $num_ov =$_POST['num_ov'];
        $date_ov =$_POST['date_ov'];
        $id_data = $_POST['id_data'];
        $num_quittance =$_POST['num_quittance'];
        $date_quittance =$_POST['date_quittance'];
        date_default_timezone_set('Indian/Antananarivo');
        $heure_actuelle = date('H:i:s');
        //renommer l'heure
        $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);

        $numOV_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_ov);
       // Directory for upload
        $uploadDir = __DIR__ . '/../upload/'; 
        // Vérification et traitement des fichiers OV
        if (isset($_FILES['scan_ov_droit']) && $_FILES['scan_ov_droit']['error'] == UPLOAD_ERR_OK) {

            $numOV_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_ov);
            $fileName_OV = "SCAN_OV_".$numOV_cleaned.$heureExacte_cleaned.".pdf";
            $uploadPath_OV = $uploadDir . $fileName_OV;

            $finalPath_OV = '../upload/' . $fileName_OV;
            if (move_uploaded_file($_FILES['scan_ov_droit']['tmp_name'], $uploadPath_CTL)) {
                $sql = "UPDATE `data_cc` SET `scan_ov`='$finalPath_OV' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    echo "Erreur lors de la mise à jour de la base de données pour le fichier OV.";
                }
            } else {
            echo "Erreur lors de l'upload du fichier.";
            }
        }
        // Vérification et traitement des fichiers Quittance
        if (isset($_FILES['scan_quittance_droit']) && $_FILES['scan_quittance_droit']['error'] == UPLOAD_ERR_OK) {

            $numQTT_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_quittance);
            $fileName_QT = "SCAN_QTT_".$numQTT_cleaned.$heureExacte_cleaned.".pdf";
            $uploadPath_QT = $uploadDir . $fileName_QT;
            $finalPath_QT = '../upload/' . $fileName_QT;

            if (move_uploaded_file($_FILES['scan_quittance_droit']['tmp_name'], $uploadPath_CTL)) {
                $sql = "UPDATE `data_cc` SET `scan_quittance`='$finalPath_QT' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);
                if (!$result) {
                    echo "Erreur lors de la mise à jour de la base de données pour le fichier Quittance.";
                }
            } else {
            echo "Erreur lors de l'upload du fichier.";
            }
        }
        $uploadDir = "../fichier/";
        
        //pj controle
        $numCTL_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_pv_controle);
        $fileName_CTL = "SCAN_PCC_" .$numCTL_cleaned.".". pathinfo($_FILES['scan_controle']['name'],
        PATHINFO_EXTENSION);
        $uploadPath_CTL = $uploadDir . $fileName_CTL;
            
            if (move_uploaded_file($_FILES['scan_controle']['tmp_name'], $uploadPath_CTL)) {
            } else {
            echo "Erreur lors de l'upload du fichier.";
            }

            $sql = "UPDATE `data_cc` SET  `droit_conformite` ='$droit_conformite', `num_ov` ='$num_ov', `num_ov` ='$num_ov',`num_quittance` ='$num_quittance',`date_quittance` ='$date_quittance',`scan_controle`='$uploadPath_CTL' WHERE id_data_cc='$id'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                 header("Location: https://cdc.minesmada.org/view_user/pv_controle/detail.php?id=" . $id);
                exit();
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
            }

            $stmt->close();
    }