<?php 
require_once('../scripts/db_connect.php');
require('../scripts/session.php');
if($groupeID!==2){
    require_once('../scripts/session_actif.php');
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="/docs/5.3/assets/js/color-modes.js"></script>

    <style>
    @font-face {
        font-family: 'monica-ext-font_YIBBBFG';
        src: url('chrome-extension://ofpnmcalabcbjgholdjcjblkibolbppb/static//fonts/Satoshi-Variable.ttf');
        font-display: swap;
    }
    </style>
    <title>Ministère des mines</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="/docs/5.3/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.3/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon.ico"> -->
    <meta name="theme-color" content="#712cf9">


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
</head>

<body>
    <?php include_once('../view/shared/navBar.php'); ?>

    <main>

        <div class="album py-5 bg-body-tertiary">
            <div class="container">
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
                <div
                    class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
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
                <div
                    class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static custom-text-justify">
                        <h5 class="mb-0">Manuel d'utilisation</h5>
                        <hr>
                        <ul>
                            <li><strong>Création d'un PV de contrôle</strong>
                                <p class="text-justify">
                                    <!-- <ol>
                                    <li>Cliquer sur l'onglet "Facture", puis sur "Ajouter une facture", saisir les
                                        informations
                                        demandées et cliquer sur Enregistrer.</li>
                                    <li>Sur la ligne correspondant au numéro de la facture inséré précédemment, cliquer
                                        sur
                                        "Détails". Dans la page qui s'ouvre, vous êtes invité à insérer le contenu de la
                                        facture.</li>
                                    <li>Cliquer sur "Ajouter un contenu", puis saisir les informations demandées, et
                                        cliquer sur
                                        Enregistrer. Si vous avez beaucoup de contenu, re-cliquer sur "Ajouter un
                                        contenu" et
                                        remplir le formulaire avec les autres contenus, puis cliquer sur Enregistrer.
                                    </li>
                                    <li>Après avoir inséré le contenu de la facture, cliquer sur "Générer PV de
                                        contrôle" pour
                                        créer le PV de contrôle. Une fenêtre s'ouvre, et vous êtes invité à remplir des
                                        informations. Après avoir rempli le formulaire, cliquer sur Enregistrer.</li>
                                    <li>Vérifier les informations.</li>
                                </ol> -->
                                    <br>1- Cliquer sur l'onglet "Facture", puis sur "Ajouter une facture", saisir les
                                    informations demandées et cliquer sur Enregistrer.
                                    <br>2- Sur la ligne correspondant au numéro de la facture inséré précédemment,
                                    cliquer
                                    sur "Détails". Dans la page qui s'ouvre, vous êtes invité à insérer les contenus de
                                    la
                                    facture.
                                    <br>3- Cliquer sur "Ajouter un contenu", puis saisir les informations demandées, et
                                    cliquer sur Enregistrer. Si vous avez beaucoup de contenu, re-cliquer sur "Ajouter
                                    un
                                    contenu" et remplir le formulaire avec les autres contenus, puis cliquer sur
                                    Enregistrer.
                                    <br>4- Après avoir inséré le contenu de la facture, cliquer sur "Générer PV de
                                    contrôle"
                                    pour créer le PV de contrôle. Une fenêtre s'ouvre, et vous êtes invité à remplir des
                                    informations. Après avoir rempli le formulaire, cliquer sur Enregistrer.
                                    <br>5- Vérifier les informations.
                                    <?php if($groupeID ===1) {?>
                                    <br>6- Sur la page qui s'affiche, vous avez le PV de contrôle et le certificat de
                                    conformité.
                                    <?php } ?>
                                </p>
                            </li>
                            <?php if($groupeID ===3) {?>
                            <li><strong>Création d'un PV de scellage</strong>
                                <p class="text-justify">
                                    <br>1- En haut, vous avez un bouton "Générer PV de scellage" pour générer le PV de
                                    scellage.
                                    Après avoir cliqué, vous êtes invité à remplir un formulaire, puis cliquer sur
                                    Enregistrer.
                                    <br>2- Sur la page qui s'affiche, vous avez le PV de scellage et le certificat de
                                    conformité.
                                </p>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>

                <!-- <div class="col-auto d-none d-lg-block">
                    <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg"
                        role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice"
                        focusable="false">
                        <title>Placeholder</title>
                        <rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef"
                            dy=".3em">Thumbnail</text>
                    </svg>
                </div> -->

            </div>
        </div>

    </main>

    <div>
        <?php
                include('../shared/pied_page.php');
            ?>
    </div>
    <script src="/docs/5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>



    <div id="monica-content-root" class="monica-widget"></div>
</body>

</html>