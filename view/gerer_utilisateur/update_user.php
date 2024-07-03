<?php
// Connexion à la base de données
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');

// Vérifier si un ID d'utilisateur est spécifié dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Récupérer les détails de l'utilisateur depuis la base de données
    $query = "
    SELECT u.*, d.*
    FROM users u
    LEFT JOIN direction d ON u.id_direction = d.id_direction
    WHERE u.id_user = ?";
    
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
    $new_nom = $_POST['nom']; 
    $new_prenom = $_POST['prenom'];
    $new_contact = $_POST['contact']; 
    $new_email = $_POST['email'];
    $new_matricule = $_POST['matricule'];
    $mots_de_passe = $_POST['mots_de_passe'];
    $dbPassword = $_POST['mots_de_passe_actuel'];

    $date = new DateTime();
    $dateActuelle = $date->format('Y-m-d H:i:s');

    $nom_prenom_cleaned = preg_replace('/[^a-zA-Z0-9]/', '_', $new_nom . '_' . $new_prenom . '_' . $dateActuelle);


    if ($mots_de_passe == $dbPassword) {
        // Mettre à jour les informations de l'utilisateur dans la base de données

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) { 
            $uploadDir = '../images/';
            $fileName = "profil_$nom_prenom_cleaned." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo)) {
                // Fichier uploadé avec succès, vous pouvez maintenant insérer le chemin dans la base de données si nécessaire

            } 
            $updateQuery = "
            UPDATE users
            SET nom_user = ?, prenom_user = ?, phone_user = ?, mail_user = ?, matricule_user = ?, photo_profil = ?
            WHERE id_user = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ssisisi",$new_nom, $new_prenom, $new_contact, $new_email, $new_matricule, $photo, $userId);
        } else {
            $updateQuery = "
            UPDATE users
            SET nom_user = ?, prenom_user = ?, phone_user = ?, mail_user = ?, matricule_user = ?
            WHERE id_user = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ssisii",$new_nom, $new_prenom, $new_contact, $new_email, $new_matricule, $userId);
        }

            
        if ($updateStmt->execute()) {
            echo "<p>Les informations de l'utilisateur ont été mises à jour avec succès.</p>";
            header("Location: compte.php");
        } else {
            echo "<p>Erreur lors de la mise à jour des informations de l'utilisateur.</p>";
        }
        
        $updateStmt->close();
    } else {
    // Passwords do not match, display an error message
        $error_message = '<span style="color: red;"><p>Erreur : Le mot de passe fourni '. $mots_de_passe .' ne correspond pas au mot de passe actuel '. $dbPassword .' .</p></span>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modifier Utilisateur</title>
    <!-- Intégration de Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
    // Function to hide the error message after 5 seconds
    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 5000);
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
    <?php include_once('../../shared/header.php'); ?>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="container mt-5">
            <div class="row mb-3">
                <div class="col md-6">
                    <h5 class="mb-4">Mon compte</h5>
                </div>
                <div class="col text-end">
                    <button class="btn btn-success rounded-pill px-3" type="submit">Enregistrer</button>
                </div>
            </div>

            <!-- Formulaire de modification des informations de l'utilisateur -->
            <div class="row">
                <div class="col-md-2">
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

                    <!-- Champ de téléchargement de fichier -->
                    <div class="form-group">
                        <label for="photo">Sélectionnez votre photo de profil</label>
                        <input type="file" class="form-control-file" id="photo" name="photo" onchange="previewImage()">
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom Utilisateur:</label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                    value="<?php echo $row['nom_user']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prenom(s):</label>
                                <input type="text" class="form-control" id="prenom" name="prenom"
                                    value="<?php echo $row['prenom_user']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse e-mail:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo $row['mail_user']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contact" class="form-label">Telephone:</label>
                                <input type="text" class="form-control" id="contact" name="contact"
                                    value="<?php echo $row['phone_user']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="groupe" class="form-label">Matricule:</label>
                                <input type="number" class="form-control" id="matricule" name="matricule"
                                    value="<?php echo $row['matricule_user']; ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="groupe" class="form-label">Mots de passe actuel:</label>
                                <input type="password" class="form-control" id="mots_de_passe" name="mots_de_passe"
                                    required>
                                <input type="hidden" class="form-control" id="mots_de_passe_actuel"
                                    name="mots_de_passe_actuel" value="<?php echo $row['password_user']; ?>" required>
                                <?php if (isset($error_message)) : ?>
                                <p id="error-message" style="color: red;">Erreur : Le mot de passe fourni
                                    <?php $mots_de_passe; ?> ne correspond pas au mot de passe actuel
                                    <?php $dbPassword; ?> .</p>
                                <?php endif; ?>
                            </div>
                        </div><br>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Intégration de Bootstrap JS (facultatif, nécessaire pour certaines fonctionnalités) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    function previewImage() {
        // Sélectionnez l'élément input et l'élément img
        var input = document.getElementById('photo');
        var preview = document.getElementById('preview');

        // Mettez à jour la source de l'image avec la nouvelle image sélectionnée
        var file = input.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    </script>

</body>

</html>