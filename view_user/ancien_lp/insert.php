<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
include '../../histogramme/insert_logs.php';
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id = $_POST['id'] ?? null;
    $numero_lp = $_POST['num_lp'] ?? null;
    $type_lp = $_POST['type_lp'] ?? null;
    $folio = $_POST['folio'] ?? null;
    $quantite = $_POST['quantite'];
    $unite = $_POST['unite'];
    $num_lp = $_POST['num_lp'] ?? null;
    $titulaire = $_POST['nom_titulaire'] ?? null;
    $date_creation = $_POST['date_creation'] ?? null;
    $type_permis = $_POST['type_permis'] ?? null;
    $numero_permis = $_POST['num_permis'] ?? null;
    $nom_substance = $_POST['nom_substance'] ?? null;
    $nom_commercant = $_POST['nom_commercant'] ?? null;
    $numero_autorisation = $_POST['numero_autorisation'] ?? null;
    $nom_transformateur = $_POST['nom_transformateur'] ?? null;
    $activite = "Insertion d'un nouvel ancien LP";
    // Effectuer une validation des données si nécessaire
    $dateFormat = "Y-m-d";
    $date = date($dateFormat);
    $dateInsert = date($dateFormat);
    $anneeActuelle = date('Y');
    $moisActuel = date('m');
    $num_as="";
    $validation_lp="En attente";
    $sql ="SELECT * FROM ancien_lp WHERE numero_lp='$numero_lp'";
    $resultLp = mysqli_query($conn, $sql);
    $rowLp = mysqli_fetch_assoc($resultLp);
    if(empty($rowLp['numero_lp'])){
        $uploadDir = '../upload/';
        $num_passeport = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp);
        $fileName_LP = "SCAN_LP_" .$num_passeport.".".
        pathinfo($_FILES['scan_lp']['name'], PATHINFO_EXTENSION);
        $uploadPath_LP = $uploadDir . $fileName_LP;
        //deplacement des fichier
        if (move_uploaded_file($_FILES['scan_lp']['tmp_name'], $uploadPath_LP)) {
        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
        if($type_lp=="LPII"){
            $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, nom_transformateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssss',$validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $nom_transformateur);
            $result = $stmt->execute();

            if ($result) {
                insertLogs($conn, $userID, $activite);
                $_SESSION['toast_message'] = "Insertion réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
            }
    }else if($type_lp == "FDC"){
        $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, numero_autorisation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sdssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $numero_autorisation);
        $result = $stmt->execute();
        if ($result) {
            insertLogs($conn, $userID, $activite);
            $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
        }
        
    }else if($type_lp == "LPIIIC"){
        $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, nom_commercant) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sdssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $nom_commercant);
        $result = $stmt->execute();
        if ($result) {
            insertLogs($conn, $userID, $activite);
            $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
        }
        // }else if($type_lp == "FDC"){
        //     $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, nom_exportateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //     $stmt = $conn->prepare($query);
        //     $stmt->bind_param('sdssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $nom_exporteur);
        //     $result = $stmt->execute();
        //     if ($result) {
        //         $_SESSION['toast_message'] = "Insertion réussie.";
        //             header("Location: ".$_SERVER['PHP_SELF']);
        //         exit();
        //     } else {
        //         echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
        //     }
        }else if($type_lp=="LPI"){
            $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, type_permis, numero_permis, nom_substance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $type_permis, $numero_permis, $nom_substance);
            $result = $stmt->execute();
            if ($result) {
                insertLogs($conn, $userID, $activite);
                $_SESSION['toast_message'] = "Insertion réussie.";
                     header("Location: ./lister.php");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
            }
        }else{
            echo $type_lp;
        }
    }else{
        $_SESSION['toast_message2'] = "LP déjà existe, Veuillez vérifier le numero LP.";
        header("Location: ./lister.php");
        exit();
    }
    
    

}else{
    echo 'vide';
}