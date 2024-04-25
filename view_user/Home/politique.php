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
    <title>Politique de Confidentialité:</title>
    <!-- Inclure les fichiers CSS de Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
            <h4 class="text-decoration-underline text-center">Politique de Confidentialité:</h4>
            <p class="text-justify">Bienvenue sur notre application web de génération des "Procès-verbal de scellage
                ou contrôle et constantation" et "Certificat de fonformité" avec code QR. La confidentialité de vos
                informations et des données
                que vous y intégrez est une priorité pour nous. Cette politique de confidentialité explique quelles
                informations collectées, leur utilisation et leur protection. En utilisant l'Application, vous consentez
                à la collecte et à l'utilisation des informations et données que vous y intégrez conformément à cette
                politique.</p>

            <h5 class="text-decoration-underline">1. Informations Collectées</h5>
            <p class="text-justify">Nous collectons les types d'informations suivants lorsque vous utilisez
                l'Application : <br>

                <strong>Informations d'Identification :</strong> Pour générer des documents administratifs
                personnalisés, l'Application collecte des informations telles que votre nom, votre prénom et d'autres
                données d'identification ainsi que celles des demandeurs de "Laissez-passer modèle I" ou l'"Attestation
                de déclaration".
                <br>
                <strong>Informations de Contact :</strong> L’application collecte votre adresse e-mail et d'autres
                informations de contact pour vous envoyer des notifications liées à l'utilisation de l'Application.
                <br>
                <strong>Données pour la Génération d'un "Procès-Verbal" ou "Certificat de conformité" avec
                    Code QR :</strong> Lorsque vous générez des documents tels que le "Procès-Verbal" ou
                le "Certificat de conformité", l'application collect des données spécifiques nécessaires à la création
                des codes QR et au remplissage du document.
            </p>

            <h5 class="text-decoration-underline">2. Utilisation des Informations</h5>
            <p class="text-justify">Les informations collectées par l’Application sont utilisées dans les buts suivants
                : <br>

                <strong>Fournir les Services :</strong> Les informations d'identification et de contact sont utilisées
                pour personnaliser les documents administratifs et faciliter la communication avec vous.
                <br>
                <strong>Améliorer l'Application :</strong> Les informations collectées seront utilisées sous forme de
                données agrégées et anonymisées afin de pouvoir analyser les tendances d'utilisation de l'Application,
                améliorer ses fonctionnalités et optimiser l'expérience de ses utilisateurs.
                <br>
                <strong>Assurer la sécurité : </strong> Les informations collectées peuvent être utilisées pour assurer
                la sécurité de l'Application et prévenir les activités frauduleuses.
            </p>
            <h5 class="text-decoration-underline">3. Protection des Informations</h5>
            <p class="text-justify">Nous mettons en place des mesures de sécurité appropriées pour protéger vos
                informations ainsi que celles des demandeurs de "Procès-Verbal" ou de "Cértificat de conformité" contre
                tout accès non autorisé, altération, divulgation ou destruction.</p>

            <h5 class="text-decoration-underline">4. Partage des Informations</h5>
            <p>Les informations collectées ne seront ni vendues ni louées à des tiers.<br>

                <strong>Conformité Légale :</strong> Les informations collectées peuvent être si cela est nécessaire
                pour respecter les Lois et Règlements en vigueur, pour des éventuelles procédures judiciaires ou pour
                répondre à des demandes gouvernementales.
            </p>
            <h5 class="text-decoration-underline">5. Modifications de la Politique de Confidentialité</h5>
            <p class="text-justify">Nous nous réservons le droit de mettre à jour cette politique de confidentialité à
                tout moment. Les modifications seront publiées sur l'Application. Nous vous encourageons à consulter
                régulièrement cette politique pour rester informé des changements.</p>
            <h5 class="text-decoration-underline">6. Contact</h5>

            <p class="text-justify">Si vous avez des questions ou des préoccupations concernant cette politique de
                confidentialité, veuillez contacter le DGM.</p>

            <p class="text-justify">En utilisant cette Application, vous acceptez d'être lié par ces Conditions. Merci
                de votre compréhension et de votre coopération.</p>
        </div>
        <?php if ($status_condition != '1') { ?>
        <form method="post" action="" class="needs-validation">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="acceptation_condition" name="acceptation_condition"
                    required>
                <label class="form-check-label" for="same-acceptation_condition">"J'accepte les <a
                        href="condition_utilisation.php" class="href">conditions d'utilisation</a>"</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="acceptation_condition" name="acceptation_condition"
                    required>
                <label class="form-check-label" for="same-acceptation_condition">"J'accepte les politiques de
                    confidentialité."</label>
            </div>
            <hr class="my-4">
            <button class="btn btn-primary btn-lg" type="submit">J'accepte</button>
        </form>
        <?php } ?>
    </div>
    <!-- </main> -->


    <!-- Inclure les fichiers JavaScript de Bootstrap 5 (pour le bon fonctionnement des composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.toast').toast('show');
    });
    </script>
</body>

</html>