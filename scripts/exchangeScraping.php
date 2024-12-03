<?php
$tauxScraping=0;
// URL de recherche Google pour 1 Dollar en Ariary
$url = "https://www.google.com/search?q=1+usd+to+mga";

// Initialisation de cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Suivre les redirections
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

// Exécuter la requête
$response = curl_exec($curl);
curl_close($curl);

// Vérification et extraction du taux avec un regex
if ($response && preg_match('/<span class="DFlfde SwHCTb"[^>]*>(.*?)<\/span>/', $response, $matches)) {
    $tauxScraping = $matches[1]; // Extraire le taux de conversion
} else {
}
?>