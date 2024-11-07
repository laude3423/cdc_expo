<?php
include './db_connect.php';
function logAction($userId, $action, $conn) {
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $action);
    
    $stmt->execute();
}
?>