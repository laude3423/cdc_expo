<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
include '../../histogramme/insert_logs.php';
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id = $_POST['id_edit'] ?? null;
    $numero_lp = $_POST['num_lp_edit'] ?? null;
    $type_lp = $_POST['type_lp_edit'] ?? null;
    $folio = $_POST['folio_edit'] ?? null;
    $num_lp = $_POST['num_lp_edit'] ?? null;
    $quantite = $_POST['quantite'] ?? null;
    $unite = $_POST['unite'] ?? null;
    $titulaire = $_POST['nom_titulaire_edit'] ?? null;
    $date_creation = $_POST['date_creation_edit'] ?? null;
    $numero_autorisation = $_POST['num_autorisation_edit'] ?? null;
    $type_permis = $_POST['type_permis_edit'] ?? null;
    $numero_permis = $_POST['num_permis_edit'] ?? null;
    $nom_substance = $_POST['nom_substance_edit'] ?? null;
    $nom_commercant = $_POST['nom_commercant_edit'] ?? null;
    $nom_exportateur = $_POST['nom_exportateur_edit'] ?? null;
    $nom_transformateur = $_POST['nom_transformateur_edit'] ?? null;
    $validation_lp="En attente";
    $activite="Modification d'un ancien LP";
    $uploadDir = '../upload/';
        $num_passeport = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp);
        $fileName_LP = "SCAN_LP_" .$num_passeport.".".
        pathinfo($_FILES['scan_lp_edit']['name'], PATHINFO_EXTENSION);
        $uploadPath_LP = $uploadDir . $fileName_LP;
        //deplacement des fichier
        if (move_uploaded_file($_FILES['scan_lp_edit']['tmp_name'], $uploadPath_LP)) {
        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
        if($type_lp=="LPII"){
            $nom_commercant=""; $nom_exportateur=""; $nom_substance=""; $type_permis=""; $numero_permis=""; $numero_autorisation="";
            $query = "UPDATE ancien_lp SET validation_lp=?, quantite=?, unite=?, type_lp=?, date_creation=?, numero_folio=?, numero_lp=?, titulaire_lp=?, scan_lp=?, nom_transformateur=?, type_permis=?, numero_permis=?, nom_substance=?, nom_commercant=?, nom_exportateur=?, numero_autorisation=?  WHERE id_ancien_lp=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssssssssssi',$validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $nom_transformateur, $type_permis, $numero_permis, $nom_substance, $nom_commercant, $nom_exportateur, $numero_autorisation, $id);
            $result = $stmt->execute();
            if ($result) {
                insertLogs($conn, $userID, $activite);
                $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: ./lister.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification.</div>';
            }
        }else if($type_lp == "LPS"){
            $nom_commercant=""; $nom_exportateur=""; $nom_substance=""; $type_permis=""; $numero_permis=""; $nom_transformateur="";
            $query = "UPDATE ancien_lp SET validation_lp=?, quantite=?, unite=?, type_lp=?, date_creation=?, numero_folio=?, numero_lp=?, titulaire_lp=?, scan_lp=?, nom_transformateur=?, type_permis=?, numero_permis=?, nom_substance=?, nom_commercant=?, nom_exportateur=?, numero_autorisation=? WHERE id_ancien_lp=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssssssssssi',$validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP,  $nom_transformateur, $type_permis, $numero_permis, $nom_substance, $nom_commercant, $nom_exportateur, $numero_autorisation, $id);
            $result = $stmt->execute();
            if ($result) {
                insertLogs($conn, $userID, $activite);
                $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: ./lister.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification.</div>';
            }
        }else if($type_lp == "LPIIIC"){
             $nom_exportateur=""; $nom_substance=""; $type_permis=""; $numero_permis=""; $numero_autorisation=""; $nom_transformateur="";
             $query = "UPDATE ancien_lp SET validation_lp=?, quantite=?, unite=?, type_lp=?, date_creation=?, numero_folio=?, numero_lp=?, titulaire_lp=?, scan_lp=?, nom_transformateur=?, type_permis=?, numero_permis=?, nom_substance=?, nom_commercant=?, nom_exportateur=?, numero_autorisation=? WHERE id_ancien_lp=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssssssssssi', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP,  $nom_transformateur, $type_permis, $numero_permis, $nom_substance, $nom_commercant, $nom_exportateur, $numero_autorisation, $id);
            $result = $stmt->execute();
            if ($result) {
                insertLogs($conn, $userID, $activite);
                $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: ./lister.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification.</div>';
            }
        }else if($type_lp == "LPIIIE"){
            $nom_commercant=""; $nom_substance=""; $type_permis=""; $numero_permis=""; $numero_autorisation="";$nom_transformateur="";
             $query = "UPDATE ancien_lp SET validation_lp=?, quantite=?, unite=?, type_lp=?, date_creation=?, numero_folio=?, numero_lp=?, titulaire_lp=?, scan_lp=?, nom_transformateur=?, type_permis=?, numero_permis=?, nom_substance=?, nom_commercant=?, nom_exportateur=?, numero_autorisation=? WHERE id_ancien_lp=?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssssssssssi',$validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP,  $nom_transformateur, $type_permis, $numero_permis, $nom_substance, $nom_commercant, $nom_exportateur, $numero_autorisation, $id);
            $result = $stmt->execute();
            if ($result) {
                insertLogs($conn, $userID, $activite);
                $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: ./lister.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification.</div>';
            }
        }else if($type_lp=="LPIFOLIO"){
            $nom_commercant=""; $nom_exportateur=""; $numero_autorisation=""; $nom_transformateur="";
             $query = "UPDATE ancien_lp SET validation_lp=?, quantite=?, unite=?, type_lp=?, date_creation=?, numero_folio=?, numero_lp=?, titulaire_lp=?, scan_lp=?, nom_transformateur=?, type_permis=?, numero_permis=?, nom_substance=?, nom_commercant=?, nom_exportateur=?, numero_autorisation=? WHERE id_ancien_lp=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssssssssssi',$validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP,  $nom_transformateur, $type_permis, $numero_permis, $nom_substance, $nom_commercant, $nom_exportateur, $numero_autorisation, $id);
            $result = $stmt->execute();
            if ($result) {
                insertLogs($conn, $userID, $activite);
                $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: ./lister.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification.</div>';
            }
        }
}else{
    echo 'Aucune';
}