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
    // if(!empty($qualite)){
    //     $requete="SELECT * FROM pv_agent_assister WHERE id_agent = $chef AND id_data_cc=$facture";
    //     $result = $conn->query($requete);
    //     if ($result->num_rows > 0) {
    //         //rien
    //     } else {
    //         $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$qualite','$facture')";
    //         $result = mysqli_query($conn, $sql);
    //     }
    // }
    if(count($agent_scellage) > 0){
    for ($i = 0; $i < count($agent_scellage); $i++) {
        $query="INSERT INTO  `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES (?, ?)" ; $stmt=$conn->prepare($query);
        $stmt->bind_param("ii", $agent_scellage[$i], $facture);
        $stmt->execute();
        }

        }
    if(!empty($douane)){
        $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$douane','$facture')";
        $result = mysqli_query($conn, $sql);
    }
    if(!empty($police)){
        $sql = "INSERT INTO `pv_agent_assister`(`id_agent`, `id_data_cc`) VALUES ('$police','$facture')";
        $result = mysqli_query($conn, $sql);
    }


        //modification pour l'insertion
        $sql="UPDATE `data_cc` SET `num_pv_scellage`='$num_pv',  `nombre_colis`='$nombre', `type_colis`='$type_colis', `lieu_scellage_pv`='$lieu_sce',
            `date_modification_pv_scellage`='$date', `lien_pv_scellage`='$pathToSave',`pj_pv_scellage`='$pathToSavePDF', 
            `validation_scellage`='En attente',`date_creation_pv_scellage`='$date' WHERE id_data_cc='$facture'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
        $_SESSION['toast_message'] = "Insertion réussie.";
        header("Location: https://cdc.minesmada.org/view_user/pv_scellage/detail.php?id=" . $facture);
        exit();
        } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    
    ?>