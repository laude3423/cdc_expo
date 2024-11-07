<?php 
require_once('../scripts/db_connect.php');
require('../scripts/session.php');

$user_id = $userID; // Assurez-vous que l'utilisateur est connecté et que l'ID est stocké


$stmt = $mysqli->prepare("SELECT id FROM sessions_actives WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Si l'utilisateur a déjà une session active, mettez simplement à jour la date
    $stmt = $mysqli->prepare("UPDATE sessions_actives SET date_derniere_activite = NOW() WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
} else {
    // Sinon, insérez une nouvelle entrée pour cette session
    $session_id = session_id();
    $stmt = $mysqli->prepare("INSERT INTO sessions_actives (user_id, session_id) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $session_id);
}
$stmt->execute();
$stmt->close();

// Supprimer les sessions inactives depuis plus de 15 minutes
$mysqli->query("DELETE FROM sessions_actives WHERE date_derniere_activite < (NOW() - INTERVAL 15 MINUTE)");

$result = $mysqli->query("SELECT COUNT(*) AS nb_users_connectes FROM sessions_actives");
$row = $result->fetch_assoc();
echo "Nombre d'utilisateurs connectés : " . $row['nb_users_connectes'];