<?php
include('https://cdc.minesmada.org/scripts/db_connect.php');
    include '../scripts/session.php';
    
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animation Image</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

    <link href="/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
    .container {
        text-align: center;
    }

    .hidden {
        display: none;
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

    .main-content {
        display: none;
        text-align: center;
        margin-top: 20px;
    }

    .album {
        padding: 2rem 0;
        background-color: #f8f9fa;
    }

    .card {
        margin: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1rem;
    }
    </style>

</head>
<?php
        include '../view/shared/navBar.php';?>

<body>

    <div style="text-align: center;">
        <img id="welcome-image" src="../logo/logo.png" alt="Welcome Image">
        <div id="welcome-message" class="message_affiche">Bienvenue sur notre site !</div>
    </div>
    <div class="main-content" id="main-content">
        <main>
            <div class="album py-5 bg-body-tertiary">
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
                    <div
                        class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                        <div class="col p-4 d-flex flex-column position-static custom-text-justify">
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
                                        d'utilisation</a>
                                </li>
                                <li>Lire les <a href="./politique.php" class="href">politiques de confidentialité</a>
                                </li>
                            </p>
                        </div>
                    </div>
                    <div
                        class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
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
                                        informations demandées et cliquer sur Enregistrer.
                                        </br>Après avoir inséré le visa, nous pouvons insérer un scan signé de
                                        certificat de
                                        conformité avec numéro de visa.
                                        </br>Si on a besoin de modifier un visa, cliquer sur l'icône d'édition, puis
                                        modifier les informations que nous
                                        voulons modifier et enregistrer.
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
    const image = document.getElementById('welcome-image');
    const message = document.getElementById('welcome-message');
    const mainContent = document.getElementById('main-content');
    const text = "Bienvenue sur notre site !";
    const interval = 100;

    image.addEventListener('animationend', () => {
        let i = 0;
        message.innerHTML = "";
        message.style.display = 'block';
        const intervalId = setInterval(() => {
            if (i < text.length) {
                message.innerHTML += text.charAt(i);
                i++;
            } else {
                clearInterval(intervalId);
                image.classList.add('hidden'); // Masquer l'image une fois le texte entièrement affiché
                message.style.display =
                    'none'; // Masquer le message de bienvenue une fois le texte entièrement affiché
                mainContent.style.display = 'block';
            }
        }, interval);
    });
    </script>
</body>

</html>