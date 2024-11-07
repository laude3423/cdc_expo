<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $id = $_POST['idval'];
        $num_visa=$_POST['num_visa'];
        $uploadDir = '../upload/';
         if (!empty($_FILES['scan_visa']['name'])) {
            $fileTmpPath = $_FILES['scan_visa']['tmp_name'];
            $fileName = $_FILES['scan_visa']['name'];
            $fileSize = $_FILES['scan_visa']['size'];
            $fileType = $_FILES['scan_visa']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('pdf');
            $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

            if (in_array($fileExtension, $allowedfileExtensions)) {
                if ($fileSize < $maxFileSize) {
                     $num_passeport = preg_replace('/[^a-zA-Z0-9]/', '-', $num_visa);
                    $fileName_VISA = "SCAN_VISA_" .$num_passeport.".".
                    pathinfo($_FILES['scan_visa']['name'], PATHINFO_EXTENSION);
                    $uploadPath_VISA = $uploadDir . $fileName_VISA;
                    //deplacement des fichier
                    if (move_uploaded_file($_FILES['scan_visa']['tmp_name'], $uploadPath_VISA)) {
                    } else {
                    echo "Erreur lors de l'upload du fichier.";
                    }
                }
            }
            $query = "UPDATE visa SET scan_visa=? WHERE id_visa=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $uploadPath_VISA, $id);
            $result = $stmt->execute();

            
            if ($result) {
                    $_SESSION['toast_message'] = "Mise à jour réussie.";
                    header("Location: https://cdc.minesmada.org/aut_visa/visa/detail.php?id=" . $id);
                    exit();
            } else {
                    echo '<div class="alert alert-danger" role="alert">Erreur lors de la modification de l\'autorisation.</div>';
            }
            $stmt->close();
        }
}  
}