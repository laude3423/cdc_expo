<?php
// Connexion à la base de données

include('https://cdc.minesmada.org/scripts/db_connect.php');

// Récupération de l'ID_Groupe de l'utilisateur
$userID = $_SESSION['ID_user'];
$requeteUsers = $conn->prepare('SELECT * FROM users WHERE id_user = ?');
$requeteUsers->bind_param('i', $userID);
$requeteUsers->execute();
$resultatUsers = $requeteUsers->get_result();
$rowUsers = $resultatUsers->fetch_assoc();
$groupeID = $rowUsers['id_groupe'];
$pdp = $rowUsers['photo_profil'];
?>

<head>
    <style>
    .navbar {
        top: 0;
        width: 100%;
        background-color: #333;
        /* Couleur de fond de la navbar */
        color: #fff;
        /* Couleur du texte de la navbar */
        padding: 8% 0;
        /* Espacement interne de la navbar */
        z-index: 1000;
        /* Assurez-vous que la navbar est toujours en premier plan */
    }

    .content {
        padding-top: 60px;
        /* Assurez-vous que le contenu ne soit pas caché par la navbar */
    }
    </style>
    <style>
    .container {
        font-size: small;

    }

    th {
        font-size: small;
    }

    .titre {
        text-decoration: none;
        font-size: 100%;
        color: black;
        font-weight: 600;
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

    .dropdown-item {
        color: white !important;
    }

    .nav-link {
        font-weight: 900 !important;
    }

    .dropdown-item:hover {
        color: black !important;
        background-color: white !important;
    }

    .nav-link:hover {
        border-radius: 0.5rem;
        background-color: #fff;
        color: black !important;

    }

    .nav-link:hover {
        cursor: pointer;
    }
    </style>
</head>
<div class="container">
    <hr>
    <header class="py-3 mb-4 border-bottom">
        <div class="container d-flex flex-wrap justify-content-between">
            <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 link-body-emphasis text-decoration-none">
                <svg class="bi me-2" width="40" height="32">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-4"><img src="../../logo/mada.jfif" alt="Logo" height="70" class="mr-3"></span>
            </a>

            <?php 

                // echo '<a href="/" class="d-flex align-items-center mb-3 mb-lg-0 link-body-emphasis text-decoration-none">';
                // echo '<span class="fs-4"><img src="../../logo/mmrs.jfif" alt="Logo" height="70" class="mr-3"></span>';
                // echo '</a>';
                // echo '<a href="/" class="d-flex align-items-center mb-3 mb-lg-0 link-body-emphasis text-decoration-none">';
                // echo '<span class="fs-4"><img src="../../logo/mmrs.jfif" alt="Logo" height="70" class="mr-3"></span>';
                // echo '</a>';
    ?>


            <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 link-body-emphasis text-decoration-none">
                <span class="fs-4"><img src="../../logo/mmrs.jfif" alt="Logo" height="70" class="mr-3"></span>
            </a>

        </div>

    </header>
    <!-- <img src="https://test.mines.gov.mg/website/static/media/banner.0b521befd115407fbf17.webp" alt="banner"
        width="100%"> -->
    <!-- <img src="https://cdc.minesmada.org/logo/banner.png" alt="banner"
        width="100%"> -->
        
    <header
        class="text-bg-dark d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <div class="col-md-3 mb-2 mb-md-0 text-end">
            <a href="#" class="d-inline-flex link-body-emphasis text-decoration-none">
                <svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="../../logo/logo.png"></use>
                </svg>
                <img src='../../logo/logo.png' alt='MIM' width="40" height="32">
            </a>
        </div>

        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <?php if($groupeID === 1) {?>
            <li><a href="https://cdc.minesmada.org/home/home.php" class="nav-link px-2 text-white">Home</a>
            </li>
            <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link px-2 text-white">CDC</a>
            </li>
            <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                    class="nav-link px-2 text-white">Facture</a></li>
            <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                    class="nav-link px-2 text-white">Controle</a>
            </li>
            <?php }?>
            <?php if($groupeID === 2) {?>
            <li><a href="https://cdc.minesmada.org/home/home.php" class="nav-link px-2 text-white">Home</a>
            </li>
            <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link px-2 text-white">CDC</a>
            </li>
            <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                    class="nav-link px-2 text-white">Facture</a></li>
            <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                    class="nav-link px-2 text-white">Controle</a>
            </li>
            <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                    class="nav-link px-2 text-white">Scellage</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Gérer
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_detail_substance/lister.php">Substance</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_type_substance/lister.php">Type de substance</a>
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_couleur_substance/lister.php">Couleur de
                            substance</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_granulo/lister.php">Granulo</a></li>
                    <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_durete/lister.php">Dureté de
                            substance</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_categorie/lister.php">Catégorie de substance</a>
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_forme_substance/lister.php">Forme de
                            substance</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_degre_couleur/lister.php">Degré de
                            couleur</a></li>
                    <li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_dimension_diametre/lister.php">Dimension ou
                            diamètre</a>
                    </li>
                    <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item"
                    href="https://cdc.minesmada.org/view/gerer_utilisateur/lister.php">Utilisateur</a></li>
            <li>
            </li>
            <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/societe_expediteur/lister.php">Société
                    expéditeur</a>
            </li>
            <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/societe_importateur/lister.php">Société
                    importateur</a>
            </li>
        </ul>
        </li>
        <?php }?>
        <?php if($groupeID === 3) {?>
        <li><a href="https://cdc.minesmada.org/home/home.php" class="nav-link px-2 text-white">Home</a></li>
        <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link px-2 text-white">CDC</a></li>
        <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                class="nav-link px-2 text-white">Facture</a></li>
        <li><a href="https://cdc.minesmada.org/view_user/pv_controle_gu/lister.php"
                class="nav-link px-2 text-white">Controle</a>
        </li>
        <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                class="nav-link px-2 text-white">Scellage</a>
        </li>
        <?php }?>
        </ul>
        <div class="col-md-3">
            <div class="dropdown">
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if ($pdp !== NULL) {?>
                    <img src="<?php echo $pdp;?>" alt="mdo" width="32" height="32"
                        class="rounded-circle border border-white">
                    <?php } else { ?>
                    <img src="https://cdc.minesmada.org/logo/person-circle.png" alt="mdo" width="32" height="32"
                        class="rounded-circle">
                    <?php } ?>
                </a>
                <ul class="dropdown-menu text-small dropdown-menu-dark">
                    <!-- <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>-->
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_utilisateur/compte.php">Profile</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/home/condition_utilisation.php">Condition</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="https://cdc.minesmada.org/scripts/logout.php" method="POST" class="d-inline">
                            <input type="submit" value="Déconnexion" class="dropdown-item">
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <script>
    window.addEventListener("scroll", function() {
        var navbar = document.getElementById("navbar");
        if (window.pageYOffset > 0) {
            navbar.style.top = "0";
        } else {
            navbar.style.top = "-60px"; /* 60px est la hauteur de votre navbar */
        }
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

</div>