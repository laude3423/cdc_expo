 <?php

include('db_connect.php');

session_start();

if (isset($_GET['id'])) {
    $id_user_hashed = $_GET['id'];
    
    // Recherche de l'utilisateur dans la base de données
    $sql_select = "SELECT * FROM `users` WHERE SHA2(id_user, 256) = '$id_user_hashed'";
    $result_select = mysqli_query($conn, $sql_select);
    
    if ($result_select && mysqli_num_rows($result_select) > 0) {
        // Utilisateur trouvé, mise à jour de la colonne 'confirme_email'
        $row = mysqli_fetch_assoc($result_select);
        $id_user = $row['id_user']; // Récupération de l'ID utilisateur
        $sql_update = "UPDATE `users` SET `email_confirme`='1' WHERE id_user=$id_user";
        $result_update = mysqli_query($conn, $sql_update);
        
        if ($result_update) {
            $_SESSION['toast_message'] = "E-mail bien confirmé.";
            header("Location: ../index.php");
            exit;
        } else {
            echo "Erreur lors de la mise à jour : " . mysqli_error($conn);
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
}
?>