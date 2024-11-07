<?php
// Connexion à la base de données
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');
$id_direction="";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "
    SELECT u.*, di.*, g.* FROM users AS u INNER JOIN direction AS di ON u.id_direction=di.id_direction 
    LEFT JOIN groupe AS g ON g.id_groupe= u.id_groupe WHERE u.id_user = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_direction = $row['id_direction'];
        $id_groupe = $row['id_groupe'];
        $status_user = $row['status_user'];
    } else {
        echo "<p>Utilisateur non trouvé.</p>";
        exit;
    }
    
    $stmt->close();
}
 if (isset($_POST['submit'])) {
        $id_groupe = $_POST['id_groupe'];
        $id_direction= $_POST['id_direction'];
        $id_user = $_POST['id'];
        $status = $_POST['status_user'];
        echo "Status".$status;

        // Mise à jour d'une société existante
        $sql = "UPDATE `users` SET `id_direction`='$id_direction', `id_groupe`='$id_groupe', `status_user`='$status' WHERE `id_user`='$id_user'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $_SESSION['toast_message'] = "Modification réussie.";
            header("Location: https://cdc.minesmada.org/view/gerer_utilisateur/detail.php?id=" . $id_user);
            exit();
        } else {
            echo "Erreur d'enregistrement: " . mysqli_error($conn);
        }
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
                ' . $_SESSION['toast_message'] . '
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
}
if(isset($_SESSION['toast_message2'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                 <img src="../../view/images/warning.jpeg" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                <small class="text-muted">Maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['toast_message'] . '
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message2']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Ministère des Mines</title>
    <!-- Intégration de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css"
        rel="stylesheet">
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
    <?php include_once('../shared/navBar.php'); ?>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col">
                <h4 class="mb-4">Mon compte</h4>
            </div>
            <div class="col text-end">
                <a href="./lister.php" class="btn btn-dark"><i class="bi bi-arrow-left"></i>Retour</a>
            </div>
        </div>
        <?php if ($status_condition != '1') { ?>
        <div class="alert alert-warning" role="alert">
            Pour pouvoir utiliser l'application, veuillez lire et accepter les <a
                href="../../home/condition_utilisation.php" class="href">condition d'utilisation</a> ainsi que la <a
                href="../../home/politique.php" class="href">politiques de confidentialité</a>.
        </div>
        <?php  } else { ?>
        <div class="alert alert-success" role="alert">
            Il a accepté les conditions d'utilisation et la politique de confidentialité le
            <?php echo $date_acceptation ;?>
        </div>
        <?php  }?>

        <!-- Formulaire de modification des informations de l'utilisateur -->
        <div class="row">
            <div class="col-md-2">
                <form action="#" method="post" enctype="multipart/form-data">
                    <!-- Affichage de la photo sélectionnée -->
                    <div class="text-center mb-3">
                        <?php if ($row['photo_profil'] !== NULL) { ?>
                        <img src="<?php echo $row['photo_profil'];?>" id="preview" class="img-fluid rounded"
                            alt="Photo de profil">
                        <?php } else { ?>
                        <img src="../images/default_profil.png" id="preview" class="img-fluid rounded"
                            alt="Photo de profil">
                        <?php } ?>
                    </div>
                </form>
            </div>
            <div class="col-md-10">
                <form method="post" action="">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" id="id" name="id"
                                    value="<?php echo $row['id_user']; ?>">
                                <label for="nom" class="form-label">Nom Utilisateur:</label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                    value="<?php echo $row['nom_user']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prenom(s):</label>
                                <input type="text" class="form-control" id="prenom" name="prenom"
                                    value="<?php echo $row['prenom_user']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label for="email" class="form-label">Adresse e-mail:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $row['mail_user']; ?>">
                            </div>
                            <div class="col">
                                <label for="contact" class="form-label">Telephone:</label>
                                <input type="text" class="form-control" id="contact" name="contact"
                                    value="<?php echo $row['phone_user']; ?>">
                            </div>
                            <div class="col">
                                <label for="fonction" class="form-label">Fonction:</label>
                                <input type="text" class="form-control" id="fonction" name="fonction"
                                    value="<?php echo $row['fonction']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label for="groupe" class="form-label">Matricule:</label>
                                <input type="text" class="form-control" id="contact" name="contact"
                                    value="<?php echo $row['matricule_user']; ?>">
                            </div>
                            <s class="col">
                                <label for="id_direction" class="form-label">Direction:</label>
                                <select class="form-select" name="id_direction" id="id_direction">
                                    <?php $requete = $conn->prepare("SELECT * FROM direction WHERE sigle_direction IN ('DIR.ANSAND', 'DIR.A', 'DR.AA', 'DIR.M', 'DR.SAVA', 'DIR.TO', 'DR.VAK', 'DR.DIANA', 'GU','PCMAII')");
                                    $requete->execute();
                                    $resultat = $requete->get_result();

                                    // Parcourir les résultats et afficher les options du menu déroulant
                                    echo '<option value=""> Selectionner une direction ... </option>';
                                     while ($rowSub = $resultat->fetch_assoc()) {
                                            $selected = ($rowSub["id_direction"] == $id_direction) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_direction'] ."'$selected>". $rowSub['nom_direction']. "</option>";
                                        }
                                    ?>
                                </select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" name="status_user" id="status_user">
                                    <?php $requete = $conn->prepare("SELECT DISTINCT status_user FROM users");
                                    $requete->execute();
                                    $resultat = $requete->get_result();
                                    // Parcourir les résultats et afficher les options du menu déroulant
                                    echo '<option value=""> Selectionner une direction ... </option>';
                                     while ($rowSub = $resultat->fetch_assoc()) {
                                        $selected = ($rowSub["status_user"] == $status_user) ? "selected" : "";
                                        $status_us = ($rowSub["status_user"] == 1) ? "Actif" : "Non actif";
                                        echo "<option value='" . $rowSub['status_user'] ."' $selected>". $status_us . "</option>";
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="col">
                                <label for="groupe" class="form-label">Groupe:</label>
                                <select class="form-select" name="id_groupe" id="id_groupe">
                                    <?php $requete = $conn->prepare("SELECT * FROM groupe");
                                    $requete->execute();
                                    $resultat = $requete->get_result();

                                    // Parcourir les résultats et afficher les options du menu déroulant
                                    echo '<option value=""> Selectionner une direction ... </option>';
                                     while ($rowSub = $resultat->fetch_assoc()) {
                                            $selected = ($rowSub["id_groupe"] == $id_groupe) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_groupe'] ."'$selected>". $rowSub['nom_groupe']. "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="groupe" class="form-label">Mot de passe:</label>
                                <input type="text" class="form-control" value="<?php echo $row['password_user'] ?>">
                            </div>
                        </div>
                    </div><br>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <?php
        include('../../shared/pied_page.php');
    ?>

    <!-- Intégration de Bootstrap JS (facultatif, nécessaire pour certaines fonctionnalités) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.toast').toast('show');
    });
    </script>

</body>

</html>