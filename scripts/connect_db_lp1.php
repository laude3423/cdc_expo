<?php 
// Connexion à la base de données
$servername = "localhost";
$username = "mrrs";
$password = "Mrrs@123*";
$dbname = "lp1_db";

$conn_lp1 = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn_lp1->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn_lp1->connect_error);
}
?>