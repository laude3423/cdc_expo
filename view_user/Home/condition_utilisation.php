<?php 
// Connexion à la base de données
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Vérification de la case à cocher
    $acceptation_politique = isset($_POST["acceptation_politique"]) ? 1 : 0;
    $acceptation_condition= isset($_POST["acceptation_condition"]) ? 1 : 0;
    $date_acceptation = date("Y-m-d H:i:s");
    $sql="SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $politique = intval($row['status_politique']);
    $condition =intval($row['status_condition']);
    if($condition===0){
        $update_query = "UPDATE users SET status_condition = '$acceptation_condition', status_politique = '$acceptation_politique', date_acceptation = '$date_acceptation'
            WHERE id_user = '$userID'";

            if ($conn->query($update_query) === TRUE) {
                $_SESSION['toast_message'] = "Vous avez accépté la condition d'utilisation.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur lors de l'insertion de l'utilisateur : " . $conn->error;
            }
    }elseif($politique===0){
        $update_query = "UPDATE users SET status_condition = '$acceptation_condition', status_politique = '$acceptation_politique', date_acceptation = '$date_acceptation'
            WHERE id_user = '$userID'";

            if ($conn->query($update_query) === TRUE) {
                $_SESSION['toast_message'] = "Vous avez accépté la politique de confidialité.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur lors de l'insertion de l'utilisateur : " . $conn->error;
            }
    }else{
        $_SESSION['toast_message2'] = "Vous avez déjà accépté la condition d'utilisation et la politique de confidialité.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
    }

    
if(isset($_SESSION['toast_message'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="../../view/images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                    <small class="text-muted">Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . $_SESSION['toast_message'] . '
                </div>
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
}
if(isset($_SESSION['toast_message2'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="../../view/images/warning.jpeg" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                    <small class="text-muted">Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . $_SESSION['toast_message2'] . '
                </div>
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message2']);
}

}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Conditions d'Utilisation de l'Application</title>
    <!-- Inclure les fichiers CSS de Bootstrap 5 -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        /* background-color: #f0f0f0; Couleur de fond */
        background-color: #ffffff;
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
        max-width: 1000px;
        /* Largeur maximale du cadre */
    }
    </style>
    <style>
    .custom-text-justify {
        text-align: justify;
        text-justify: inter-word;
        /* Ajoutez cette propriété pour une justification plus précise */
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
    </style>
</head>

<body>
    <?php include "../../shared/header.php"; ?>
    <!-- <main> -->
    <div class="container rounded-border custom-text-justify">
        <div class="alert" role="alert">
            <h4 class="text-decoration-underline text-center">Conditions d'Utilisation de l'Application</h4>
            <p class="text-justify">Bienvenue sur notre application web de génération des "Procès-Verbal de Scellage ou
                contrôle et constantation" et "Certificat de conformité"
                avec code QR, dans le cadre de digitalisation. Avant d'utiliser
                l'Application, veuillez lire attentivement les présentes conditions d'utilisation. En accédant ou en
                utilisant l'Application, vous acceptez d'être lié par ces Conditions.</p>

            <li><strong>Acceptation des Conditions</strong></li>
            <p class="text-justify">En utilisant l'Application, vous déclarez avoir lu, compris et accepté les présentes
                Conditions. Si vous utilisez l'Application au nom de votre Direction Régionale, Direction Interrégionale
                ou Guichet Unique, vous
                déclarez avoir l'autorité nécessaire pour lier cette entité à ces Conditions. </p>


            <li><strong>Utilisation de l'Application</strong></li>
            <p class="text-justify">Vous êtes autorisé à utiliser l'Application pour générer le "PV de scellage, le PV
                de
                contrôle et constantation et le certificat de foncformité"
                munis d'un code QR conformément aux normes éthiques. Vous vous engagez à ne pas utiliser l'Application à
                des fins illégales, frauduleuses ou préjudiciables.</p>
            <li><strong>Données Personnelles</strong></li>
            <p class="text-justify">Lors de l'utilisation de l'Application, vous pourriez être amené à fournir des
                informations personnelles. Vous consentez à ce que ces informations soient collectées et traitées
                conformément à notre <a href="politique.php" class="href">politique de
                    confidentialité</a>, disponible sur l'Application.</p>
            <li><strong>Responsabilité</strong></li>
            <p>Lors de votre inscription, vous devrez créer un mot de passe pour utiliser cette présente application.
                Vous devez garder votre mot de passe secret; ne le communiquez à personne et ne laissez personne
                utiliser votre compte. Vous ne devez pas utiliser le mot de passe ni le compte de quelqu’un d’autre.
                Vous devez nous informer immédiatement si vous soupçonnez une utilisation ou un accès non autorisé de
                votre compte. Nous ne saurions être tenus responsables de toute perte ou de tout préjudice subi parce
                que vous n’avez pas gardé votre mot de passe secret, si vous laissez quelqu’un d’autre utiliser votre
                compte, ou si vous constatez ou soupçonnez une utilisation non autorisée de votre compte sans nous avoir
                informé dans les plus brefs délais.
                <br>Avant chaque saisie de données dans cette Application, vous êtes tenus d’effectuer
                les vérifications nécessaires des dossiers physiques déposés par les demandeurs
                auprès de votre Direction afin d’éviter toutes sortes de fraudes et de fausses
                déclarations. L’authenticité ou la véracité des données que vous saisissez dans cette
                Application est de votre entière responsabilité.
            </p>

            <li><strong>Code QR</strong></li>
            <p class="text-justify">Les codes QR générés par l'Application sont destinés à un usage légitime et
                conformément aux Lois et Règlements. Vous êtes responsables de l'utilisation appropriée des codes QR
                générés, et nous ne sommes pas responsables de toute utilisation abusive ou illégale.</p>
            <li><strong>Modifications des Conditions</strong></li>
            <p class="text-justify">Nous nous réservons le droit de modifier ces Conditions à tout moment. Les
                modifications prendront effet dès leur publication sur l'Application. Il vous incombe de consulter
                régulièrement les Conditions pour être informé des changements éventuels.</p>
            <li><strong>Résiliation</strong></li>
            <p class="text-justify">Nous pouvons résilier votre accès à l'Application à tout moment en cas de violation
                de ces Conditions ou pour toute autre raison. En cas de résiliation, vous devez cesser toute utilisation
                de l'Application.</p>
            <li><strong>Contact</strong></li>
            <p class="text-justify">Pour toute question ou préoccupation concernant ces Conditions, veuillez contacter
                le DGM.</p>

            <p class="text-justify">En utilisant cette Application, vous acceptez d'être lié par ces Conditions. Merci
                de votre compréhension et de votre coopération.</p>
        </div>
        <?php if ($status_condition != '1') { ?>
        <form method="post" action="" class="needs-validation">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="acceptation_condition" name="acceptation_condition"
                    required>
                <label class="form-check-label" for="same-acceptation_condition">"J'accepte les conditions d'utilisation
                    de l'application"</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="acceptation_politique" name="acceptation_politique"
                    required>
                <label class="form-check-label" for="same-acceptation_politique">"J'accepte les <a href="politique.php"
                        class="href">politiques de confidentialité</a>."</label>
            </div>
            <hr class="my-4">
            <button class="btn btn-primary btn-lg" type="submit">J'accepte</button>
        </form>
        <?php } ?>
    </div>
    <!-- </main> -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Inclure les fichiers JavaScript de Bootstrap 5 (pour le bon fonctionnement des composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>