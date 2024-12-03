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
$userNom=$rowUsers['nom_user'];
$userPrenom=$rowUsers['prenom_user'];
?>
<?php 
function getNonValidCount($conn, $tableName, $columnName,$code_fonction) {
    // Protection contre les injections SQL (bien que basique ici)
    $tableName = $conn->real_escape_string($tableName);
    $columnName = $conn->real_escape_string($columnName);
    $result="";
    if($code_fonction=="C"){
        $sql = "SELECT COUNT(*) AS pas_valide FROM $tableName WHERE $columnName = 'À Refaire'";
        $result = $conn->query($sql);
    }else{
        $sql = "SELECT COUNT(*) AS pas_valide FROM $tableName WHERE $columnName != 'Validé'";
        $result = $conn->query($sql);
    }
    

    if (!$result) {
        die("Erreur dans la requête : " . $conn->error);
    }

    // Récupération du résultat
    $rowVal = $result->fetch_assoc();
    return $rowVal['pas_valide'];
}
function getNonValidCount2($conn, $tableName, $columnName, $direction, $code_fonction) {
    // Protection contre les injections SQL (bien que basique ici)
    $tableName = $conn->real_escape_string($tableName);
    $columnName = $conn->real_escape_string($columnName);
    $result="";
    if($code_fonction=="C"){
        $sql = "SELECT COUNT(*) AS pas_valide FROM $tableName AS dcc
                            LEFT JOIN users AS us ON dcc.id_user = us.id_user
                            LEFT JOIN direction AS dir ON dir.id_direction = us.id_direction WHERE dcc.$columnName = 'À Refaire' AND dir.id_direction=$direction";
        $result = $conn->query($sql);
    }else{
        $sql = "SELECT COUNT(*) AS pas_valide FROM $tableName AS dcc
                            LEFT JOIN users AS us ON dcc.id_user = us.id_user
                            LEFT JOIN direction AS dir ON dir.id_direction = us.id_direction WHERE dcc.$columnName != 'Validé' AND dir.id_direction=$direction";
        $result = $conn->query($sql);
    }
    if (!$result) {
        die("Erreur dans la requête : " . $conn->error);
    }

    // Récupération du résultat
    $rowVal2 = $result->fetch_assoc();
    return $rowVal2['pas_valide'];
}

function getNonValidCount3($conn, $tableName, $columnName1,$columnName2, $direction, $code_fonction) {
    // Protection contre les injections SQL (bien que basique ici)
    $tableName = $conn->real_escape_string($tableName);
    $columnName1 = $conn->real_escape_string($columnName1);
    $columnName2 = $conn->real_escape_string($columnName2);
    $result="";
    if($code_fonction=='C'){
        $sql = "SELECT COUNT(*) AS pas_valide FROM $tableName AS dcc
                            LEFT JOIN users AS us ON dcc.id_user = us.id_user
                            LEFT JOIN direction AS dir ON dir.id_direction = us.id_direction WHERE (dcc.$columnName1 = 'À Refaire' OR dcc.$columnName2 = 'À Refaire') AND dir.id_direction=$direction";
        $result = $conn->query($sql);
    }else{
            $sql = "SELECT COUNT(*) AS pas_valide FROM $tableName AS dcc
                            LEFT JOIN users AS us ON dcc.id_user = us.id_user
                            LEFT JOIN direction AS dir ON dir.id_direction = us.id_direction WHERE (dcc.$columnName1 != 'Validé' OR dcc.$columnName2 != 'Validé') AND dir.id_direction=$direction";
        $result = $conn->query($sql);
    }
    if (!$result) {
        die("Erreur dans la requête : " . $conn->error);
    }

    // Récupération du résultat
    $rowValue = $result->fetch_assoc();
    return $rowValue['pas_valide'];
}


function afficherBadgeNonValid($conn, $tableName, $columnName,$code_fonction) {
    $a_corrige = getNonValidCount($conn, $tableName, $columnName, $code_fonction);

    if($a_corrige > 0) {
        echo '<span class="badge text-bg-danger">' . $a_corrige . '</span>';
    }
}

function afficherBadgeNonValid2($conn, $tableName, $columnName, $direct, $code_fonction) {
    $a_corrige = getNonValidCount2($conn, $tableName, $columnName, $direct, $code_fonction);

    if($a_corrige > 0) {
        echo '<span class="badge text-bg-danger">' . $a_corrige . '</span>';
    }
}
function afficherBadgeNonValid3($conn, $tableName,$columnName1, $columnName2, $direct, $code_fonction) {
    $a_corrige = getNonValidCount3($conn, $tableName, $columnName1,$columnName2, $direct, $code_fonction);

    if($a_corrige > 0) {
        echo '<span class="badge text-bg-danger">' . $a_corrige . '</span>';
    }
}

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

.toast-container-centered {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1055;
    /* Assurez-vous qu'il est au-dessus d'autres éléments */
}

.toast {
    opacity: 1;
    background-color: white;
    /* Pour s'assurer qu'il est visible */
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

th {
    font-size: small;
}

td {
    font-size: small;
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
                    <?php if($groupeID === 1) {?>
                    <li><a href="https://cdc.minesmada.org/home/home.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view/societe_expediteur/lister.php"
                            class="nav-link px-2 text-white">Société
                            expediteur<?php afficherBadgeNonValid($conn, 'societe_expediteur', 'validation', $code_fonction); ?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view/societe_importateur/lister.php"
                            class="nav-link px-2 text-white">Société
                            importatrice<?php afficherBadgeNonValid($conn, 'societe_importateur', 'validation', $code_fonction); ?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                            class="nav-link px-2 text-white">Facture<?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_facture', $id_direction, $code_fonction);?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/attestation_valeur/liste_attestation.php"
                            class="nav-link px-2 text-white">Attestation</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/ancien_lp/lister.php"
                            class="nav-link px-2 text-white">Ancien_lp<?php afficherBadgeNonValid($conn, 'ancien_lp', 'validation_lp', $code_fonction); ?></a>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                            class="nav-link px-2 text-white">Contrôle<?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_controle', $id_direction, $code_fonction);?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php"
                            class="nav-link px-2 text-white">CDC<?php afficherBadgeNonValid3($conn, 'data_cc', 'validation_chef', 'validation_directeur', $id_direction, $code_fonction)?></a>
                    </li>
                    <?php }?>
                    <?php if($groupeID === 3) {?>
                    <li><a href="https://cdc.minesmada.org/home/home.php" class="nav-link px-2 text-white">Home</a></li>
                    <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view/societe_expediteur/lister.php"
                            class="nav-link px-2 text-white">Société
                            expediteur<?php afficherBadgeNonValid($conn, 'societe_expediteur', 'validation', $code_fonction); ?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view/societe_importateur/lister.php"
                            class="nav-link px-2 text-white">Société importatrice
                            <?php afficherBadgeNonValid($conn, 'societe_importateur', 'validation', $code_fonction); ?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                            class="nav-link px-2 text-white">Facture<?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_facture', $id_direction, $code_fonction);?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/attestation_valeur/liste_attestation.php"
                            class="nav-link px-2 text-white">Attestation</a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/ancien_lp/lister.php"
                            class="nav-link px-2 text-white">Ancien_lp<?php afficherBadgeNonValid($conn, 'ancien_lp', 'validation_lp', $code_fonction); ?></a>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_controle_gu/lister.php"
                            class="nav-link px-2 text-white">Contrôle<?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_controle', $id_direction, $code_fonction);?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                            class="nav-link px-2 text-white">Scellage<?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_scellage', $id_direction, $code_fonction);?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php"
                            class="nav-link px-2 text-white">CDC<?php afficherBadgeNonValid3($conn, 'data_cc', 'validation_chef', 'validation_directeur', $id_direction, $code_fonction)?></a>
                    </li>
                    <?php } ?>
                    <?php if($groupeID === 2) {?>
                    <li><a href="https://cdc.minesmada.org/histogramme/dashboard.php"
                            class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/cdc/lister.php"
                            class="nav-link px-2 text-white">C.C
                            <?php $sql="";
                        if($code_fonction=="C"){
                            $sql = "SELECT COUNT(*) AS pas_valide FROM data_cc WHERE validation_directeur ='À Refaire' OR validation_chef ='À Refaire'";
                        }else{
                            $sql = "SELECT COUNT(*) AS pas_valide FROM data_cc WHERE validation_directeur !='Validé' AND validation_chef !='Validé'";
                        }
                        $result = $conn->query($sql);
                        if (!$result) {
                            die("Erreur dans la requête : " . $conn->error);
                        }
                        $rowVal3 = $result->fetch_assoc();
                        $a_corrige = $rowVal3['pas_valide'];

                        if($a_corrige>0){
                            echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' . $a_corrige . '</span>';
                        }
                ?></a></li>
                    <li><a href="https://cdc.minesmada.org/view_user/ancien_lp/lister.php"
                            class="nav-link px-2 text-white">Ancien_lp<?php afficherBadgeNonValid($conn, 'ancien_lp', 'validation_lp', $code_fonction); ?></a>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                            class="nav-link px-2 text-white">Contrôle<?php afficherBadgeNonValid($conn, 'data_cc', 'validation_controle', $code_fonction); ?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                            class="nav-link px-2 text-white">Scellage<?php afficherBadgeNonValid($conn, 'data_cc', 'validation_scellage', $code_fonction); ?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php"
                            class="nav-link px-2 text-white">Facture<?php afficherBadgeNonValid($conn, 'data_cc', 'validation_facture', $code_fonction); ?></a>
                    </li>
                    <li><a href="https://cdc.minesmada.org/view_user/attestation_valeur/liste_attestation.php"
                            class="nav-link px-2 text-white">Attestation</a>
                    </li>
                    <hr>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                    </li>
                    <li><a class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/histogramme/logs.php">Logs</a>
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
                            importatrice</a>
                    </li>
                    <li>
                        <e class="nav-link px-2 text-white"
                            href="https://cdc.minesmada.org/view/gerer_utilisateur/annonce.php">Annonce</a>
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

            <?php if($groupeID === 1) {?>
            <li class="nav-item"><a href="https://cdc.minesmada.org/home/home.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Gérer
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/societe_expediteur/lister.php">Société
                            Expéditeur<?php afficherBadgeNonValid($conn, 'societe_expediteur', 'validation', $code_fonction); ?></a>
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view/societe_importateur/lister.php">Société
                            Importatrice<?php afficherBadgeNonValid($conn, 'societe_importateur', 'validation', $code_fonction); ?></a>
                    </li>
                    <li><a class="dropdown-item"
                            href="https://cdc.minesmada.org/view_user/ancien_lp/lister.php">Ancien_lp<?php afficherBadgeNonValid($conn, 'ancien_lp', 'validation_lp', $code_fonction); ?></a>
                    </li>
                    <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                    </li>
                    <li><a class="dropdown-item" href="https://cdc.minesmada.org/histogramme/liste_substance.php">Liste
                            des
                            substances</a>
                    </li>
                </ul>
            </li>

            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Facture
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item"
                            href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php">
                            Facture
                            <?php afficherBadgeNonValid($conn, 'data_cc', 'validation_facture', $code_fonction); ?>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item"
                            href="https://cdc.minesmada.org/view_user/attestation_valeur/liste_attestation.php">
                            Attestation des valeurs
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                    class="nav-link">Contrôle
                    <?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_controle', $id_direction, $code_fonction);?></a>
            </li>
            <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link">C.C
                    <?php afficherBadgeNonValid3($conn, 'data_cc', 'validation_chef', 'validation_directeur', $id_direction, $code_fonction)?>
                </a>
            </li>
        </ul>
        <?php }?>

        <?php if($groupeID === 3) {?>
        <li class="nav-item"><a href="https://cdc.minesmada.org/home/home.php" class="nav-link">Home</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Gérer
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/societe_expediteur/lister.php">Société
                        Expéditeur<?php afficherBadgeNonValid($conn, 'societe_expediteur', 'validation', $code_fonction); ?></a>
                </li>
                <li><a class="dropdown-item"
                        href="https://cdc.minesmada.org/view/societe_importateur/lister.php">Société
                        Importatrice<?php afficherBadgeNonValid($conn, 'societe_importateur', 'validation', $code_fonction); ?></a>
                </li>
                <li><a class="dropdown-item"
                        href="https://cdc.minesmada.org/view_user/ancien_lp/lister.php">Ancien_lp<?php afficherBadgeNonValid($conn, 'ancien_lp', 'validation_lp', $code_fonction); ?></a>
                </li>
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                </li>
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/histogramme/liste_substance.php">Liste des
                        substances</a>
                </li>
            </ul>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Facture
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item"
                        href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php">
                        Facture
                        <?php afficherBadgeNonValid($conn, 'data_cc', 'validation_facture', $code_fonction); ?>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item"
                        href="https://cdc.minesmada.org/view_user/attestation_valeur/liste_attestation.php">
                        Attestation des valeurs
                    </a>
                </li>
            </ul>
        </li>
        <li><a href="https://cdc.minesmada.org/view_user/pv_controle_gu/lister.php"
                class="nav-link px-2 text-white">Contrôle
                <?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_controle', $id_direction, $code_fonction);?></a>
        </li>
        <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                class="nav-link px-2 text-white">Scellage
                <?php afficherBadgeNonValid2($conn, 'data_cc', 'validation_scellage', $id_direction, $code_fonction);?></a>
        </li>
        <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link">C.C
                <?php afficherBadgeNonValid3($conn, 'data_cc', 'validation_chef', 'validation_directeur', $id_direction, $code_fonction)?>
            </a>
        </li>
        <?php }?>
        <?php if($groupeID === 2) {?>
        <li class="nav-item"><a href="https://cdc.minesmada.org/histogramme/dashboard.php" class="nav-link">Home</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Facture
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item"
                        href="https://cdc.minesmada.org/view_user/gerer_contenu_facture/liste_facture.php">
                        Facture
                        <?php afficherBadgeNonValid($conn, 'data_cc', 'validation_facture', $code_fonction); ?>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item"
                        href="https://cdc.minesmada.org/view_user/attestation_valeur/liste_attestation.php">
                        Attestation des valeurs
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/cdc/lister.php" class="nav-link">C.C
                <?php $sql="";
                    $sql = "SELECT COUNT(*) AS pas_valide FROM data_cc WHERE (validation_directeur !='Validé' OR validation_chef !='Validé')";
                        $result = $conn->query($sql);
                        if (!$result) {
                            die("Erreur dans la requête : " . $conn->error);
                        }
                        $rowCC = $result->fetch_assoc();
                        $a_corrige = $rowCC['pas_valide'];

                        if($a_corrige>0){
                             echo '<span class="badge text-bg-danger">' . $a_corrige . '</span>';
                        }
                ?>
            </a>
        </li>
        <li class="nav-item"><a href="https://cdc.minesmada.org/view_user/ancien_lp/lister.php"
                class="nav-link">Ancien_lp<?php afficherBadgeNonValid($conn, 'ancien_lp', 'validation_lp', $code_fonction); ?></a>
        <li><a href="https://cdc.minesmada.org/view_user/pv_controle/lister.php"
                class="nav-link px-2 text-white">Contrôle
                <?php afficherBadgeNonValid($conn, 'data_cc', 'validation_controle', $code_fonction); ?></a>
        </li>
        <li><a href="https://cdc.minesmada.org/view_user/pv_scellage/lister.php"
                class="nav-link px-2 text-white">Scellage
                <?php afficherBadgeNonValid($conn, 'data_cc', 'validation_scellage', $code_fonction); ?></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                Gérer
            </a>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_agent/lister.php">Agent</a>
                </li>
                <li><a class="dropdown-item text-white" href="https://cdc.minesmada.org/histogramme/logs.php">Logs</a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item"
                        href="https://cdc.minesmada.org/view/gerer_detail_substance/lister.php">Substance</a>
                </li>
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_type_substance/lister.php">Type
                        de
                        substance</a>
                </li>
                <li><a class="dropdown-item"
                        href="https://cdc.minesmada.org/view/gerer_couleur_substance/lister.php">Couleur de
                        substance</a></li>
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_granulo/lister.php">Granulo</a>
                </li>
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_durete/lister.php">Dureté de
                        substance</a></li>
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_categorie/lister.php">Catégorie
                        de
                        substance</a>
                </li>
                <li><a class="dropdown-item"
                        href="https://cdc.minesmada.org/view/gerer_forme_substance/lister.php">Forme de
                        substance</a></li>
                <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_degre_couleur/lister.php">Degré
                        de
                        couleur</a></li>
                <li>
                <li><a class="dropdown-item"
                        href="https://cdc.minesmada.org/view/gerer_dimension_diametre/lister.php">Dimension
                        ou
                        diamètre</a>
                </li>
                <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_utilisateur/lister.php">Utilisateur</a>
        </li>
        <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/gerer_utilisateur/annonce.php">Annonce</a>
        </li>
        </li>
        <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/societe_expediteur/lister.php">Société
                expéditeur</a>
        </li>
        <li><a class="dropdown-item" href="https://cdc.minesmada.org/view/societe_importateur/lister.php">Société
                importatrice</a>
        </li>
        </ul>
        </li>
        <?php }?>


        </ul>
        <div class="col-md-3">
            <div id="profileDropdown">
                <?php

                    $image_filename = basename($image_url); // extrait "profil.png"

                    // Chemin de base des images
                    $base_image_url = 'https://cdc.minesmada.org/view/images/';
                    $username = $userNom. " ".$userPrenom;
                    // Générer l'URL complète
                    $full_image_url = $base_image_url . $image_filename;
                    // Si l'image est trouvée, utiliser son URL complète, sinon utiliser l'image par défaut
                    if (empty($image_url)) {
                        // Extraire les initiales
                        $initials = strtoupper(substr($username, 0, 1) . substr(strrchr($username, " "), 1, 1));
                        $full_image_url = "https://ui-avatars.com/api/?name=$initials&background=299B63&color=fff&size=128";
                    }
                    ?>
                <a href="#" role="button" aria-expanded="false">
                    <img src="<?php echo $full_image_url; ?>" alt="Profile" width="32" height="32"
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