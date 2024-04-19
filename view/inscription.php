<?php
// Connexion à la base de données
include('../scripts/connect_db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_user = $_POST["nom_user"];
    $prenom_user = $_POST["prenom_user"];
    $mail_user = $_POST["mail_user"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $contact_user = $_POST["contact_user"];
    $direction = $_POST["direction"];
    $matricule = $_POST["matricule"];
    
    // Vérification si l'adresse e-mail existe déjà
    $check_query = "SELECT COUNT(*) AS count FROM users WHERE mail_user = '$mail_user'";
    $check_result = $conn->query($check_query);
    $row = $check_result->fetch_assoc();
    $email_count = $row['count'];
    $validation = 0;
    $groupe=1;

    if ($email_count > 0) {
        echo '<script>alert("L\'adresse e-mail existe déjà dans la base de données.");</script>';
    } else {
        // Vérification de la case à cocher
        $validation = isset($_POST["validation"]) ? 1 : 0;

        $insert_query = "INSERT INTO users (nom_user, prenom_user, mail_user, phone_user, password_user, matricule_user, status_user, id_direction, id_groupe) VALUES ('$nom_user', '$prenom_user', '$mail_user', '$contact_user', '$mot_de_passe','$matricule', '$validation', '$direction', '$groupe')";

        if ($conn->query($insert_query) === TRUE) {
            echo "Utilisateur inséré avec succès.";
            header("Location: ../index.php");
            exit;
        } else {
            echo "Erreur lors de l'insertion de l'utilisateur : " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <!-- Intégration de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        background-color: #f0f0f0; /* Couleur de fond */
        }
        
        .centered-container {
            text-align: center;
        }
        
        .rounded-border {
            background-color: #fff; /* Couleur de fond du cadre */
            border-radius: 10px; /* Bordure arrondie */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Ombre légère */
            padding: 20px;
            width: 100%;
            max-width: 600px; /* Largeur maximale du cadre */
        }
    </style>
    <script>
        function validatePassword() {
            var password = document.getElementById("mot_de_passe").value;
            var confirmPassword = document.getElementById("confirmation_mot_de_passe").value;
            if (password != confirmPassword) {
                alert("Le mot de passe et la confirmation du mot de passe ne correspondent pas.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    
    <main>
        <br/>
        <br/>
            <div class="rounded-border">
                <div class="centered-container">
                    <img src="../logo/mmrs.jfif" alt="Logo" height="100" class="mr-3">
                    <h4 class="mb-3">Inscription</h4>
                </div>
                    
                        
                    <form method="post" action="" class="needs-validation" onsubmit="return validatePassword();">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="nom_user" class="form-label">Nom</label>
                                <input type="text" name="nom_user" id="nom_user" class="form-control" required>
                                <div class="invalid-feedback">
                                    Veuillez entrer un nom d'utilisateur.
                                </div>
                            </div>
            
                            <div class="col-sm-6">
                                <label for="prenom_user" class="form-label">Prenoms</label>
                                <input type="text" name="prenom_user" id="prenom_user" class="form-control" required>
                            <div class="invalid-feedback">
                                Veuillez entrer un Prenom(s) d'utilisateur.
                            </div>
                        </div>
            
                            <div class="col-12">
                                <label for="mail_user" class="form-label">Adresse e-mail<span class="text-body-secondary"> * </span></label>
                                <input type="email" name="mail_user" id="mail_user" class="form-control" required placeholder="you@example.com">
                                <div class="invalid-feedback">
                                    Please enter a valid email address for shipping updates.
                                </div>
                            </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="mot_de_passe" class="form-label">Mots de Passe</label>
                                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please enter your Password.
                                </div>
                                
                            </div>
                            <div class="col-sm-6">
                                <label for="confirmation_mot_de_passe" class="form-label">Confirmation mots de Passe</label>
                                <input type="password" name="confirmation_mot_de_passe" id="confirmation_mot_de_passe" class="form-control" required>
                                <div class="invalid-feedback">
                                    Please enter your Password.
                                </div>
                                
                            </div>
                        </div>
                            <div class="col-12">
                                <label for="contact_user" class="form-label">Telephone <span class="text-body-secondary">*</span></label>
                                <input type="text" name="contact_user" id="contact_user" class="form-control" required pattern="[0-9]+" title="Veuillez entrer uniquement des chiffres sans espace">
                            </div>
            
                            <div class="col-md-5">
                                <label for="mtricule" class="form-label">Matricule:</label>
                                <input type="number" name="matricule" id="matricule" class="form-control" required>
                                <div class="invalid-feedback">
                                    Veuillez sélectionner un matricule.
                                </div>
                            </div>
            
                        
                        <div class="col-12">
                            <label for="societe">Direction :</label>
                            <select class="form-control" name="direction" id="direction" required>
                                <?php
                                include('connect_db.php');
                                $requete = $conn->prepare('SELECT * FROM directions');
                                $requete->execute();
                                $resultat = $requete->get_result();
    
                                // Parcourir les résultats et afficher les options du menu déroulant
                                echo '<option value=""> Selectionner une direction ... </option>';
                                while ($row = $resultat->fetch_assoc()) {
                                    echo '<option value="' . $row['id_direction'] . '">' . $row['nom_direction'] . '</option>';
                                }
    
                                $requete->close();
                                $conn->close();
                                ?>
                            </select>
                        </div>
                    </div>
                        <hr class="my-4">
                        
            
                        <button class="w-100 btn btn-primary" type="submit">S'enregister</button>
                        <hr class="my-4">
                    </form>
                <p class="text-center mt-3">Déjà inscrit ? <a href="../index.php">Connectez-vous</a></p>
            </div>
        </div>
    </div>
    
    <!-- Intégration de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('.needs-validation');
    
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
    
                form.classList.add('was-validated');
            });
        });
    </script>
    </main>
</body>
</html>
