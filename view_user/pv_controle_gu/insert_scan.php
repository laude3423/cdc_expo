<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id_data'];
        $num_pv_scellage = $_POST['num_pv_scellage'];
        $num_pv_controle = $_POST['num_pv_controle'];

        $uploadDir = "../fichier/";
        $numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_pv_scellage);
        $fileName_SCE = "SCAN_" .$numDec_cleaned.".".
        pathinfo($_FILES['scan_scellage']['name'], PATHINFO_EXTENSION);
        $uploadPath_SCE = $uploadDir . $fileName_SCE;
        //pj lp3 e
        $numLP3_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_pv_controle);
        $fileName_LP3 = "SCAN_" .$numLP3_cleaned.".". pathinfo($_FILES['scan_controle']['name'],
        PATHINFO_EXTENSION);
        $uploadPath_CTL = $uploadDir . $fileName_LP3;
            //deplacement des fichier
            if (move_uploaded_file($_FILES['scan_scellage']['tmp_name'], $uploadPath_SCE)) {

            } else {
            echo "Erreur lors de l'upload du fichier.";
            }
            if (move_uploaded_file($_FILES['scan_controle']['tmp_name'], $uploadPath_CTL)) {

            } else {
            echo "Erreur lors de l'upload du fichier.";
            }

            $sql = "UPDATE `data_cc` SET `scan_controle`='$uploadPath_CTL',`scan_scellage`='$uploadPath_SCE' WHERE id_data_cc='$id'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                 header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id);
                exit();
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
            }
    }