<?php 
require_once('../scripts/db_connect.php');
require('../scripts/session.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
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


    <title>Ministere des mines</title>
    <?php 
    include "../shared/nav.php";
    ?>
</head>

<body>
    <div class="container">
        <hr>
        <h5>Liste des logs</h5>
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
                    <th scope="col">Date</th>
                    <th scope="col">Adresse email</th>
                    <th scope="col">Direction</th>
                    <th scope="col">Adresse IP</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT l.*, u.*, di.* 
                FROM `logs` AS l 
                LEFT JOIN users u ON l.user_id = u.id_user 
                LEFT JOIN direction AS di ON di.id_direction = u.id_direction 
                WHERE DATE(l.created_at) = CURDATE()";
                $result= mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $mail_user = $row['user_id'] === null ? 'inconnu' : $row['mail_user'];
                    $nom_direction = $row['user_id'] === null ? 'inconnu' : $row['nom_direction'];
                    $datetime = new DateTime($row['created_at']);
                    // Formatage de la date au format '24-10-2024'
                    $date = $datetime->format('d/m/Y');
                    // Addition de 3 heures à l'heure
                    $datetime->modify('+3 hours');
                    $time = $datetime->format('H:i:s');
                  ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $time." ".$date ?></td>
                    <td><?php echo $mail_user;?></td>
                    <td><?php echo $nom_direction; ?></td>
                    <td><?php echo $row['adressIP'] ?></td>
                    <td><?php echo $row['action'] ?></td>
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


    <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>