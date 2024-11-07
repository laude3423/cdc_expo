<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Lire les données JSON envoyées depuis la requête
$data = json_decode(file_get_contents('php://input'), true);
$image_data = $data['image'] ?? '';

if (empty($image_data)) {
    echo json_encode(['error' => 'Aucune image fournie.']);
    exit; // Arrêter l'exécution si aucune image n'est fournie
}

// Enlever le préfixe "data:image/jpeg;base64,"
$image_data = str_replace('data:image/jpeg;base64,', '', $image_data);
$image_data = str_replace(' ', '+', $image_data);
$file_data = base64_decode($image_data);

// Chemin de l'image capturée
$image_file_path = '../image/captured_image.jpg';
if (file_put_contents($image_file_path, $file_data) === false) {
    echo json_encode(['error' => 'Erreur lors de l\'enregistrement de l\'image capturée']);
    exit;
}

// Fonction pour comparer deux images de visages
function compareFaces($image_path1, $image_path2) {
    // Paramètres API Face++
    $api_key = "He7AHBExeM2gouTlbiGW5aB5inic3f1a"; // Remplacez par votre clé API
    $api_secret = "jjS-FdDtjCMz5X8iGgC31ukc-RV2e4lo"; // Remplacez par votre clé secrète
    $image_file1 = new CURLFile($image_path1);
    $image_file2 = new CURLFile($image_path2);

    $url = "https://api-us.faceplusplus.com/facepp/v3/compare";

    // Paramètres à envoyer avec la requête
    $data = array(
        'api_key' => $api_key,
        'api_secret' => $api_secret,
        'image_file1' => $image_file1,
        'image_file2' => $image_file2
    );

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête
    $response = curl_exec($ch);

    // Vérification des erreurs cURL
    if (curl_errno($ch)) {
        return ['error' => 'Erreur cURL : ' . curl_error($ch)];
    }

    // Vérifiez si la réponse est JSON valide
    $result = json_decode($response, true);
    curl_close($ch);

    // Retourner le résultat
    return $result;
}

// Comparer les visages capturés avec ceux enregistrés
$stored_image_path = '../image/laude.jpg'; // Chemin de l'image stockée pour comparaison
$comparisonResult = compareFaces($image_file_path, $stored_image_path);

// Vérifier si la comparaison a réussi
if (isset($comparisonResult['error_message'])) {
    echo json_encode(['error' => 'Erreur lors de la comparaison des visages : ' . $comparisonResult['error_message']]);
    exit;
}

// Utiliser le score de similarité pour déterminer si les visages correspondent
$similarity = $comparisonResult['confidence'] ?? 0;

if ($similarity >= 80) { // Vous pouvez ajuster ce seuil
    $message = "Le visage correspond à l'image enregistrée avec une similarité de $similarity%.";
} else {
    $message = "Le visage ne correspond pas à l'image enregistrée. Similarité : $similarity%.";
}

// Retourner le message et les résultats de la comparaison
echo json_encode([
    'message' => $message,
    'similarity' => $similarity,
    'comparisonResult' => $comparisonResult,
]);
?>