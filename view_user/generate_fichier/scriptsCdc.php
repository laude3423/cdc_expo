<?php
require_once('../../scripts/db_connect.php');
require_once '../../vendor/autoload.php';
use \setasign\Fpdi\Tcpdf\Fpdi; // Utilise FPDI comme une extension de TCPDF

$lien_controle = '';
$lien_scellage = '';
$lien_cc = '';

if(isset($_GET['id_data_cc'])) {
    $id = $_GET['id_data_cc'];
    $sql = "SELECT lien_pv_controle,num_cc, lien_cc, lien_pv_scellage FROM data_cc WHERE id_data_cc=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(!empty($row['lien_cc'])) {
        $lien_pv_controle = $row["lien_pv_controle"];
        $lien_pv_scellage = $row["lien_pv_scellage"];
        $lien_cc = $row["lien_cc"];
        $num_cc = $row["num_cc"];
        $num_cc=preg_replace('/[^a-zA-Z0-9]/', '-', $num_cc);
        // Remplacement de '_QR.pdf' par '.pdf' dans les liens
        $lien_controle = str_replace('_QR.pdf', '.pdf', $lien_pv_controle);
        $lien_scellage = str_replace('_QR.pdf', '.pdf', $lien_pv_scellage); 
        $lien_cc = str_replace('_QR.pdf', '.pdf', $lien_cc);
        
        // Créer un nouvel objet PDF avec FPDI en tant qu'extension de TCPDF
        $pdf = new Fpdi();

        // Ajouter les pages PDF au document
        if (file_exists($lien_cc)) {
            $pdf->setSourceFile($lien_cc);
            $tplIdx = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, 210);
        }
        
        if (file_exists($lien_controle)) {
            $pdf->setSourceFile($lien_controle);
            $tplIdx = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, 210);
        }
        
        if (file_exists($lien_scellage)) {
            $pdf->setSourceFile($lien_scellage);
            $tplIdx = $pdf->importPage(1);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx, 0, 0, 210);
        }

        // Générer et télécharger le document PDF
        $pdf->Output($num_cc.'.pdf', 'D');
        exit;
    }
}
?>