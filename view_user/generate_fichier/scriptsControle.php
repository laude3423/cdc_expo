<?php
require_once('../../scripts/db_connect.php');

if(isset($_GET['id_data_cc'])) {
    $id = $_GET['id_data_cc'];
    $sql = "SELECT lien_pv_controle FROM data_cc WHERE id_data_cc=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(!empty($row['lien_pv_controle'])) {
        $lien_pv_controle = $row["lien_pv_controle"];
        $lien_pv_scan = str_replace('_QR.pdf', '.pdf', $lien_pv_controle); 
        $nom_fichier = basename($lien_pv_scan); 
        if (file_exists($lien_pv_scan)) {
            // Définition du type de contenu
            header('Content-Type: application/pdf');
            
            // Téléchargement forcé du fichier
            header('Content-Disposition: inline; filename="' . $nom_fichier . '"');

            // Lire le fichier et le retourner en sortie
            readfile($lien_pv_scan);
            exit;
        } else {
            echo "Impossible de trouver le fichier";
        }
    }
}
?>