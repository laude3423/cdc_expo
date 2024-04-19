<?php
        // Définissez le délai d'expiration de la session à 5 minutes (300 secondes)
        ini_set('session.gc_maxlifetime', 1000);
        
        session_start();

        
        // Configurez le délai d'expiration de la session sur chaque nouvelle requête
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1000)) {
            session_unset();     // Détruisez toutes les données de session
            session_destroy();   // Détruisez la session elle-même
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // Mettez à jour le timestamp de dernière activité

        // Connexion à la base de données
        include('db_connect.php');
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header('Location: ../index.php');
            exit;
        }

        // Récupération de l'id_groupe
        $userID = $_SESSION['ID_user'];
        $requeteUsers = $conn->prepare('SELECT id_groupe FROM users WHERE id_user = ?');
        $requeteUsers->bind_param('i', $userID);
        $requeteUsers->execute();
        $resultatUsers = $requeteUsers->get_result();
        $rowUsers = $resultatUsers->fetch_assoc();
        $groupeID = $rowUsers['id_groupe'];
        
        switch ($groupeID) {
            case 1:
                header("Location: ../view/demande_user.php");
                exit;
            case 3:
                header("Location: ../view/demande_user.php");
                exit;
        }

        // $sql = "SELECT * FROM societe";

        // $result = $conn->query($sql);
        ?>