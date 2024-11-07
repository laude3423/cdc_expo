<?php

    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    require_once '../../vendor/autoload.php';
    use \setasign\Fpdi\Tcpdf\Fpdi;
    require('../fpdf/fpdf.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id_data'];
        $id_data = $_POST['id_data'];
        
        if($groupeID===3){
            $num_pv_scellage = $_POST['num_pv_scellage'];
        }else{
            $num_pv_scellage = "";
            $uploadPath_SCE="";
        }
        $num_pv_controle = $_POST['num_pv_controle'];
        $num_ov =$_POST['num_ov'];
        $date_ov =$_POST['date_ov'];
        $num_quittance =$_POST['num_quittance'];
        $date_quittance =$_POST['date_quittance'];
        $ristourne =$_POST['ristourne'];
        $redevance =$_POST['redevance'];
        $droit_conformite =$_POST['droit_conformite'];
        $description = $_POST['description'];

        date_default_timezone_set('Indian/Antananarivo');
        $heure_actuelle = date('H:i:s');
        $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
        //scan OV
        $uploadDir = __DIR__ . '/../upload/';
        $pdf = new Fpdi();
        // Vérification et traitement des fichiers OV
        if ((isset($_FILES['scan_ov_rdv']) && $_FILES['scan_ov_rdv']['error'] == UPLOAD_ERR_OK) ||
            (isset($_FILES['scan_ov_ris']) && $_FILES['scan_ov_ris']['error'] == UPLOAD_ERR_OK) ||
            (isset($_FILES['scan_ov_droit']) && $_FILES['scan_ov_droit']['error'] == UPLOAD_ERR_OK)) {

            $numOV_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_ov);
            $fileName_OV = "SCAN_OV_".$numOV_cleaned. $heureExacte_cleaned.".pdf";
            $uploadPath_OV = $uploadDir . $fileName_OV;

            $ov_files = ['scan_ov_rdv', 'scan_ov_ris', 'scan_ov_droit'];
            foreach ($ov_files as $file_input_name) {
                if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES[$file_input_name]['tmp_name'];

                    // Vérifiez si le fichier est un PDF
                    $file_extension = pathinfo($_FILES[$file_input_name]['name'], PATHINFO_EXTENSION);
                    echo "Traitement du fichier : " . $_FILES[$file_input_name]['name'] . "<br>";

                    if (filesize($tmp_name) > 0) {
                        if (strtolower($file_extension) === 'pdf') {
                            // Vérifiez si le fichier commence par %PDF-
                            $pdf_file_content = file_get_contents($tmp_name);
                            if (strpos($pdf_file_content, '%PDF-') === false) {
                                echo "Le fichier n'est pas un PDF valide : " . $_FILES[$file_input_name]['name'] . "<br>";
                            } else {
                                // Importer le fichier PDF
                                $pageCount = $pdf->setSourceFile($tmp_name);
                                echo "Nombre de pages dans le fichier : $pageCount<br>";

                                if ($pageCount > 0) { // Vérifiez si le PDF a des pages
                                    for ($i = 1; $i <= $pageCount; $i++) {
                                        $pdf->AddPage();
                                        $templateId = $pdf->importPage($i);
                                        $pdf->useTemplate($templateId);
                                    }
                                } else {
                                    echo "Le fichier PDF est vide : " . $_FILES[$file_input_name]['name'];
                                }
                            }
                        } else {
                            echo "Le fichier n'est pas un PDF valide : " . $_FILES[$file_input_name]['name'];
                        }
                    } else {
                        echo "Le fichier temporaire est vide : " . $tmp_name;
                    }
                }
            }

            // Sauvegarde du PDF combiné pour OV
            $pdf->Output($uploadPath_OV, 'F');

            // Importer le fichier dans le dossier final
            $finalPath_OV = '../upload/' . $fileName_OV;
            $sql = "UPDATE `data_cc` SET `scan_ov`='$finalPath_OV' WHERE id_data_cc='$id_data'";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                echo "Erreur lors de la mise à jour de la base de données pour le fichier OV.";
            }
        }
        $pdf2 = new Fpdi();
        // Vérification et traitement des fichiers Quittance
        if ((isset($_FILES['scan_quittance_rdv']) && $_FILES['scan_quittance_rdv']['error'] == UPLOAD_ERR_OK) ||
            (isset($_FILES['scan_quittance_ris']) && $_FILES['scan_quittance_ris']['error'] == UPLOAD_ERR_OK) ||
            (isset($_FILES['scan_quittance_droit']) && $_FILES['scan_quittance_droit']['error'] == UPLOAD_ERR_OK)) {

            $numQTT_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_quittance);
            $fileName_QT = "SCAN_QTT_".$numQTT_cleaned.$heureExacte_cleaned.".pdf";
            $uploadPath_QT = $uploadDir . $fileName_QT;

            
            $quittance_files = ['scan_quittance_rdv', 'scan_quittance_ris', 'scan_quittance_droit'];
            foreach ($quittance_files as $file_input_name) {
                if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES[$file_input_name]['tmp_name'];

                    // Vérifiez si le fichier est un PDF
                    $file_extension = pathinfo($_FILES[$file_input_name]['name'], PATHINFO_EXTENSION);
                    echo "Traitement du fichier : " . $_FILES[$file_input_name]['name'] . "<br>";

                    if (filesize($tmp_name) > 0) {
                        if (strtolower($file_extension) === 'pdf') {
                            // Vérifiez si le fichier commence par %PDF-
                            $pdf_file_content = file_get_contents($tmp_name);
                            if (strpos($pdf_file_content, '%PDF-') === false) {
                                echo "Le fichier n'est pas un PDF valide : " . $_FILES[$file_input_name]['name'] . "<br>";
                            } else {
                                // Importer le fichier PDF
                                $pageCount = $pdf2->setSourceFile($tmp_name);
                                echo "Nombre de pages dans le fichier : $pageCount<br>";

                                if ($pageCount > 0) { // Vérifiez si le PDF a des pages
                                    for ($i = 1; $i <= $pageCount; $i++) {
                                        $pdf2->AddPage();
                                        $templateId = $pdf2->importPage($i);
                                        $pdf2->useTemplate($templateId);
                                    }
                                } else {
                                    echo "Le fichier PDF est vide : " . $_FILES[$file_input_name]['name'];
                                }
                            }
                        } else {
                            echo "Le fichier n'est pas un PDF valide : " . $_FILES[$file_input_name]['name'];
                        }
                    } else {
                        echo "Le fichier temporaire est vide : " . $tmp_name;
                    }
                }
            }


            // Sauvegarde du PDF combiné pour Quittance
            $pdf2->Output($uploadPath_QT, 'F');

            // Importer le fichier dans le dossier final
            $finalPath_QT = '../upload/' . $fileName_QT;
            $sql = "UPDATE `data_cc` SET `scan_quittance`='$finalPath_QT' WHERE id_data_cc='$id_data'";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                echo "Erreur lors de la mise à jour de la base de données pour le fichier Quittance.";
            }
        }

        //scan Quittance
        if($groupeID===3){
             if (($_FILES['scan_scellage']) && $_FILES['scan_scellage']['error'] == UPLOAD_ERR_OK) {
                $numDec_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_pv_scellage);
                $fileName_SCE = "SCAN_PSC_" .$numDec_cleaned.".".
                pathinfo($_FILES['scan_scellage']['name'], PATHINFO_EXTENSION);
                $uploadPath_SCE = $uploadDir . $fileName_SCE;

                if (move_uploaded_file($_FILES['scan_scellage']['tmp_name'], $uploadPath_SCE)) {

                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
                $sql = "UPDATE `data_cc` SET `scan_scellage`='$uploadPath_SCE' WHERE id_data_cc='$id_data'";
                $result = mysqli_query($conn, $sql);
            }
            
        }
        //pj controle
        if (($_FILES['scan_controle']) && $_FILES['scan_controle']['error'] == UPLOAD_ERR_OK) {
            $numCTL_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_pv_controle);
            $fileName_CTL = "SCAN_PCC_" .$numCTL_cleaned.".". pathinfo($_FILES['scan_controle']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_CTL = $uploadDir . $fileName_CTL;
            if (move_uploaded_file($_FILES['scan_controle']['tmp_name'], $uploadPath_CTL)) {

            } else {
            echo "Erreur lors de l'upload du fichier.";
            }
            $sql = "UPDATE `data_cc` SET `scan_controle`='$uploadPath_CTL' WHERE id_data_cc='$id_data'";
            $result = mysqli_query($conn, $sql);
        }
        $sql = "UPDATE `data_cc` SET `redevance` ='$redevance', `ristourne` ='$ristourne', `description` ='$description', `droit_conformite` ='$droit_conformite',`validation_chef`='En attente', `validation_directeur`='En attente', `num_ov` ='$num_ov',`date_ov` ='$date_ov',`num_quittance` ='$num_quittance',`date_quittance` ='$date_quittance' WHERE id_data_cc='$id'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $_SESSION['toast_message'] = "Modification réussie.";
                 header("Location: https://cdc.minesmada.org/view_user/pv_controle/detail.php?id=" . $id);
                exit();
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
            }

            $stmt->close();
    }