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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.text-bg-dark');

    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 0) {
                navbar.classList.add('fixed-navbar');
            } else {
                navbar.classList.remove('fixed-navbar');
            }
        });
    }
});
</script>

<style>
.fixed-navbar {
    margin-top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 999 !important;
    position: fixed;
    width: 100%;
    top: 0;
}

.navbar-toggler {
    border-color: rgba(255, 255, 255, 0.1);
    /* Change the border color to white with some transparency */
}

a:link {
    text-decoration: none;
}

#profileDropdown ul {
    display: none;
}

.image {
    margin-left: 5%;
    padding-left: 5%;
}

.col-md-3 {
    width: 12%;
}

.masque {
    display: none;
}

.bouton2 {
    display: none;
}

@media screen and (max-width: 800px) {
    .masque1 {
        display: none;
    }

    .navbar .image .navbar-toggler {
        display: flex !important;
        flex-direction: column;
    }

    .image {
        margin-left: 0;
        padding-left: 0;
    }

    .navbar-toggler {
        margin-left: 50%;
    }

    .col-md-3 {
        display: none;
    }
}

@media screen and (max-width: 600px) {
    .masque2 {
        display: none;
    }

    .bouton1 {
        display: none;
    }

    .bouton2 {
        display: block;
    }

    .masque {
        display: table-cell;
        /* Utilisez table-cell pour les éléments de tableau */
    }
}

#profileDropdown:hover ul {
    display: block;
    position: absolute;
    background: #17202a;
    color: #fff;
    border-radius: 4px;
    width: 10%;
    z-index: 999;
}

#profileMenu {
    list-style-type: none;
    color: #fff;
    padding-left: 2px;
}

#profileMenu li a {
    padding: 5px 10px;
}

#profileMenu li a:hover {
    background: #fff;
    color: black;
    border-radius: 3px;
}


.navbar-toggler .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255, 255, 255, 1%29' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
}

.offcanvas {
    width: 200px !important;
    background: #161b20;
    color: white;
}

.nav-link {
    font-weight: 900 !important;
    color: #fff;
}

.nav-link:hover {
    border-radius: 0.5rem;
    background-color: #fff;
    color: black !important;

}

.nav-link:hover {
    cursor: pointer;
}

.btn-close-white {
    filter: invert(1);
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

<div class="container">
    <hr>
    <!-- <header class="py-3 mb-4 border-bottom">
        <div class="container d-flex flex-wrap justify-content-between">
            <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 link-body-emphasis text-decoration-none">
                <svg class="bi me-2" width="40" height="32">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-4"><img src="../../logo/mada.jfif" alt="Logo" height="70" class="mr-3"></span>
            </a>

            <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 link-body-emphasis text-decoration-none">
                <span class="fs-4"><img src="../../logo/mmrs.jfif" alt="Logo" height="70" class="mr-3"></span>
            </a>
        </div>
    </header> -->
    <img src="https://cdc.minesmada.org/logo/Banner-MIM-1.png" alt="banner" width="100%">
    <div
        class="text-bg-dark d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="#" class="d-inline-flex image link-body-emphasis text-decoration-none">
            <img src='../../logo/cc-logo.png' alt='MIM' width="40" height="32">
        </a>
        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end offcanvas-nav" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="nav nav-pills flex-column">
                    <li><a href="https://cdc.minesmada.org/home/home.php" class="nav-link px-2 text-white">Home</a></li>
                    <?php if($groupeID === 1) {?>
                    <li><a href="https://cdc.minesmada.org/view/societe_expediteur/lister.php"
                            class="nav-link px-2 text-white">Societés expediteur</a></li>
                    <li><a href="https://cdc.minesmada.org/view/societe_importateur/lister.php"
                            class="nav-link px-2 text-white">Societés importateur</a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                            class="nav-link px-2 text-white">Facture</a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                            class="nav-link px-2 text-white">Contrôle</a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php"
                            class="nav-link px-2 text-white">CDC</a></li>
                    <?php }?>
                    <?php if($groupeID === 3) {?>
                    <li><a href="https://cdc.minesmada.org/view/societe_expediteur/lister.php"
                            class="nav-link px-2 text-white">Societés expediteur</a></li>
                    <li><a href="https://cdc.minesmada.org/view/societe_importateur/lister.php"
                            class="nav-link px-2 text-white">Societés importateur</a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                            class="nav-link px-2 text-white">Facture</a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_controle_gu/lister.php"
                            class="nav-link px-2 text-white">Contrôle</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                            class="nav-link px-2 text-white">Scellage</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php"
                            class="nav-link px-2 text-white">CDC</a></li>
                    <?php } ?>
                    <?php if($groupeID === 2) {?>
                    <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php"
                            class="nav-link px-2 text-white">CDC</a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                            class="nav-link px-2 text-white">Contrôle</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                            class="nav-link px-2 text-white">Scellage</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                            class="nav-link px-2 text-white">Facture</a></li>
                    <hr>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_detail_substance/lister.php">Substance</a>
                    </li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_type_substance/lister.php">Type
                            de substance</a>
                    </li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_couleur_substance/lister.php">Couleur
                            de
                            substance</a></li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_granulo/lister.php">Granulo</a>
                    </li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_durete/lister.php">Dureté de
                            substance</a></li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_categorie/lister.php">Catégorie
                            de substance</a>
                    </li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_forme_substance/lister.php">Forme
                            de
                            substance</a></li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_degre_couleur/lister.php">Degré
                            de
                            couleur</a></li>
                    <li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_dimension_diametre/lister.php">Dimension
                            ou
                            diamètre</a>
                    </li>
                    <hr>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_utilisateur/lister.php">Utilisateur</a>
                    </li>
                    <hr>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/societe_expediteur/lister.php">Société
                            expéditeur</a>
                    </li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/societe_importateur/lister.php">Société
                            importateur</a>
                    </li>
                    <?php }?>
                    <hr>
                    <li class="nav-item"><a href="https://cdc.minesmada.org/view/gerer_utilisateur/compte.php"
                            class="nav-link">Compte</a></li>
                    <li class="nav-item"><a href="https://cdc.minesmada.org/home/condition_utilisation.php"
                            class="nav-link">Condition</a></li>
                    <hr>
                    <li class="nav-item">
                        <form action="https://cdc.minesmada.org/scripts/logout.php" method="POST" class="d-inline">
                            <input type="hidden" value="Déconnexion">
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <ul class="nav d-none d-md-flex col-md-auto mb-2 justify-content-center mb-md-0">
            <li class="nav-item"><a href="https://cdc.minesmada.org/home/home.php" class="nav-link">Home</a>
            </li>
            <?php if($groupeID === 1) {?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Societé
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/societe_expediteur/lister.php">Liste des societés
                            expediteur</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/societe_importateur/lister.php">Liste des societés
                            importateur</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                    class="nav-link">Facture</a></li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                    class="nav-link">Contrôle</a></li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link">CDC</a>
            </li>
            <?php }?>

            <?php if($groupeID === 3) {?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Societe
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/societe_expediteur/lister.php">Liste des societés
                            expediteur</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/societe_importateur/lister.php">Liste des societés
                            importateur</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                    class="nav-link">Facture</a></li>
            <li><a href="https://cdc.minesmada.org/view_user/pv_controle_gu/lister.php"
                    class="nav-link px-2 text-white">Contrôle</a>
            </li>
            <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                    class="nav-link px-2 text-white">Scellage</a>
            </li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link">CDC</a>
            </li>
            <?php }?>
            <?php if($groupeID === 2) {?>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                    class="nav-link">Facture</a></li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link">CDC</a>
            </li>
            <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                    class="nav-link px-2 text-white">Contrôle</a>
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
                            href="https://cdc.minesmada.org/view/gerer_detail_substance/lister.php">Substance</a>
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_type_substance/lister.php">Type de
                            substance</a>
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_couleur_substance/lister.php">Couleur de
                            substance</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_granulo/lister.php">Granulo</a></li>
                    <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_durete/lister.php">Dureté de
                            substance</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_categorie/lister.php">Catégorie de
                            substance</a>
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_forme_substance/lister.php">Forme de
                            substance</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_degre_couleur/lister.php">Degré de
                            couleur</a></li>
                    <li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_dimension_diametre/lister.php">Dimension
                            ou
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


        </ul>
        <div class="col-md-3">
            <div id="profileDropdown">
                <a href="#" role="button" aria-expanded="false">
                    <img src="https://cdc.minesmada.org/logo/person-circle.png" alt="Profile" width="32" height="32"
                        class="rounded-circle">
                </a>
                <ul id="profileMenu">
                    <li><a href="https://cdc.minesmada.org/view/gerer_utilisateur/compte.php"
                            class="dropdown-item">Profile</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/home/condition_utilisation.php"
                            class="dropdown-item">Condition</a>
                    </li>
                    <li>
                        <hr>
                    </li>
                    <li>
                        <form action="https://cdc.minesmada.org/scripts/logout.php" method="POST" class="d-inline">
                            <input type="hidden" value="Déconnexion">
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                    <hr>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
</script>