<?php
require_once('../scripts/db_connect.php');
require('../scripts/session.php');

 // Log des données brutes reçues

$data = json_decode(file_get_contents('php://input'), true);

if ($data !== null && isset($data['user_id']) && isset($data['video_id'])) {
    $user_id = $data['user_id'];
    $video_id = $data['video_id'];

    $query = "INSERT INTO video_views (video_id, id_user) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $video_id, $user_id);
    $stmt->execute();
} else {
    file_put_contents('log.txt', "Invalid input" . PHP_EOL, FILE_APPEND);  // Log des entrées non valides
}
?>