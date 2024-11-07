<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php
if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $id = $_POST['id'] ?? null;
    $civilite = $_POST['civilite_edit'] ?? '';
    $nom = $_POST['nom_edit'] ?? '';
    $prenom = $_POST['prenom_edit'] ?? '';
    $passeport = $_POST['passeport_edit'] ?? '';
    $num_vol = $_POST['num_vol_edit'] ?? '';
    $compagnie = $_POST['compagnie_edit'] ?? '';
    $escale = $_POST['escale_edit'] ?? '';
    $designation = $_POST['designation_edit'] ?? '';
    $poids = $_POST['poids_edit'] ?? '';
    $unite = $_POST['unite_edit'] ?? '';
    $date_depart=$_POST['date_depart_edit'] ?? '';
    $matricule = $_POST['matricule_edit'] ?? '';
    $nom_agent = $_POST['nom_agent_edit'] ?? '';
    $prenom_agent = $_POST['prenom_agent_edit'] ?? '';
    $id_pays = $_POST['id_pays_edit'] ?? '';
    $id=$_POST['id'];
    $facture = $_POST['facture_edit'] ?? '';
    $id_vol=$_POST['num_vol_edit'] ??"";

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
        $query = "UPDATE autorisation SET scan_passeport=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $uploadPath_PAS, $id);
        $result = $stmt->execute();
    }
    if (isset($_FILES['scan_facture_edit']) && $_FILES['scan_facture_edit']['error'] == UPLOAD_ERR_OK) {
        $num_facture = preg_replace('/[^a-zA-Z0-9]/', '-', $facture);
        $fileName_FAC = "SCAN_FACTURE_" .$num_facture.".".
        pathinfo($_FILES['scan_facture_edit']['name'], PATHINFO_EXTENSION);
        $uploadPath_FAC = $uploadDir . $fileName_FAC;
        //deplacement des fichier
        if (move_uploaded_file($_FILES['scan_facture_edit']['tmp_name'], $uploadPath_FAC)) {
        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
        $query = "UPDATE autorisation SET scan_facture=? WHERE id_autorisation=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $uploadPath_FAC, $id);
        $result = $stmt->execute();
    }
    include('./generate.php');
        $query = "UPDATE autorisation SET id_pays= ?,date_depart=?,numero_facture=?, lien_autorisation=?,pj_autorisation=?, date_modification=?, nom_porteur=?, prenom_porteur=?, numero_passeport=?, id_vol=?, designation=?, poids=?, unite=?, id_agent_controle=? WHERE id_autorisation=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('issssssssisssii', $id_pays,$date_depart, $facture, $pathToSave, $pathToSavePDF,$dateInsert, $nom, $prenom, $passeport, $id_vol, $designation, $poids, $unite, $matricule, $id);
        $result = $stmt->execute();

        if ($result) {
            $_SESSION['toast_message'] = "Modification réussie.";
             header("Location:https://cdc.minesmada.org/aut_visa/autorisation/lister.php");
             exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification de l\'autorisation.</div>';
        }
}