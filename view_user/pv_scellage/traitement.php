<?php 

    //insertion sur l'agent
    // if(!empty($chef)){
    //     $requete="SELECT * FROM pv_agent_assister WHERE id_agent = $chef AND id_data_cc=$facture";
    //     $result = $conn->query($requete);
    //     if ($result->num_rows > 0) {
    //         //rien
    //     } else {
    //         $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$chef','$facture')";
    //         $result = mysqli_query($conn, $sql);
    //     }
    
    // }
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
    if (count($agent_scellage) > 0) {
        for ($i = 0; $i < count($agent_scellage); $i++) {
            // Requête pour vérifier l'existence de l'enregistrement
            $checkQuery = "SELECT COUNT(*) FROM `pv_agent_assister` WHERE `id_agent` = ? AND `id_data_cc` = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("ii", $agent_scellage[$i], $facture);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            // Insérer seulement si l'enregistrement n'existe pas
            if ($count == 0) {
                $query = "INSERT INTO `pv_agent_assister` (`id_agent`, `id_data_cc`) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $agent_scellage[$i], $facture);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    if (!empty($douane)) {
        $check_sql = "SELECT * FROM `pv_agent_assister` WHERE `id_agent` = '$douane' AND `id_data_cc` = '$facture'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) == 0) {
            $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$douane', '$facture')";
            $result = mysqli_query($conn, $sql);
        }
    }
    if (!empty($fraude)) {
        $check_sql = "SELECT * FROM `pv_agent_assister` WHERE `id_agent` = '$fraude' AND `id_data_cc` = '$facture'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) == 0) {
            $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$fraude', '$facture')";
            $result = mysqli_query($conn, $sql);
        }
    }

    if (!empty($police)) {
        $check_sql = "SELECT * FROM `pv_agent_assister` WHERE `id_agent` = '$police' AND `id_data_cc` = '$facture'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) == 0) {
            $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$police', '$facture')";
            $result = mysqli_query($conn, $sql);
        }
    }
        //modification pour l'insertion
        $sql="UPDATE `data_cc` SET `num_pv_scellage`='$num_pv',  `nombre_colis`='$nombre', `type_colis`='$type_colis', `lieu_scellage_pv`='$lieu_sce',
            `date_modification_pv_scellage`='$date', `lien_pv_scellage`='$pathToSave',`pj_pv_scellage`='$pathToSavePDF', 
            `validation_scellage`='En attente',`date_creation_pv_scellage`='$date' WHERE id_data_cc='$facture'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            insertLogs($conn, $userID, $activite);
        $_SESSION['toast_message'] = "Insertion réussie.";
        header("Location: https://cdc.minesmada.org/view_user/pv_scellage/detail.php?id=" . $facture);
        exit();
        } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    
    ?>