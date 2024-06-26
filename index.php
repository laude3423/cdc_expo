<?php
session_start();

// Connexion à la base de données
require 'scripts/db_connect.php';


// Vrifier si l'utilisateur est déj connecté
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    redirigerVersPageAccueil();
}

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rcupérer les données du formulaire
    $adresseEmail = $_POST['adresse_email'];
    $motDePasse = $_POST['mots_de_passe'];

    // Valider les données (vous pouvez ajouter des validations supplémentaires ici)

    // Vérifier les informations de connexion dans la base de donnes
    // $conn = seConnecterBaseDeDonnees();
    $utilisateur = verifierInformationsConnexion($conn, $adresseEmail, $motDePasse);

    if ($utilisateur !== null) {
        // Connexion réussie, créer une session pour l'utilisateur
        creerSessionUtilisateur($utilisateur);

        // Rediriger en fonction de l'id_groupe de l'utilisateur
        redirigerSelonIDGroupe($utilisateur['id_groupe']);
    } else {
        // Identifiants de connexion invalides
        $messageErreur = 'Identifiants de connexion invalides';
    }

    // Fermer la connexion à la base de donnes
    fermerConnexionBaseDeDonnees($conn);
}


?>
<!DOCTYPE html>
<html>

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <style>
    body {
        background-color: #fff;
        font-family: 'Karla', sans-serif;
    }

    h1>a {
        text-decoration: none;
        color: #fff !important;
    }

    .intro-section {
        background-image: url("logo/cdc.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        padding: 75px 95px;
        min-height: 100vh;
        display: -webkit-box;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        flex-direction: column;
        color: #ffffff;
    }

    @media (max-width: 991px) {
        .intro-section {
            padding-left: 50px;
            padding-rigth: 50px;
        }
    }

    @media (max-width: 767px) {
        .intro-section {
            padding: 28px;
        }
    }

    @media (max-width: 575px) {
        .intro-section {
            min-height: auto;
        }
    }

    .brand-wrapper .logo {
        height: 35px;
    }

    @media (max-width: 767px) {
        .brand-wrapper {
            margin-bottom: 35px;
        }
    }

    .intro-content-wrapper {
        width: 410px;
        max-width: 100%;
        margin-top: auto;
        margin-bottom: auto;
    }

    .intro-content-wrapper .intro-title {
        font-size: 40px;
        font-weight: bold;
        margin-bottom: 17px;
    }

    .intro-content-wrapper .intro-text {
        font-size: 19px;
        line-height: 1.37;
    }

    .intro-content-wrapper .btn-read-more {
        background-color: #fff;
        padding: 13px 30px;
        border-radius: 0;
        font-size: 16px;
        font-weight: bold;
        color: #000;
    }

    .intro-content-wrapper .btn-read-more:hover {
        background-color: transparent;
        border: 1px solid #fff;
        color: #fff;
    }

    @media (max-width: 767px) {
        .intro-section-footer {
            margin-top: 35px;
        }
    }

    .intro-section-footer .footer-nav a {
        font-size: 20px;
        font-weight: bold;
        color: inherit;
    }

    @media (max-width: 767px) {
        .intro-section-footer .footer-nav a {
            font-size: 14px;
        }
    }

    .intro-section-footer .footer-nav a+a {
        margin-left: 30px;
    }

    .form-section {
        display: -webkit-box;
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
    }

    @media (max-width: 767px) {
        .form-section {
            padding: 35px;
        }
    }

    .login-wrapper {
        width: 300px;
        max-width: 100%;
    }

    @media (max-width: 575px) {
        .login-wrapper {
            width: 100%;
        }
    }

    .login-wrapper .form-control {
        border: 0;
        border-bottom: 1px solid #e7e7e7;
        border-radius: 0;
        font-size: 14px;
        font-weight: bold;
        padding: 15px 10px;
        margin-bottom: 7px;
    }

    .login-wrapper .form-control::-webkit-input-placeholder {
        color: #b0adad;
    }

    .login-wrapper .form-control::-moz-placeholder {
        color: #b0adad;
    }

    .login-wrapper .form-control:-ms-input-placeholder {
        color: #b0adad;
    }

    .login-wrapper .form-control::-ms-input-placeholder {
        color: #b0adad;
    }

    .login-wrapper .form-control::placeholder {
        color: #b0adad;
    }

    .login-title {
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .login-btn {
        padding: 13px 30px;
        background-color: #000;
        border-radius: 0;
        font-size: 20px;
        font-weight: bold;
        color: #fff;
    }

    .login-btn:hover {
        border: 1px solid #000;
        background-color: transparent;
        color: #000;
    }

    .forgot-password-link {
        font-size: 14px;
        color: #080808;
        text-decoration: underline;
    }

    .social-login-title {
        font-size: 15px;
        color: #919aa3;
        display: -webkit-box;
        display: flex;
        margin-bottom: 23px;
    }

    .social-login-title::before,
    .social-login-title::after {
        content: "";
        background-image: -webkit-gradient(linear, left top, left bottom, from(#f4f4f4), to(#f4f4f4));
        background-image: linear-gradient(#f4f4f4, #f4f4f4);
        -webkit-box-flex: 1;
        flex-grow: 1;
        background-size: calc(100% - 20px) 1px;
        background-repeat: no-repeat;
    }

    .social-login-title::before {
        background-position: center left;
    }

    .social-login-title::after {
        background-position: center right;
    }

    .social-login-links {
        text-align: center;
        margin-bottom: 32px;
    }

    .social-login-link img {
        width: 40px;
        height: 40px;
        -o-object-fit: contain;
        object-fit: contain;
    }

    .social-login-link+.socia-login-link {
        margin-left: 16px;
    }

    .login-wrapper-footer-text {
        font-size: 14px;
        text-align: center;
    }

    /* .wrap {
            width: 50% auto;
            overflow: hidden;
            background: #fff;
            border-radius: 5px;
            -webkit-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
            -moz-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
            box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);


            max-width: auto;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
            margin: 50px auto 0;
        } */
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
</head>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-md-7 intro-section">
            <div class="brand-wrapper image-container">
                <img src="logo/logo.png" alt="logo MIM" width="100" height="100">
                <div class="blur-background"></div>
            </div>
            <div class="intro-content-wrapper">
                <h1 class="intro-title"><br><br><br><br><br><br><br><br><br><br>Certificat de conformité!</h1>
                <p class="intro-text">Bienvenu sur cette application</p>
                <!-- <a href="#!" class="btn btn-read-more">Read more</a> -->
            </div>
            <!-- <div class="intro-section-footer">
            <na class="footer-nav">
              <a href="#!">Facebook</a>
              <a href="#!">Twitter</a>
              <a href="#!">Gmail</a>
            </na>
          </div> -->
        </div>
        <div class="col-sm-6 col-md-5 form-section">
            <div class="wrap login-wrapper mx-auto">
                <h2 class="login-title">Connectez-vous</h2>
                <?php if (isset($messageErreur)) : ?>
                <p class="error-message"><?php echo $messageErreur; ?></p>
                <?php endif; ?>
                <form class="login-form" method="POST" action="">
                    <div class="form-group mb-4">
                        <input id="inputEmail" type="email" name="adresse_email" placeholder="Email address" required=""
                            autofocus="" class="form-control rounded-pill border-0 shadow-sm px-4">
                    </div>
                    <div class="form-group mb-4">
                        <input id="inputPassword" name="mots_de_passe" type="password" placeholder="Password"
                            required="" class="form-control rounded-pill border-0 shadow-sm px-4 text-primary">
                    </div>
                    <!-- <div class="custom-control custom-checkbox mb-3">
                    <input id="customCheck1" type="checkbox" checked class="custom-control-input">
                    <label for="customCheck1" class="custom-control-label">Remember password</label>
                </div> -->
                    <button type="submit" class="btn btn-dark btn-block text-uppercase mb-2 rounded-pill shadow-sm">Se
                        connecter</button>
                </form>
                <p class="login-wrapper-footer-text">Besoin d'un compte ? <a href="scripts/inscription.php"
                        class="text-reset"> S'inscrire </a></p>
            </div>
        </div>
    </div>
</div>


</html>



<?php
function redirigerVersPageAccueil() {
    // header('Location: view/demande_user.php');
    header('Location: view/acc_user.php');
    exit;
}

// function seConnecterBaseDeDonnees() {
// 	// Connexion à la base de donnes
// 	include('scripts/db_connect.php');

//     return $conn;
// }

function fermerConnexionBaseDeDonnees($conn) {
    $conn->close();
}

function verifierInformationsConnexion($conn, $adresseEmail, $motDePasse) {
    $requete = $conn->prepare('SELECT id_user, nom_user, id_groupe FROM users WHERE mail_user = ? AND password_user = ?');
    $requete->bind_param('ss', $adresseEmail, $motDePasse);
    $requete->execute();
    $resultat = $requete->get_result();

    if ($resultat->num_rows === 1) {
        return $resultat->fetch_assoc();
    }

    return null;
}
function verifierEmail($conn, $adresseEmail) {
    $requete = $conn->prepare('SELECT id_user, nom_user, id_groupe FROM users WHERE mail_user = ?');
    $requete->bind_param('s', $adresseEmail);
    $requete->execute();
    $resultat = $requete->get_result();

    if ($resultat->num_rows === 1) {
        return $resultat->fetch_assoc();
    }

    return null;
}

function creerSessionUtilisateur($utilisateur) {
    $_SESSION['loggedin'] = true;
    $_SESSION['ID_user'] = $utilisateur['id_user'];
    $_SESSION['nom_utilisateur'] = $utilisateur['nom_user'];
}

function redirigerSelonIDGroupe($idGroupe) {
    if ($idGroupe === 1) {
        // header('Location: view/demande_user.php');
        header('Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php');
    } else if ($idGroupe === 3) {
        // header('Location: view/demande_user.php');
        header('Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php');
    } else if ($idGroupe === 4) {
        header('Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php');
    } else {
        header('Location: https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php');
    }
    exit;
}
?>