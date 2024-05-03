<?php 
include "db_connect.php";
    if(isset($_POST['submit'])){
        $nom= $_POST['nom'];
        $prenom= $_POST['prenom'];
        $email= $_POST['email'];
        $password= $_POST['password'];

        $sql= "INSERT INTO `users`( `nom_user`, `prenom_user`, `email_user`, `mdp_user`) VALUES (NULL,'$nom','$prenom','$email','$password')";

        $result = mysqli_query($conn, $sql);

        if($result){
            header("Location: index.php?msg=Enregistrement effectué");
        }else{
            echo "Erreur d'enregistrement".mysqli_error($conn);
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap-->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>CRUD PHP</title>
</head>
<body>
    <nav class="navbar navbar-light justify-content-center fs3 mb-5" style="background-color:#283747 ; color: white">
        CRUD compléte en PHP
    </nav>
    <div class="container">
        <div class="text-center mb-4">
            <h3>Ajout nouveau utilisateur</h3>
            <p class="text-muted">Veuillez compléter le formulaire</p>
        </div>
        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px">
                <div class="mb-3">
                    <label class="firm-label">Nom:</label>
                    <input type="text" class="form-control" name="nom" placeholder="Veuillez saisir votre nom">
                </div>
                <div class="mb-3">
                    <label class="firm-label">Prénom(s):</label>
                    <input type="text" class="form-control" name="prenom" placeholder="Veuillez saisir votre prénom(s)">
                </div>
                <div class="mb-3">
                    <label class="firm-label">Adresse email</label>
                    <input type="text" class="form-control" name="email" placeholder="Veuillez saisir votre adresse email">
                </div>
                <div class="mb-3">
                    <label class="firm-label">Mot de passe:</label>
                    <input type="text" class="form-control" name="password" placeholder="Veuillez saisir votre mot de passe">
                </div>
                <div>
                    <button type="submit" class="btn btn-success" name="submit">Enregistrer</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
        </form>
        </div>
    </div>
<!--Bootstrap-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>