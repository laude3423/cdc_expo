<?php
// Connexion à la base de données
require_once('../../scripts/connect_db.php');
require_once('../../scripts/session_admin.php');

// Vérifier si un ID d'utilisateur est spécifié dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Récupérer les détails de l'utilisateur depuis la base de données
    $query = "SELECT u.*, d.* FROM users u
    LEFT JOIN directions d ON u.id_direction = d.id_direction
    WHERE u.ID_user = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Utilisateur non trouvé.</p>";
        exit;
    }
    
    $stmt->close();
} else {
    echo "<p>Aucun ID d'utilisateur spécifié.</p>";
    exit;
}

// Traitement de la soumission du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'];
    $newGroupe = $_POST['groupe'];

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $updateQuery = "
    UPDATE users
    SET status_user = ?, id_groupe = ?
    WHERE id_user = ?";
    
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sii",$newStatus, $newGroupe, $userId);
    
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
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
    </style>
</head>

<body>
    <?php include_once('../scripts/header.php'); ?>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col md-6">
                <h4 class="mb-4">Modifier Utilisateur</h4>
            </div>
            <div class="col text-end">
                <a href="show_user.php" class="btn btn-secondary">Retour aux détails de l'utilisateur</a>
            </div>
        </div>
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
                <!-- Formulaire de modification des informations de l'utilisateur -->
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
                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse e-mail:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $row['mail_user']; ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="contact" class="form-label">Telephone:</label>
                                <input type="text" class="form-control" id="contact" name="contact"
                                    value="<?php echo $row['phone_user']; ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="contact" class="form-label">Groupe:</label>
                                <select class="form-select" id="groupe" name="groupe">
                                    <option value="1" <?php if ($row['id_groupe'] == 1) echo "selected"; ?>>Utilisateur
                                    </option>
                                    <option value="3" <?php if ($row['id_groupe'] == 3) echo "selected"; ?>>Directeur
                                    </option>
                                    <option value="4" <?php if ($row['id_groupe'] == 4) echo "selected"; ?>>DGAM
                                    </option>
                                </select>
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
                                <select class="form-select" id="status" name="status">
                                    <option value="0" <?php if ($row['status_user'] == 0) echo "selected"; ?>>Non Actif
                                    </option>
                                    <option value="1" <?php if ($row['status_user'] == 1) echo "selected"; ?>>Actif
                                    </option>
                                    <option value="2" <?php if ($row['status_user'] == 2) echo "selected"; ?>>Bloqued
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="password" class="form-label">Mots de pass:</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password"
                                        value="<?php echo $row['password_user']; ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i> <!-- Icône Bootstrap pour l'œil -->
                                    </button>
                                </div>
                            </div>
                        </div><br>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Enregistrer les
                                    modifications</button><br><br>
                            </div>
                            <div class="col text-end">
                                <a class="btn btn-danger rounded-pill px-3" href="#"
                                    onclick="confirmerSuppression('<?php echo $row['id_user']; ?>')">Supprimer
                                    l'utilisateur</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>




    </div>
    <script>
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');

    togglePasswordButton.addEventListener('click', function() {
        passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
        togglePasswordButton.innerHTML = (passwordInput.type === 'password') ? '<i class="bi bi-eye"></i>' :
            '<i class="bi bi-eye-slash"></i>';
    });
    </script>

    <!-- Intégration de Bootstrap JS (facultatif, nécessaire pour certaines fonctionnalités) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
    function confirmerSuppression(id) {
        // Utilisation de la fonction confirm pour afficher une boîte de dialogue
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");

        // Si l'utilisateur clique sur "OK", la suppression est effectuée
        if (confirmation) {
            window.location.href = '../scripts/delete_user.php?id=' + id;
        } else {
            // Sinon, rien ne se passe
        }
    }
    </script>
</body>

</html>