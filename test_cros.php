<?php
// URL du PDF à récupérer
$pdfUrl = 'https://lp1.minesmada.org/lp_QR_PDF/MMRS-SG-DIR-A-2024-PIM-I-01007_QR.pdf';

// if (isset($_GET['url'])) {
//     $pdfUrl = $_GET['url'];
// Définir les en-têtes CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/pdf");

// // Obtenir le contenu du PDF depuis l'URL
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $pdfUrl);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Désactiver la vérification SSL si nécessaire
// $pdfContent = curl_exec($ch);
// curl_close($ch);

// // Vérifier si la récupération a réussi
// if ($pdfContent === false) {
//     header("HTTP/1.1 500 Internal Server Error");
//     echo "Erreur lors de la récupération du PDF.";
//     exit;
// }

// // Afficher le contenu du PDF
// echo $pdfContent;
// }

// Récupérer le contenu du PDF
$response = file_get_contents($pdfUrl);

if ($response !== false) {
    // Définir les en-têtes pour l'affichage du PDF
    header('Content-Disposition: inline; filename="' . basename($pdfUrl) . '"');
    header('Content-Length: ' . strlen($response));

    // Envoyer le contenu du PDF
    echo $response;
} else {
    // Envoyer une réponse 404 si le fichier n'est pas trouvé
    http_response_code(404);
    echo "PDF not found.";
}
?>
