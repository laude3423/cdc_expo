<?php
        // Définissez le délai d'expiration de la session à 5 minutes (300 secondes)
        ini_set('session.gc_maxlifetime', 1800);
        session_start();

        
        // Configurez le délai d'expiration de la session sur chaque nouvelle requête
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            session_unset();     // Détruisez toutes les données de session
            session_destroy();   // Détruisez la session elle-même
            // Rechargez la page pour rediriger l'utilisateur vers la page de connexion
            header("Location: ".$_SERVER['PHP_SELF']);
            exit(); // Assurez-vous de terminer le script après la redirection
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // Mettez à jour le timestamp de dernière activité

        // Connexion à la base de données
        include('db_connect.php');
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            header('Location: https://cdc.minesmada.org/index.php');
            exit;
        }

        // Récupération de l'id_groupe
        $userID = $_SESSION['ID_user'];
        $requeteUsers = $conn->prepare('SELECT * FROM users WHERE id_user = ?');
        $requeteUsers->bind_param('i', $userID);
        $requeteUsers->execute();
        $resultatUsers = $requeteUsers->get_result();
        $rowUsers = $resultatUsers->fetch_assoc();
        $groupeID = $rowUsers['id_groupe'];
        $status_user = $rowUsers['status_user'];
        $status_condition = $rowUsers['status_condition'];
        $status_politique = $rowUsers['status_politique']; 
        $date_acceptation = date('d/m/Y', strtotime($rowUsers['date_acceptation']));
        $id_direction = $rowUsers['id_direction'];
        $_SESSION['id_direction'] = $rowUsers['id_direction'];

        // switch ($groupeID) {
        //     case 2:
        //         header("Location: ../view/home_admin.php");
        //         exit;
        // }



        // $sql = "SELECT * FROM societe";

        // $result = $conn->query($sql);
        ?>