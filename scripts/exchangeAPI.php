<?php
$tauxAPI=0;
$apiKey = "238523a69a0bff70e8cf805f";
$endpoint = "https://v6.exchangerate-api.com/v6/$apiKey/latest/USD"; // Base en USD

// Initialiser cURL
$curl = curl_init($endpoint);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Exécuter la requête
$response = curl_exec($curl);
curl_close($curl);

// Traiter la réponse
$data = json_decode($response, true);

if (isset($data['conversion_rates']['MGA'])) {
    $tauxAPI = $data['conversion_rates']['MGA'];
} else {
}
?>