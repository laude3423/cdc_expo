<?php
// Connexion à la base de données
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
// Création d'une nouvelle instance de PHPMailer
$mail = new PHPMailer(true);

include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_user = $_POST["nom_user"];
    $prenom_user = $_POST["prenom_user"];
    $mail_user = $_POST["mail_user"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $contact_user = $_POST["contact_user"];
    $direction = $_POST["direction"];
    $matricule = $_POST["matricule"];
    $users = $_POST["fonction"];
    $fonction = "";
    if(($users =="Directeur")||($users=="Directeur des Exportations")){
        $fonction = "A";
    }else if (($users =="Chef de service")||($users=="Chef de Division Exportation")||($users=="Chef de Section Scellage")){
        $fonction = "B";
    }else{
        $fonction = "C";
    }


    //envoie email
    
    
    // Vérification si l'adresse e-mail existe déjà
    $check_query = "SELECT COUNT(*) AS count FROM users WHERE mail_user = '$mail_user'";
    $check_result = $conn->query($check_query);
    $row = $check_result->fetch_assoc();
    $email_count = $row['count'];
    $validation = 0;
    $groupe=1;
    if($direction==17){
     $groupe=3;   
    }else if($direction=18){
      $groupe=4;    
    }
    $id_user='';

    if ($email_count > 0) {
        echo '<script>alert("L\'adresse e-mail existe déjà dans la base de données.");</script>';
    } else {
        // Vérification de la case à cocher
        $validation = isset($_POST["validation"]) ? 1 : 0;

        $insert_query = "INSERT INTO users (nom_user, prenom_user, mail_user, phone_user, password_user, matricule_user, status_user, id_direction, id_groupe, email_confirme, code_fonction, fonction) VALUES ('$nom_user', '$prenom_user', '$mail_user', '$contact_user', '$mot_de_passe','$matricule', '$validation', '$direction', '$groupe', '0', '$fonction', '$users')";
        
        if ($conn->query($insert_query) === TRUE) {
            $id_user = $conn->insert_id;

            $mail->isSMTP();
            $mail->Host = 'mail.minesmada.org'; // Adresse du serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'no-reply@minesmada.org'; // Votre adresse e-mail
            $mail->Password = 'test@123'; // Votre mot de passe e-mail
            $mail->SMTPSecure = 'tls'; // Protocole de sécurité
            $mail->Port = 587; // Port SMTP


                // Destinataire
            $mail->setFrom('no-reply@minesmada.org', 'lp1.minesmada.org');
            $mail->addAddress($mail_user, 'Nom du destinataire');
                
                    // Contenu de l'e-mail
            $id_user_hashed = hash('sha256', $id_user);

            $mail->isHTML(true);
            $mail->Subject = "=?UTF-8?B?" . base64_encode("Vérification email") . "?=";
            $mail->Body = 'Bonjour,<br><br>
                Pour vérifier que votre email existe, veuillez cliquer sur le lien suivant :<br><br>

            <a href="https://cdc.minesmada.org/scripts/confirme_email.php?id=' .$id_user_hashed. '">Cliquer ici</a><br><br>
                    Cordialement.';


                // Envoi du message
            if(!$mail->send()) {
                    echo 'Erreur lors de l\'envoi du message : ' . $mail->ErrorInfo;
            } else {
                    echo 'Message envoyé avec succès !';
            }
            $_SESSION['toast_message'] = "Utilisateur enregistré avec succès.";
            header("Location: ../scripts/indication.php");
            exit;
        } else {
            echo "Erreur lors de l'insertion de l'utilisateur : " . $conn->error;
        }
         
    }


    
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inscription</title>
    <!-- Intégration de Bootstrap CSS -->
    <link rel="icon" href="../logo/favicon.ico">
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
    <style>
    .container {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .btn {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .dropdown-item {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .form-control {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .form-select {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .h4 {
        font-size: 20px;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .modal {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .modal-dialog {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
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
                        <h5 class="card-title text-center mb-5 fw-light fs-5">Inscrivez-vous !</h5>
                        <form method="post" action="" class="needs-validation" onsubmit="return validatePassword();">
                            <div class="row g-3 form-floating mb-3">
                                <div class="col-sm-6">
                                    <label for="nom_user" class="form-label">Nom</label>
                                    <input type="text" name="nom_user" id="nom_user" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Veuillez entrer un nom d'utilisateur.
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="prenom_user" class="form-label">Prenoms</label>
                                    <input type="text" name="prenom_user" id="prenom_user" class="form-control"
                                        required>
                                    <div class="invalid-feedback">
                                        Veuillez entrer un Prenom(s) d'utilisateur.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="mail_user" class="form-label">Adresse e-mail<span
                                            class="text-body-secondary"> * </span></label>
                                    <input type="email" name="mail_user" id="mail_user" class="form-control" required
                                        placeholder="you@example.com">
                                    <div class="invalid-feedback">
                                        Please enter a valid email address for shipping updates.
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="mot_de_passe" class="form-label">Mots de Passe</label>
                                    <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control"
                                        required>
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
                                <div class="col-12">
                                    <label for="contact_user" class="form-label">Telephone <span
                                            class="text-body-secondary">*</span></label>
                                    <input type="text" name="contact_user" id="contact_user" class="form-control"
                                        required pattern="[0-9]+"
                                        title="Veuillez entrer uniquement des chiffres sans espace">
                                </div>
                                <div class="col-sm-6">
                                    <label for="matricule" class="form-label">Matricule:</label>
                                    <input type="number" name="matricule" id="matricule" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Veuillez sélectionner un matricule.
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="fonction" class="form-label">Fonction:</label>
                                    <select class="form-select" name="fonction" id="fonction" required>
                                        <option value="">Séléctionner ...</option>
                                        <option value="Directeur">Directeur</option>
                                        <option value="Directeur des Exportations">Directeur des Exportations</option>
                                        <option value="Chef de service">Chef de service</option>
                                        <option value="Chef de Division Exportation">Chef de Division Exportation
                                        </option>
                                        <option value="Chef de Section Scellage">Chef de Section Scellage</option>
                                        <option value="Secrétaire de Direction">Secrétaire de Direction</option>
                                        <option value="Opérateur de saisie">Opérateur de saisie</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Veuillez sélectionner un matricule.
                                    </div>
                                </div>


                                <div class="col-12">
                                    <label for="societe">Direction :</label>
                                    <select class="form-control" name="direction" id="direction" required>
                                        <?php
                            include('db_connect.php');
                            $requete = $conn->prepare("SELECT * FROM direction WHERE sigle_direction IN ('DIR.ANSAND', 'DIR.A', 'DR.AA', 'DIR.M', 'DR.SAVA', 'DIR.TO', 'DR.VAK', 'DR.DIANA', 'GU','PCMAII')");
                            $requete->execute();
                            $resultat = $requete->get_result();

                            // Parcourir les résultats et afficher les options du menu déroulant
                            echo '<option value=""> Selectionner une direction ... </option>';
                            while ($row = $resultat->fetch_assoc()) {
                                echo '<option value="' . $row['id_direction'] . '">' . $row['nom_direction'] . '</option>';
                            }

                            $requete->close();
                            $conn->close();
                            ?>
                                    </select>
                                </div>
                            </div>
                            <hr class="my-4">


                            <button class="w-100 btn btn-dark" type="submit">S'enregister</button>
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