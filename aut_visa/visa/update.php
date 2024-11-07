<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id = $_POST['id_edit'] ?? null;
    $civilite = $_POST['civilite_edit'] ?? '';
    $nom = $_POST['nom_edit'] ?? '';
    $type_trasport = $_POST['type_trasport'] ?? '';
    $prenom = $_POST['prenom_edit'] ?? '';
    $passeport = $_POST['passeport_edit'] ?? '';
    $compagnie = $_POST['compagnie_edit'] ?? '';
    $escale = $_POST['escale_edit'] ?? '';
    $matricule = $_POST['matricule_edit'] ?? '';
    $nom_agent = $_POST['nom_agent_edit'] ?? '';
    $prenom_agent = $_POST['prenom_agent_edit'] ?? '';
    $date_depart_edit_fret=$_POST['date_depart_edit_fret'] ?? '';
    $facture = $_POST['facture_edit'] ?? '';
    $id_vol=$_POST['num_vol_edit'] ??"";
    $id_fret=$_POST['nom_fret_edit'] ?? "";
    $date_depart = $_POST['date_depart_edit'] ?? '';
    $id_data=$_POST['id_data_edit'] ?? '';

    // Effectuer une validation des données si nécessaire
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $dateInsert = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');
    // Par exemple, vérifier que les champs obligatoires ne sont pas vides
    //pj declaration
    $uploadDir = '../upload/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    if (isset($_FILES['scan_passeport_edit']) && $_FILES['scan_passeport_edit']['error'] == UPLOAD_ERR_OK) {
        $num_passeport = preg_replace('/[^a-zA-Z0-9]/', '-', $passeport);
        $fileName_PAS = "SCAN_PASSEPORT_" .$num_passeport.".".
        pathinfo($_FILES['scan_passeport_edit']['name'], PATHINFO_EXTENSION);
        $uploadPath_PAS = $uploadDir . $fileName_PAS;
        //deplacement des fichier
        if (move_uploaded_file($_FILES['scan_passeport_edit']['tmp_name'], $uploadPath_PAS)) {
        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
        $query = "UPDATE visa SET scan_passeport=? WHERE id_visa=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $uploadPath_PAS, $id);
        $result = $stmt->execute();
    }
    if($type_trasport=="OUI"){
        $query = "UPDATE visa SET date_depart=?, date_modification=?, nom_porteur=?, prenom_porteur=?, numero_passeport=?, id_vol=?, id_agent_controle=?, civilite=?, id_data_cc=? WHERE id_visa=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssiisii', $date_depart,$dateInsert, $nom, $prenom, $passeport, $id_vol,$matricule, $civilite, $id_data, $id);
        $result = $stmt->execute();
         if ($result) {
            $_SESSION['toast_message'] = "Modification réussie.";
                header("Location:https://cdc.minesmada.org/aut_visa/visa/lister.php");
                exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification du visa.</div>';
        }
    }else{
        $query = "UPDATE visa SET date_depart=?, date_modification=?, id_fret=?, id_agent_controle=?, civilite=?, id_data_cc=? WHERE id_visa=?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Error in prepare: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param('ssiisii', $date_depart_edit_fret, $dateInsert, $id_fret, $matricule, $civilite, $id_data, $id);
        $result = $stmt->execute();
        if ($result) {
            $_SESSION['toast_message'] = "Modification réussie.";
            header("Location:https://cdc.minesmada.org/aut_visa/visa/lister.php");
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification du visa: ' . htmlspecialchars($stmt->error) . '</div>';
        }
        $stmt->close();
    }
}