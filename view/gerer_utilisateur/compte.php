<?php
// Connexion à la base de données
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');

    // Récupérer les détails de l'utilisateur depuis la base de données
    $query = "
    SELECT u.*, d.*
    FROM users u
    LEFT JOIN direction d ON u.id_direction = d.id_direction
    WHERE u.id_user = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Utilisateur non trouvé.</p>";
        exit;
    }
    
    $stmt->close();

// Traitement de la soumission du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les nouvelles valeurs des champs de formulaire
    // $newNom = $_POST['nom'];
    // $newPrenom = $_POST['prenom'];
    // $newEmail = $_POST['email'];
    // $newContact = $_POST['contact'];
    // $newGroupe = $_POST['groupe'];
    $newStatus = $_POST['status'];

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $updateQuery = "
    UPDATE users
    SET status_user = ?
    WHERE id_user = ?";
    
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si",$newStatus, $userId);
    
    if ($updateStmt->execute()) {
        echo "<p>Les informations de l'utilisateur ont été mises à jour avec succès.</p>";
        header("Location: show_user.php");
    } else {
        echo "<p>Erreur lors de la mise à jour des informations de l'utilisateur.</p>";
    }
    
    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modifier Utilisateur</title>
    <!-- Intégration de Bootstrap CSS -->
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
            <div class="col md-6">
                <h4 class="mb-4">Mon compte</h4>
            </div>
            <div class="col text-end">
                <a class="btn btn-dark rounded-pill px-3"
                    href="update_user.php?id=<?php echo $row["id_user"]; ?>">Mettre à jour le profil</a>
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
            Vous avez accepté les conditions d'utilisation et la politique de confidentialité le
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
                                <label for="nom" class="form-label">Nom Utilisateur:</label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                    value="<?php echo $row['nom_user']; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prenom(s):</label>
                                <input type="text" class="form-control" id="prenom" name="prenom"
                                    value="<?php echo $row['prenom_user']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label for="email" class="form-label">Adresse e-mail:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $row['mail_user']; ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="contact" class="form-label">Telephone:</label>
                                <input type="text" class="form-control" id="contact" name="contact"
                                    value="<?php echo $row['phone_user']; ?>" readonly>
                            </div>
                            <div class="col">
                                <label for="fonction" class="form-label">Fonction:</label>
                                <input type="text" class="form-control" id="fonction" name="fonction"
                                    value="<?php echo $row['fonction']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="groupe" class="form-label">Matricule:</label>
                                <input type="text" class="form-control" id="contact" name="contact"
                                    value="<?php echo $row['matricule_user']; ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="groupe" class="form-label">Direction:</label>
                                <input type="text" class="form-control" id="contact" name="contact"
                                    value="<?php echo $row['nom_direction']; ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status:</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="<?php
                                switch ($row['status_user']) {
                                    case 0:
                                        echo 'Non actif';
                                        break;
                                    case 1:
                                        echo 'Actif';
                                        break;
                                    case 2:
                                        echo 'Bloquer';
                                        break;
                                    default:
                                        echo 'Statut inconnu';
                                        break;
                                }
                            ?>" readonly>

                            </div>
                            <div class="col-md-3">
                                <label for="groupe" class="form-label">Groupe:</label>
                                <input type="text" class="form-control" id="groupe" name="groupe"
                                    value="<?php echo getRoleName($row['id_groupe']); ?>" readonly>

                                <?php
                            // Fonction pour obtenir le nom du rôle en fonction de l'id_groupe
                            function getRoleName($id_groupe) {
                                switch ($id_groupe) {
                                    case 1:
                                        return 'Utilisateur';
                                    case 2:
                                        return 'Admin';
                                    case 3:
                                        return 'Guichet Uniques';
                                    case 4:
                                        return 'DGAM';
                                    default:
                                        return 'Inconnu';
                                }
                            }
                            ?>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>
        </div>


        <div class="alert alert-light" role="alert">
            <li>Lire les <a href="../../home/condition_utilisation.php" class="href">condition d'utilisation</a></li>
            <li>Lire les <a href="../../home/politique.php" class="href">politiques de confidentialité</a>
            </li>
        </div>
    </div>


    <!-- Intégration de Bootstrap JS (facultatif, nécessaire pour certaines fonctionnalités) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

</body>

</html>