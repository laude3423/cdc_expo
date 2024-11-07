<?php
include_once('../../../scripts/db_connect.php');
include_once('../../../scripts/session.php');
// require_once('https://cdc.minesmada.org/scripts/db_connect.php');
// require_once('https://cdc.minesmada.org/scripts/session.php');
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $num_attestation = htmlspecialchars($_POST["num_attestation"]); 
    $date_attestation = htmlspecialchars($_POST["date_attestation"]);
    $id_societe_expediteur = $_POST['id_societe_expediteur'];
    $id_societe_importateur = $_POST['id_societe_importateur'];
    $num_attestation = intval($num_attestation);
    $requete = $conn->prepare('SELECT * FROM data_cc WHERE num_attestation = ?');
    $requete->bind_param('i', $num_attestation);
    $requete->execute();
    $resultat = $requete->get_result();
    if ($resultat->num_rows === 1) {
        $row= $resultat->fetch_assoc();
        $id_data_cc=$row['id_data_cc'];
        header("Location: https://cdc.minesmada.org/view_user/attestation_valeur/liste_contenu_attestation.php?id=" . $id_data_cc);
    }else{
        $pre ='En attente';
        date_default_timezone_set('Indian/Antananarivo');
        $heure_actuelle = date('H:i:s');
        //renommer l'heure
        $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
        $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_attestation);
        $uploadDir = '../../upload/';
        $uploadDir2 = '../upload/';
        $fileName_DOM = "SCAN_AV_" .$numDom_cleaned.$id_direction.$heureExacte_cleaned.".". pathinfo($_FILES['pj_attestation']['name'],
        PATHINFO_EXTENSION);
        $uploadPath_FAC = $uploadDir . $fileName_DOM;
        $uploadPath_FAC2 = $uploadDir2 . $fileName_DOM;
        
        if (move_uploaded_file($_FILES['pj_attestation']['tmp_name'], $uploadPath_FAC)) {

        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
        $validation_fac="En attente";
        $_SESSION['toast_message'] = "Insertion réussie.";
        $query2 = "INSERT INTO data_cc (id_user, id_societe_expediteur, id_societe_importateur, num_attestation, date_attestation, pj_attestation, validation_attestation) VALUES (?, ?,?, ?,?,?,?)";
        $stmt = $conn->prepare($query2);
        // Liaison des paramètres avec bind_param
        $stmt->bind_param("iiiisss",$num_userID,$id_societe_expediteur, $id_societe_importateur, $num_attestation, $date_attestation, $uploadPath_FAC2,$validation_fac);
        if($stmt->execute()){
            $id_data_cc = $conn->insert_id;
            $_SESSION['toast_message'] = "Insertion réussie.";
            header("Location: https://cdc.minesmada.org/view_user/attestation_valeur/liste_contenu_attestation.php?id=" . $id_data_cc);
        }else{
            $_SESSION['toast_message'] = "Insertion refusé.";
            header("Location: https://cdc.minesmada.org/view_user/attestation_valeur/liste_attestation.php");
        }
    }

    

} else {
    exit();
}