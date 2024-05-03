 <?php

include('db_connect.php');

session_start();

 if (isset($_GET['id'])) {
        $id_user = $_GET['id'];
        $sql="UPDATE `users` SET `confirme_email`='1' WHERE id_user=$id_user";
        $result = mysqli_query($conn, $sql);
        if ($result) {
                $_SESSION['toast_message'] = "E-mail bien confirmé.";
                header("Location: ../index.php");
                exit;
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
 }