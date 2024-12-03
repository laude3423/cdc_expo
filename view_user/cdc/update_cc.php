<?php 
require('../../histogramme/insert_logs.php');
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $activite = "Modification d'un nouveau certificat de conformité.";
        $num_domiciliation = $_POST['numero_dom'];
        $date_dom = $_POST["date_dom"];
        $dateFormat = "Y-m-d";
        $id_data = $_POST["id_data_cc"];
        $id_data_cc = $_POST["id_data_cc"];
        $dateInsert = date($dateFormat);
        

        $sql ="SELECT * FROM direction WHERE id_direction=$id_direction";
        $resultDir = mysqli_query($conn, $sql);
        $rowDir = mysqli_fetch_assoc($resultDir);
        $sigle = $rowDir['sigle_direction'];
        $lieu_emission = htmlspecialchars($rowDir['lieu_emission']);
        $typeDirection = htmlspecialchars($rowDir['type_direction']);
        $nomDirection = htmlspecialchars($rowDir['nom_direction']);

        include '../generate_fichier/generate_insert_controle.php';
        date_default_timezone_set('Indian/Antananarivo');
        $heure_actuelle = date('H:i:s');
        //renommer l'heure
        $heureExacte_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $heure_actuelle);
        //pj_DOM
        if (($_FILES['pj_dom']) && $_FILES['pj_dom']['error'] == UPLOAD_ERR_OK) {
            $numDom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $num_domiciliation);
            $uploadDir = '../upload/';
            $fileName_DOM = "SCAN_DOM_" .$numDom_cleaned.$heureExacte_cleaned.".". pathinfo($_FILES['pj_dom']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_DOM = $uploadDir . $fileName_DOM;
            $sql = "UPDATE `data_cc` SET `pj_domiciliation_pv`='$uploadPath_DOM' WHERE id_data_cc='$id_data'";
            $result = mysqli_query($conn, $sql);
        }
        $sql = "UPDATE `data_cc` SET 
            `num_domiciliation`=?,
            `date_dom`=?,
            `date_cc`=?
            WHERE id_data_cc=?";

        // Préparation de la requête
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            die('Erreur de préparation de la requête: ' . mysqli_error($conn));
        }

        // Vérification du nombre de paramètres (23 types attendus)
        $bind_result = mysqli_stmt_bind_param($stmt, 'sssi',     // string
            $num_domiciliation,      // string
            $date_dom,   // string
            $dateInsert,   // string
            $id_data                // int
        );

        if ($bind_result === false) {
            die('Erreur de liaison des paramètres: ' . mysqli_error($conn));
        }

        // Exécution de la requête
        $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    insertLogs($conn, $userID, $activite);
                    $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: https://cdc.minesmada.org/view_user/pv_controle/detail.php?id=" . $id_data);
                    exit();
                } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
            
}else{

}

    ?>