<?php
require_once('../../scripts/db_connect.php');
require_once '../../vendor/autoload.php';
use \setasign\Fpdi\Tcpdf\Fpdi; // Utilise FPDI comme une extension de TCPDF

// Démarrer la capture de sortie
ob_start();

$lien_controle = '';
$lien_scellage = '';
$lien_cc = '';

if (isset($_GET['id_data_cc'])) {
    $id = $_GET['id_data_cc'];
    $sql = "SELECT num_cc, scan_controle, scan_scellage FROM data_cc WHERE id_data_cc=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (!empty($row['scan_scellage'])) {
        $num_cc = $row['num_cc'];
        $num_cc=preg_replace('/[^a-zA-Z0-9]/', '-', $num_cc);
        $lien_pv_controle = $row["scan_controle"];
        $lien_pv_scellage = $row["scan_scellage"];
        // Remplacement de '_QR.pdf' par '.pdf' dans les liens
        // Créer un nouvel objet PDF avec FPDI en tant qu'extension de TCPDF
        $pdf = new Fpdi();

        // Ajouter les pages PDF au document
        if (file_exists($lien_pv_controle)) {
            $pdf->setSourceFile($lien_pv_controle);
            $tplIdx = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, 210);
        }

        if (file_exists($lien_pv_scellage)) {
            $pdf->setSourceFile($lien_pv_scellage);
            $tplIdx = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, 210);
        }

        // Nettoyer la capture de sortie avant de générer le PDF
        ob_end_clean();

        // Générer et télécharger le document PDF
        $pdf->Output($num_cc . '.pdf', 'D');
        exit;
    } else {
        // Nettoyer la capture de sortie avant d'afficher un message
        ob_end_clean();
        echo 'Aucun scan correspondant !';
    }
}
?>