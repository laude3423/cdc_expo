<?php
require_once('../../scripts/db_connect.php');
require_once '../../vendor/autoload.php';
use \setasign\Fpdi\Tcpdf\Fpdi;

if (isset($_GET['id_data_cc'])) {
    $id = $_GET['id_data_cc'];
    $sql = "SELECT num_cc, scan_controle, scan_scellage FROM data_cc WHERE id_data_cc=$id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!empty($row['scan_controle'])) {
        $num_cc = $row['num_cc'];
        $num_cc = preg_replace('/[^a-zA-Z0-9]/', '-', $num_cc);
        $lien_pv_controle = $row["scan_controle"];
        $lien_pv_scellage = $row["scan_scellage"];

        $pdf = new Fpdi();
        ob_start(); // Démarre la mise en mémoire tampon

        // Tester le premier fichier
        if (file_exists($lien_pv_controle)) {
            try {
                $pdf->setSourceFile($lien_pv_controle);
                $tplIdx = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx, 0, 0, 210);
                error_log('Fichier de contrôle traité.');
            } catch (Exception $e) {
                error_log("Erreur d'importation de contrôle : " . $e->getMessage());
                // Ajouter une page blanche en cas d'erreur pour voir si le reste fonctionne
                $pdf->AddPage();
                $pdf->Cell(0, 10, 'Erreur lors de l\'importation de scan_controle', 0, 1, 'C');
            }
        } else {
            error_log("Fichier de contrôle introuvable.");
        }

        // Tester le deuxième fichier
        if (file_exists($lien_pv_scellage)) {
            try {
                $pdf->setSourceFile($lien_pv_scellage);
                $tplIdx = $pdf->importPage(1);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx, 0, 0, 210);
                error_log('Fichier de scellage traité.');
            } catch (Exception $e) {
                error_log("Erreur d'importation de scellage : " . $e->getMessage());
                // Ajouter une page blanche en cas d'erreur pour voir si le reste fonctionne
                $pdf->AddPage();
                $pdf->Cell(0, 10, 'Erreur lors de l\'importation de scan_scellage', 0, 1, 'C');
            }
        } else {
            error_log("Fichier de scellage introuvable.");
        }

        ob_end_clean(); // Nettoie le buffer

        // Génère le fichier PDF
        $pdf->Output("SCAN_".$num_cc . '.pdf', 'D');
    } else {
        echo 'Aucun scan correspondant !';
    }
}