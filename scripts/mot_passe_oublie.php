<?php
ini_set('session.gc_maxlifetime', 1000);
session_start();
// Connexion à la base de données
include('./db_connect.php');
require '../vendor/autoload.php';
// Inclure la bibliothèque PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $destinataire = $_POST["mail_user"];

    
    // Vérification si l'adresse e-mail existe déjà
    $check_query = "SELECT COUNT(*) AS count FROM users WHERE mail_user = '$destinataire'";
    $check_result = $conn->query($check_query);
    $row = $check_result->fetch_assoc();
    $email_count = $row['count'];
    

    if ($email_count > 0) {
        $nombreAleatoire = mt_rand(100000, 999999);
        $mail = new PHPMailer(true);

        try {
            // Paramètres du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'mail.minesmada.org'; // Adresse du serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@minesmada.org'; // Votre adresse e-mail
            $mail->Password = 'test@123'; // Votre mot de passe e-mail
            $mail->SMTPSecure = 'tls'; // Protocole de sécurité
            $mail->Port = 587; // Port SMTP
        
            // Destinataire
            $mail->setFrom('no-reply@minesmada.org', 'lp1.minesmada.org');
            $mail->addAddress($destinataire, 'Nom du destinataire');
        
            // Contenu de l'e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Code de reinitialisation de mots de passe';
            $mail->Body    = 'Bonjour, <br><br>
            Pour procéder à la réinitialisation du mots de passe, veuillez utiliser le code de réinitialisation suivant :<br><br>

            Code de réinitialisation : '.$nombreAleatoire.'<br><br>
            Cordialement.
            ' ;
        
            // Envoyer l'e-mail
            $mail->send();
            echo 'L\'e-mail a été envoyé avec succès.';
            $_SESSION['code_renew'] = $nombreAleatoire;
            $_SESSION['destinataire'] = $destinataire;
            $_SESSION['success_message'] = "Un code de réinitialisation a été envoyé à votre adresse e-mail: $destinataire";
            header("Location: renew_mot_de_passe.php?success=true");
        } catch (Exception $e) {
            echo 'Erreur lors de l\'envoi de l\'e-mail : ', $mail->ErrorInfo;
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
                        <h5 class="card-title text-center mb-5 fw-light fs-5">Réinitialisation du mots de passe.</h5>
                        <form method="post" action="" class="needs-validation">
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

                                <!-- <div class="col-md-5">
                            <label for="mtricule" class="form-label">Code de réinitialisation </label>
                            <input type="number" name="matricule" id="matricule" class="form-control" required>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un matricule.
                            </div>
                        </div> -->

                            </div>
                            <hr class="my-4">


                            <button class="w-100 btn btn-dark" type="submit">Envoyer le code de
                                reinitialisation.</button>
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