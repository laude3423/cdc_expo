<?php if ($status_user != '1') {
            // Redirigez l'utilisateur ou affichez un message d'erreur pour accès refusé
            header("Location: ../view/page_attente.php");
            exit;
        }
?>
<?php if ($status_condition != '1') {
            // Redirigez l'utilisateur ou affichez un message d'erreur pour accès refusé
            header("Location: ../view/condition_utilisation.php");
            exit;
        }
?>