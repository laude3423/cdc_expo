<head>

</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<style>
@media screen and (max-width: 800px) {
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


a:link {
    text-decoration: none;
}

#profileDropdown ul {
    display: none;
}


#profileDropdown:hover ul {
    display: block;
    position: absolute;
    background: #121920;
    color: #fff;
    border-radius: 4px;
    width: 10%
}

#profileMenu {
    list-style-type: none;
    color: #fff;
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

            <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 link-body-emphasis text-decoration-none">
                <span class="fs-4"><img src="../../logo/mmrs.jfif" alt="Logo" height="70" class="mr-3"></span>
            </a>
        </div>
    </header>
    <div
        class="navbar text-bg-dark d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <a href="#" class="d-inline-flex image link-body-emphasis text-decoration-none">
            <img src='../../logo/logo.png' alt='MIM' width="40" height="32">
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
                    <li class="nav-item"><a href="https://cdc.minesmada.org/home/home.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php"
                            class="nav-link">CDC</a></li>
                    <li class="nav-item"><a
                            href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                            class="nav-link">Facture</a></li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/gerer_utilisateur/lister.php">Utilisateur</a>
                    </li>
                    <li class="nav-item"><a href="https://cdc.minesmada.org/view/gerer_utilisateur/compte.php"
                            class="nav-link">Compte</a></li>
                    <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                            class="nav-link">Condition</a></li>
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
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link">CDC</a>
            </li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                    class="nav-link">Facture</a></li>
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
                </ul>
            </div>
        </div>
    </div>
</div>