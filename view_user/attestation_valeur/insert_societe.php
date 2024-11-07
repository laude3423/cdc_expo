<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
require('../../histogramme/insert_logs.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pre ='En attente';
    $id_data_cc = $_POST['id_data_cc'];
    $unites = $_POST['unite']; // Ceci est un tableau
    $poids = $_POST['poids'];
    $numero_lp = $_POST['numero_lp'];
    $id_data_cc = $_POST['id_data_cc'];
    $nom_substances = $_POST['nom_substance'];
    $id_lp = NULL;

    if((!empty($numero_lp))&&(isset($_FILES['scan_lp']) && $_FILES['scan_lp']['error'] == UPLOAD_ERR_OK)){
        $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $numero_lp);

        $uploadDir2 = '../upload/';
        $fileName_DOM = "SCAN_LP_" .$numDom_cleaned.".". pathinfo($_FILES['scan_lp']['name'],
        PATHINFO_EXTENSION);
        $uploadPath_LP = $uploadDir2 . $fileName_DOM;
        
        if (move_uploaded_file($_FILES['scan_lp']['tmp_name'], $uploadPath_LP)) {
            $query = "INSERT INTO lp_scan (numero_lp, scan_lp) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $numero_lp, $uploadPath_LP);
            $stmt->execute();
            $id_lp = $conn->insert_id;
        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
    }


       if(count($nom_substances)){
            for ($i = 0; $i < count($nom_substances); $i++) {
                $poid = floatval($poids[$i]);
                if($poid > 0){
                    $query3 = "INSERT INTO contenu_attestation (id_data_cc,id_substance,unite, poids_attestation, id_lp_scan) VALUES ( ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($query3);
                    $nom_substance = htmlspecialchars($nom_substances[$i], ENT_QUOTES, 'UTF-8');
                    $unite = htmlspecialchars($unites[$i], ENT_QUOTES, 'UTF-8');
                    
                    // Liaison des paramètres avec bind_param
                    $stmt->bind_param("iisdi", $id_data_cc, $nom_substance, $unite, $poid, $id_lp);
                    $stmt->execute();
                }
               
            }
            insertLogs($conn, $userID, $activite);
             $_SESSION['toast_message'] ="Insertion succès";
            header("Location: https://cdc.minesmada.org/view_user/attestation_valeur/liste_contenu_attestation.php?id=" . $id_data_cc);
            exit();
        }
    }