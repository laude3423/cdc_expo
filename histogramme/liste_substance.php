<?php 
require_once('../scripts/db_connect.php');
require('../scripts/session.php');
include './insert_logs.php';
$activite='Consulter les valeurs marchande';
insertLogs($conn, $userID, $activite);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <title>Ministère des Mines</title>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const spinner = document.getElementById('loadingSpinner');
        const table = document.getElementById('agentTable');

        // Afficher le spinner
        spinner.style.display = 'block';
        table.style.display = 'none';

        // Simulation de chargement des données
        setTimeout(() => {
            spinner.style.display = 'none';
            table.style.display = 'table';
        }, 2000); // Changer le délai selon vos besoins
    });
    </script>
    <style>
    th {
        font-size: small;
    }

    td {
        font-size: small;
    }


    #agentTable {
        display: none;
    }
    </style>

    <?php 
    include "../view/shared/navBar.php";
    ?>
</head>

<body>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des substances</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche par nom ...">
            </div>
        </div>
        <hr>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table id="agentTable" class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Substance</th>
                    <th scope="col">Couleur</th>
                    <th scope="col">Granulo</th>
                    <th scope="col">Transparence</th>
                    <th scope="col">Degré de couleur</th>
                    <th scope="col">Forme</th>
                    <th scope="col">Dureté</th>
                    <th scope="col">Catégorie</th>
                    <th scope="col">Dim</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Unité</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql="SELECT sub_detail.*, sub.*, trans.*, forme.*, dure.*, cate.*, diam.*,gra.*, degre.*, coul.*
                FROM substance_detaille_substance sub_detail
                INNER JOIN substance sub ON sub_detail.id_substance = sub.id_substance
                LEFT JOIN transparence trans ON sub_detail.id_transparence = trans.id_transparence
                LEFT JOIN categorie cate ON sub_detail.id_categorie = cate.id_categorie
                LEFT JOIN durete dure ON sub_detail.id_durete = dure.id_durete
                LEFT JOIN couleur_substance coul ON sub_detail.id_couleur_substance = coul.id_couleur_substance
                LEFT JOIN forme_substance forme ON sub_detail.id_forme_substance = forme.id_forme_substance
                LEFT JOIN dimension_diametre diam ON sub_detail.id_dimension_diametre = diam.id_dimension_diametre
                LEFT JOIN granulo gra ON sub_detail.id_granulo = gra.id_granulo
                LEFT JOIN degre_couleur degre ON sub_detail.id_degre_couleur = degre.id_degre_couleur";
                $result= mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                  ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $row['nom_substance']?></td>
                    <td><?php echo $row['nom_couleur_substance']?></td>
                    <td><?php echo $row['nom_granulo']?></td>
                    <td><?php echo $row['nom_transparence']?></td>
                    <td><?php echo $row['nom_degre_couleur']?></td>
                    <td><?php echo $row['nom_forme_substance']?></td>
                    <td><?php echo $row['nom_durete']?></td>
                    <td><?php echo $row['nom_categorie']?></td>
                    <td><?php echo $row['nom_dimension_diametre']?></td>
                    <td><?php echo $row['prix_substance']?></td>
                    <td><?php echo $row['unite_valeur']?></td>
                </tr>
                <?php   
                }

                ?>


                <tr>
            </tbody>
        </table>
        <div>
            <?php
                include('../shared/pied_page.php');
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>