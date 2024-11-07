<?php 
require_once('../scripts/db_connect.php');
require('../scripts/session.php');

if ($groupeID !== 2) {
    require_once('../scripts/session_actif.php');
} else { // Assurez-vous de définir un $video_id par défaut ou basé sur votre logique
    $query = "SELECT COUNT(*) as view_count FROM video_views";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $view_count = $row['view_count'];
}

$query = "SELECT * FROM direction WHERE id_direction=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_direction);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nom_direction = $row['nom_direction'];
    $type_direction = $row['type_direction'];
} else {
    echo "<p>Direction non trouvée.</p>";
    exit;
}
if(isset($_SESSION['toast_message'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="../images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
                <strong class="me-auto">Notifications</strong>
                <small class="text-muted">Maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Bonjour,Bienvenue sur notre site!
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
   // unset($_SESSION['toast_message']);
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="../logo/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<title>Ministère des mines</title>

<link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
<meta name="theme-color" content="#712cf9">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>
<style>
video {
    width: 100%;
    /* La vidéo occupera 100% de la largeur de l'écran */
    height: auto;
    /* La hauteur sera ajustée automatiquement pour conserver le ratio d'aspect */
    max-width: 100%;
    /* Empêche la vidéo de dépasser la taille de son conteneur */
}
</style>
<style>
@font-face {
    font-family: 'monica-ext-font_YIBBBFG';
    src: url('chrome-extension://ofpnmcalabcbjgholdjcjblkibolbppb/static//fonts/Satoshi-Variable.ttf');
    font-display: swap;
}
</style>
<style>
.bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}

@media (min-width: 768px) {
    .bd-placeholder-img-lg {
        font-size: 3.5rem;
    }
}

.b-example-divider {
    width: 100%;
    height: 3rem;
    background-color: rgba(0, 0, 0, .1);
    border: solid rgba(0, 0, 0, .15);
    border-width: 1px 0;
    box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
}

.b-example-vr {
    flex-shrink: 0;
    width: 1.5rem;
    height: 100vh;
}

.bi {
    vertical-align: -.125em;
    fill: currentColor;
}

.nav-scroller {
    position: relative;
    z-index: 2;
    height: 2.75rem;
    overflow-y: hidden;
}

.nav-scroller .nav {
    display: flex;
    flex-wrap: nowrap;
    padding-bottom: 1rem;
    margin-top: -1px;
    overflow-x: auto;
    text-align: center;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}

.btn-bd-primary {
    --bd-violet-bg: #712cf9;
    --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

    --bs-btn-font-weight: 600;
    --bs-btn-color: var(--bs-white);
    --bs-btn-bg: var(--bd-violet-bg);
    --bs-btn-border-color: var(--bd-violet-bg);
    --bs-btn-hover-color: var(--bs-white);
    --bs-btn-hover-bg: #6528e0;
    --bs-btn-hover-border-color: #6528e0;
    --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
    --bs-btn-active-color: var(--bs-btn-hover-color);
    --bs-btn-active-bg: #5a23c8;
    --bs-btn-active-border-color: #5a23c8;
}

.bd-mode-toggle {
    z-index: 1500;
}

.bd-mode-toggle .dropdown-menu .active .bi {
    display: block !important;
}

.container {
    text-align: center;
}

#welcome-image {
    width: 150px;
    height: 150px;
    animation: zoomIn 1s forwards;
}

.message_affiche {
    font-size: 24px;
    font-weight: bold;
}

@keyframes zoomIn {
    from {
        transform: scale(0.05);
    }

    to {
        transform: scale(1.5);
    }
}

#welcome-message {
    margin-top: 70px;
    font-size: 24px;
    display: none;
}

.hidden {
    display: none;
}

#main-content {
    display: none;
    text-align: left;
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
<style>
.custom-text-justify {
    text-align: justify;
    text-justify: inter-word;
    /* Ajoutez cette propriété pour une justification plus précise */
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoContainer = document.getElementById('videoContainer'); // ID à corriger si nécessaire
    if (!videoContainer) {
        console.error('Élément avec l\'ID "videoContainer" introuvable.');
        return;
    }

    const videoElement = videoContainer.querySelector('video');
    if (!videoElement) {
        console.error('Élément vidéo introuvable.');
        return;
    }

    let videoID = null;
    const sourceSrc = videoElement.querySelector('source').getAttribute('src');
    console.log('Source de la vidéo :', sourceSrc);

    if (sourceSrc === './video/exemple.mp4') {
        videoID = 1;
        console.log('VideoID défini à :', videoID);
    }

    videoElement.addEventListener('play', function() {
        console.log('Événement play détecté');

        if (videoID !== null) {
            console.log('Envoi de la requête fetch...');

            fetch('record_view.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: <?= json_encode($userID); ?>,
                        video_id: videoID
                    })
                })
                .then(response => response.text())
                .then(data => console.log('Réponse du serveur :', data))
                .catch(error => console.error('Erreur fetch :', error));
        } else {
            console.error('videoID non défini.');
        }
    });
});
</script>
</head>

<body>
    <?php
    if($groupeID==4){
        include_once('../aut_visa/header.php');
    }else{
        include_once('../view/shared/navBar.php');
    }
         ?>
    <?php if($groupeID===4){ ?>
    <main>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <div class="col">
                    <div class="card shadow-sm">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                            xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                            preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Autorisation de sortie</title>
                            <rect width="100%" height="100%" fill="#55595c"></rect>
                            <a href="../aut_visa/autorisation/lister.php">
                                <image href="../logo/autorisation.jpeg" width="100%" height="100%"
                                    preserveAspectRatio="xMidYMid slice" />
                            </a>
                        </svg>
                        <div class="card-body">
                            <h5 class="card-text">Autorisation de sortie</h5><br>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="../aut_visa/autorisation/lister.php"
                                    class="btn btn-sm btn-outline-secondary">Selectionner</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                            xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                            preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Visa</title>
                            <rect width="100%" height="100%" fill="#55595c"></rect>
                            <a href="../aut_visa/visa/lister.php">
                                <image href="../logo/visa.png" width="100%" height="100%"
                                    preserveAspectRatio="xMidYMid slice" />
                            </a>
                        </svg>
                        <div class="card-body">
                            <h5 class="card-text">Visa</h5><br>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="../aut_visa/visa/lister.php"
                                    class="btn btn-sm btn-outline-secondary">Selectionner</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="col p-4 d-flex flex-column position-static custom-text-justify">
                    <!-- <strong class="d-inline-block mb-2 text-success-emphasis">Design</strong> -->
                    <h5 class="mb-0">Rappel de vos responsabilités</h5>
                    <hr>
                    <p class="text-justify">
                        Lors de votre inscription, vous devrez créer un mot de passe pour utiliser cette
                        présente
                        application. Vous devez garder votre mot de passe secret; ne le communiquez à personne
                        et ne
                        laissez personne utiliser votre compte. Vous ne devez pas utiliser le mot de passe ni le
                        compte de quelqu’un d’autre. Vous devez nous informer immédiatement si vous soupçonnez
                        une
                        utilisation ou un accès non autorisé de votre compte. Nous ne saurions être tenus
                        responsables de toute perte ou de tout préjudice subi parce que vous n’avez pas gardé
                        votre
                        mot de passe secret, si vous laissez quelqu’un d’autre utiliser votre compte, ou si vous
                        constatez ou soupçonnez une utilisation non autorisée de votre compte sans nous avoir
                        informé dans les plus brefs délais.
                        Avant chaque saisie de données dans cette Application, vous êtes tenus d’effectuer les
                        vérifications nécessaires des dossiers physiques déposés par les demandeurs auprès de
                        votre
                        Direction afin d’éviter toutes sortes de fraudes et de fausses déclarations.
                        L’authenticité
                        ou la véracité des données que vous saisissez dans cette Application est de votre
                        entière
                        responsabilité.</p>
                    <p>
                        <li>Lire les <a href="./condition_utilisation.php" class="href">condition
                                d'utilisation</a></li>
                        <li>Lire les <a href="./politique.php" class="href">politiques de
                                confidentialité</a></li>
                </div>
            </div>
            <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                <div class="col p-4 d-flex flex-column position-static custom-text-justify">
                    <h5 class="mb-0">Manuel d'utilisation</h5>
                    <hr>
                    <ul>
                        <li><strong>Insertion d'une nouvelle autorisation</strong>
                            <p class="text-justify">
                                </br>Cliquer sur l'onglet "Autorisation", puis sur "Ajouter nouveau", saisir les
                                informations demandées et cliquer sur Enregistrer.
                                </br>Après avoir inséré l'autorisation, on doit attendre la validation du
                                Directeur
                                ou chef de service.
                                </br>Si on a besoin de modifier une autorisation ou le statut de validation est
                                à
                                refaire, cliquer sur l'icône d'édition, puis modifier les informations que nous
                                voulons modifier et enregistrer.
                            </p>
                        </li>
                        <li><strong>Insertion de nouveau visa</strong>
                            <p class="text-justify">
                                </br>Cliquer sur l'onglet "visa", puis sur "Ajouter nouveau", saisir les
                                infordivions demandées et cliquer sur Enregistrer.
                                </br>Après avoir inséré le visa, nous pouvons insérer un scan signé de
                                certificat de
                                conformité avec numéro de visa.
                                </br>Si on a besoin de modifier un visa, cliquer sur l'icône d'édition, puis
                                modifier les informations que nous
                                voulons modifier et enregistrer.
                            </p>
                        </li>
                </div>
                </ul>
            </div>
        </div>

    </main>
    <?php }else {?>
    </main>
    <div class="container">
        <!-- <p class="text-center">Guide pour l'utilisation de l'Attestation des valeurs.</p>
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static custom-text-justify">
                <div id="videoContainer">
                    <video controls>
                        <source src="./video/exemple.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </div> -->

        <hr>
        <?php echo "DIRECTION ".$type_direction." ".$nom_direction; ?>
        <hr>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <div class="col">
                <div class="card shadow-sm">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                        xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                        preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Certificat de conformité</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <a href="../view_user/cdc/lister.php">
                            <image href="../logo/cdc_home_2.jpg" width="100%" height="100%"
                                preserveAspectRatio="xMidYMid slice" />
                        </a>
                    </svg>
                    <div class="card-body">
                        <h5 class="card-text">Certificat de conformité</h5><br>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="../view_user/cdc/lister.php"
                                class="btn btn-sm btn-outline-secondary">Selectionner</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                        xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                        preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Procès-Verbal de constantation et contrôle</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <a href="../view_user/pv_controle/lister.php">
                            <image href="../logo/control.jpg" width="100%" height="100%"
                                preserveAspectRatio="xMidYMid slice" />
                        </a>
                    </svg>
                    <div class="card-body">
                        <h5 class="card-text">PV de constantation et contrôle</h5><br>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="../view_user/pv_controle/lister.php"
                                class="btn btn-sm btn-outline-secondary">Selectionner</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(($groupeID ===3)||($groupeID ===2)){?>
            <div class="col">
                <div class="card shadow-sm">
                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                        xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                        preserveAspectRatio="xMidYMid slice" focusable="false">
                        <title>Procès-Verbal de scellage</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect>
                        <a href="../view_user/pv_scellage/lister.php">
                            <image href="../logo/scellage.webp" width="100%" height="100%"
                                preserveAspectRatio="xMidYMid slice" />
                        </a>
                    </svg>
                    <div class="card-body">
                        <h5 class="card-text">PV de scellage</h5><br>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="../view_user/pv_scellage/lister.php"
                                class="btn btn-sm btn-outline-secondary">Selectionner</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <hr>
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static custom-text-justify">
                <!-- <strong class="d-inline-block mb-2 text-success-emphasis">Design</strong> -->
                <h5 class="mb-0">Rappel de vos responsabilités</h5>
                <hr>
                <p class="text-justify">
                    Lors de votre inscription, vous devrez créer un mot de passe pour utiliser cette présente
                    application. Vous devez garder votre mot de passe secret; ne le communiquez à personne et ne
                    laissez personne utiliser votre compte. Vous ne devez pas utiliser le mot de passe ni le
                    compte de quelqu’un d’autre. Vous devez nous informer immédiatement si vous soupçonnez une
                    utilisation ou un accès non autorisé de votre compte. Nous ne saurions être tenus
                    responsables de toute perte ou de tout préjudice subi parce que vous n’avez pas gardé votre
                    mot de passe secret, si vous laissez quelqu’un d’autre utiliser votre compte, ou si vous
                    constatez ou soupçonnez une utilisation non autorisée de votre compte sans nous avoir
                    informé dans les plus brefs délais.
                    Avant chaque saisie de données dans cette Application, vous êtes tenus d’effectuer les
                    vérifications nécessaires des dossiers physiques déposés par les demandeurs auprès de votre
                    Direction afin d’éviter toutes sortes de fraudes et de fausses déclarations. L’authenticité
                    ou la véracité des données que vous saisissez dans cette Application est de votre entière
                    responsabilité.</p>
                <p>
                    <li>Lire les <a href="./condition_utilisation.php" class="href">condition
                            d'utilisation</a></li>
                    <li>Lire les <a href="./politique.php" class="href">politiques de
                            confidentialité</a></li>
            </div>
        </div>
        <?php if($groupeID !=2) {?>
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static custom-text-justify">
                <h5 class="mb-0">Manuel d'utilisation</h5>
                <hr>
                <ul>
                    <li><strong>Insertion d'une nouvelle société</strong>
                        <p class="text-justify">
                            <br>1- Cliquer sur l'onglet "Société", puis sur "Choisir l'une de ces deux
                            sociétés",
                            puis sur "Ajouter nouveau", saisir les
                            informations demandées et cliquez sur Enregistrer.
                            <br>2- Après avoir inséré la société, on doit attendre la validation du Directeur.
                        </p>
                    </li>
                    <li><strong>Création d'une facture</strong>
                        <p class="text-justify">
                            <br>1- Cliquer sur l'onglet "Facture", puis sur "Ajouter une facture", saisir les
                            informations demandées et cliquer sur Enregistrer.
                            <br>2- Vous êtes rediriger dans la page qui s'ouvre, vous êtes invité à insérer les
                            contenus de la facture.
                            <br>3- Cliquer sur "Ajouter un contenu", puis saisir les informations demandées, et
                            cliquer sur Enregistrer. Si vous avez beaucoup de contenu, re-cliquer sur "Ajouter
                            un contenu" et remplir le formulaire avec les autres contenus, puis cliquer sur
                            Enregistrer.
                            <br>4- Si nous utiliserons un nouveau LP, nous devrons choisir la date du LP, puis
                            la Direction d'origine, et enfin choisir un numéro de LP.
                            <br>5- Si nous utiliserons un ancien LP, nous pourrons cliquer sur 'Ancien LP' et
                            choisir un numéro. Si le numéro que nous voulons insérer n'existe pas, nous devrons
                            aller dans le menu 'Ancien_lp' et en insérer un nouveau. Ensuite, nous devrons
                            attendre la validation du Directeur ou du Chef de service.
                            <br>6- Après avoir inséré le contenu de la facture, nous devrons attendre la
                            validation du Chef de Service ou du Chef de Division Exportation.
                    </li>
                    <li><strong>Création d'un PV de contrôle</strong>
                        <p class="text-justify">
                            <br>1- Après avoir inséré le contenu de la facture, on doit attendre la validation
                            du Chef de Service ou du Chef de Division Exportation.
                            <br>2- Une fois que le contenu de la facture est validé, nous pouvons cliquer sur
                            "Générer PV de contrôle" pour créer le PV de contrôle. Une fenêtre s'ouvre, et vous
                            êtes invité à remplir des informations. Après avoir rempli le formulaire, cliquer
                            sur Enregistrer.
                            <br>3-Vérifier les informations et attendre la validation du Chef de Service
                            Exportation ou du Chef de Division Exportation.
                            <br>Une fois que le PV est validé, nous pouvons insérer le scan du PV certifié et
                            signé (avec le PV de scellage).
                            <br>4- Cliquer sur "Insérer scan", puis saisir les
                            informations demandées et cliquer sur Enregistrer.
                            <br>5- Si le certificat de conformité est à refaire, vous pouvez cliquer sur
                            "Modifier scan"
                            <br>6- Sur la page qui s'affiche, vous avez le PV de contrôle et le certificat de
                            conformité en attendant la validation du Chef de Service Exportation et du Directeur
                            des Exportations.
                        </p>
                    </li>
                    <?php if($groupeID ===3) {?>
                    <li><strong>Création d'un PV de scellage</strong>
                        <p class="text-justify">
                            <br>1- En haut, vous avez un bouton "Générer PV de scellage" pour générer le PV de
                            scellage.
                            Après avoir cliqué, vous êtes invité à remplir un formulaire, puis cliquer sur
                            Enregistrer.
                            <br>2- Sur la page qui s'affiche, on doit attendre la validation du Chef de Section
                            Scellage ou Chef de Division Exportation.
                            <br>3- Une fois que le PV de scellage est validé, nous pouvons insérer le scan du PV
                            cértifié et signé avec le PV de contrôle.
                            <br>7- Cliquer sur "Insérer scan", puis saisir les
                            informations demandées et cliquer sur Enregistrer.
                            <br>8- Si le certificat de conformité est à refaire, vous pouvez cliquer sur
                            "Modifier scan"
                        </p>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <?php } ?>

        <?php  if($groupeID === 2){
            echo 'Nombre d\'utilisateur qui regarde le video:'.$view_count;
        }?>
    </div>
    </main>
    <?php }?>
    </div>
    <?php
                include('../shared/pied_page.php');
            ?>
    </div>

    <script>
    $(document).ready(function() {
        $('.toast').toast('show');
    });
    </script>
    <div id="monica-content-root" class="monica-widget"></div>
</body>

</html>