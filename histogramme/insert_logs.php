<?php 


function insertLogs($conn, $user_id, $action){
        function getUserIP() {
    // Vérifie si l'IP passe par un proxy ou une passerelle
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // Pour les adresses IP via un proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
                // Adresse IP réelle de l'utilisateur
                $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
        }

        // Utilisation de la fonction pour récupérer l'IP
        $user_ip = getUserIP();
        $stmt = $conn->prepare("INSERT INTO `logs`( `user_id`, `action`, `adressIP`) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $action, $user_ip);
        $stmt->execute();
}
?>