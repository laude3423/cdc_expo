<?php
ini_set('session.gc_maxlifetime', 1000);
session_start();
// Connexion à la base de données
include('./db_connect.php');

$user = isset($_SESSION['destinataire']) ? $_SESSION['destinataire'] : null;
// $activite_user = 'Reinitialisation du mots de passe de ' . $user; 
// $HInsertQuery = "INSERT INTO historique_users (activite_user, ip_user) VALUES ('$activite_user', '$ip_address')";
// $conn->query($HInsertQuery);
// $code_renew = $_SESSION['code_renew'];
if(isset($_SESSION['success_message']) && isset($_GET['success']) && $_GET['success'] == "true") {
    // Affichez le message de succès
    echo "<div style='background-color: #dff0d8; color: #3c763d; padding: 10px; margin-bottom: 10px;'>".$_SESSION['success_message']."</div>";
    
    // Supprimez le message de succès de la session pour qu'il ne soit affiché qu'une seule fois
    unset($_SESSION['success_message']);
}

    $code_renew = intval($_SESSION['code_renew']);
    $destinataire = $_SESSION['destinataire'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail_user = $_POST["mail_user"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $code = intval($_POST["code"]);
    
    // Vérification si l'adresse e-mail existe déjà
    $check_query = "SELECT COUNT(*) AS count FROM users WHERE mail_user = '$mail_user'";
    $check_result = $conn->query($check_query);
    $row = $check_result->fetch_assoc();
    $email_count = $row['count'];

    if ($email_count > 0) {
        if($code === $code_renew && $mail_user === $destinataire) {
            $check_user = "SELECT id_user FROM users WHERE mail_user = '$mail_user'";
            $check_result_user = $conn->query($check_user);
            $row_user = $check_result_user->fetch_assoc();
            $id_user = $row_user['id_user'];

            $sql_user = "UPDATE users SET password_user = ? WHERE id_user = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("si", $mot_de_passe, $id_user);
            $stmt_user->execute();

            // $activite_user_1 = 'Reinitialisation du mots de passe avec succes de ' . $destinataire; 
            // $HInsertQuery_1 = "INSERT INTO historique_users (activite_user, ip_user) VALUES ('$activite_user_1', '$ip_address')";
            // $conn->query($HInsertQuery_1);

            $_SESSION['success_message'] = "Votre mots de passe a été modifier avec succès.";
            
            header("Location: ../index.php?success=true");
        } else {
            if ($code != $code_renew) {
                $activite_user_2 = 'Le code de reinitialisation ne correspond pas lors du reinitialisation du mots de passe de ' . $destinataire; 
                $HInsertQuery_2 = "INSERT INTO historique_users (activite_user, ip_user) VALUES ('$activite_user_2', '$ip_address')";
                $conn->query($HInsertQuery_2);
                echo '<div class="text-center text-danger">Verifier votre code de réinitialisation.</div>';
            }
            if ($mail_user != $destinataire) {
                $activite_user_3 = 'L adresse mail ne correspond pas lors du reinitialisation du mots de passe avec succes de ' . $destinataire; 
                $HInsertQuery_3 = "INSERT INTO historique_users (activite_user, ip_user) VALUES ('$activite_user_3', '$ip_address')";
                $conn->query($HInsertQuery_3);
                echo '<div class="text-center text-danger">Verifier votre adresse email.</div>';
            }
            // echo "code renew" .$code_renew;
            // echo "<br>code" .$code;
            // echo "<br>mail_user:" .$mail_user;
            // echo "<br>destinataire:" .$destinataire;
            // $_SESSION['success_message'] = "Le code ne correspond pas .";
        }
    } else {
        // Vérification de la case à cocher
        echo '<script>alert("L\'adresse e-mail n\'existe pas dans la base de données.");</script>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Réinitialisation de mots de passe.</title>
    <!-- Intégration de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        background-color: #f0f0f0;
        /* Couleur de fond */
    }

    .centered-container {
        text-align: center;
    }

    .rounded-border {
        background-color: #fff;
        /* Couleur de fond du cadre */
        border-radius: 10px;
        /* Bordure arrondie */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        /* Ombre légère */
        padding: 20px;
        width: 100%;
        max-width: 600px;
        /* Largeur maximale du cadre */
    }

    body {
        /* background: #007bff;
        background: linear-gradient(to right, #0062E6, #33AEFF); */
        background: 0 0 url('../logo/bg_repeat.dd939d5….webp') repeat;
        background-image: url('../logo/bg_repeat.dd939d5….webp');
        background-position-x: 0px;
        background-position-y: 0px;
        background-size: initial;
        background-repeat: repeat;
        background-attachment: initial;
        background-origin: initial;
        background-clip: initial;
        background-color: initial;
        padding: 24px;
        padding-top: 24px;
        padding-right: 24px;
        padding-bottom: 24px;
        padding-left: 24px;
    }

    .card-img-left {
        width: 45%;
        /* Link to your background image using in the property below! */
        background: scroll center url('../logo/cdc.jpg');
        background-size: cover;
    }

    .btn-login {
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
    }

    .btn-google {
        color: white !important;
        background-color: #ea4335;
    }

    .btn-facebook {
        color: white !important;
        background-color: #3b5998;
    }

    img {
        display: block;
        margin: 20px auto 0;
    }
    </style>
    <script>
    function validatePassword() {
        var password = document.getElementById("mot_de_passe").value;
        var confirmPassword = document.getElementById("confirmation_mot_de_passe").value;
        if (password != confirmPassword) {
            alert("Le mot de passe et la confirmation du mot de passe ne correspondent pas.");
            return false;
        }
        return true;
    }
    </script>
</head>
<!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->

<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-9 mx-auto">
                <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
                    <div class="card-img-left d-none d-md-flex">
                        <img src="../logo/logo.png" alt="logo MIM" width="100" height="100">
                        <!-- Background image for card set in CSS! -->
                    </div>
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title text-center mb-5 fw-light fs-5">Réinitialisation de mots de passe.</h5>
                        <form method="post" action="" class="needs-validation" onsubmit="return validatePassword();">
                            <div class="row g-3 form-floating mb-3">


                                <div class="col-12">
                                    <label for="mail_user" class="form-label">Adresse e-mail<span
                                            class="text-body-secondary"> * </span></label>
                                    <input type="email" name="mail_user" id="mail_user" class="form-control" required
                                        placeholder="you@example.com">
                                    <div class="invalid-feedback">
                                        Please enter a valid email address for shipping updates.
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label for="mot_de_passe" class="form-label">Mots de Passe</label>
                                        <input type="password" name="mot_de_passe" id="mot_de_passe"
                                            class="form-control" required>
                                        <div class="invalid-feedback">
                                            Please enter your Password.
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <label for="confirmation_mot_de_passe" class="form-label">Confirmation mots de
                                            Passe</label>
                                        <input type="password" name="confirmation_mot_de_passe"
                                            id="confirmation_mot_de_passe" class="form-control" required>
                                        <div class="invalid-feedback">
                                            Please enter your Password.
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label for="mtricule" class="form-label">Code de réinitialisation </label>
                                    <input type="number" name="code" id="code" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Veuillez entrer le code de reinitialisation.
                                    </div>
                                </div>

                            </div>
                            <hr class="my-4">


                            <button class="w-100 btn btn-dark" type="submit">Réinitialiser le mot de passe.</button>
                            <hr class="my-4">
                        </form>
                        <p class="text-center mt-3">Déjà inscrit ? <a href="../index.php">Connectez-vous</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Intégration de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('.needs-validation');

        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        });
    });
    </script>
    </main>
</body>

</html>