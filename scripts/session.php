<?php
        // Définissez le délai d'expiration de la session à 5 minutes (300 secondes)
        ini_set('session.gc_maxlifetime', 1800);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        
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
        $email_confirm = $rowUsers['email_confirme'];
        $status_condition = $rowUsers['status_condition'];
        $status_politique = $rowUsers['status_politique']; 
        $date_acceptation = date('d/m/Y', strtotime($rowUsers['date_acceptation']));
        $id_direction = $rowUsers['id_direction'];
        $fonctionUsers = $rowUsers['fonction'];
        $nom_user= $rowUsers['nom_user'];
        $num_userID=$rowUsers['id_user'];
        $prenom_user = $rowUsers['prenom_user'];
        $code_fonction = $rowUsers['code_fonction'];
        $image_url = $rowUsers['photo_profil'];
        $_SESSION['id_direction'] = $rowUsers['id_direction'];

        $user_id=$userID;
        $stmt = $conn->prepare("SELECT id FROM sessions_actives WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
        // Si l'utilisateur a déjà une session active, mettez simplement à jour la date
        $stmt = $conn->prepare("UPDATE sessions_actives SET date_derniere_activite = NOW() WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        } else {
        // Sinon, insérez une nouvelle entrée pour cette session
        $session_id = session_id();
        $stmt = $conn->prepare("INSERT INTO sessions_actives (user_id, session_id) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $session_id);
        }
        $stmt->execute();
        $stmt->close();

        $conn->query("DELETE FROM sessions_actives WHERE date_derniere_activite < (NOW() - INTERVAL 15 MINUTE)");

        // switch ($groupeID) {
        //     case 2:
        //         header("Location: ../view/home_admin.php");
        //         exit;
        // }



        // $sql = "SELECT * FROM societe";

        // $result = $conn->query($sql);
        ?>