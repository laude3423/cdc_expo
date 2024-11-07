<?php

  require_once('../../scripts/db_connect.php');
  require_once('../../scripts/session.php');
  if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
if(isset($_SESSION['toast_message'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="../../view/images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
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

    <title>Ministere des mines</title>
    <style>
    #agentTable {
        display: none;
    }

    td {
        font-size: small;
    }

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
    <?php include_once('../../view/shared/navBar.php'); ?>

    <div class="container">
        <?php include('./add_attestation.php'); ?>
        <hr>
        <div class="row mb-3">
            <div class="col">
                <h5>Liste des attestation des valeurs</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche par numéro...">
            </div>
            <div class="col text-end">
                <a class="btn btn-success rounded-pill px-3" href="./exporter_attestation.php?"
                    style="font-size: 90%;"><i class="fas fa-file-excel"></i> Exporter</a>
                <a class="btn btn-dark rounded-pill px-3" href="../gerer_substance/lister.php?" style="font-size: 90%;">
                    Substance</a>
                <!-- <a class="btn btn-dark rounded-pill px-3" href="../insert_lp1_permis_e.php">Ajouter une demande</a> -->
                <?php
                    if ($groupeID !== 2) {
                        echo '<button class="btn btn-dark rounded-pill px-3"  id="btn-attestation" style="font-size: 90%;">
                    <i class="fa-solid fa-add me-1"></i>Nouvelle attestation
                </button>';
                    }?>

            </div>
        </div>
        <hr>
        <?php
        if ($groupeID === 2) {
            $query = "SELECT dcc.*, sexp.*, simp.*
            FROM data_cc dcc
            LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
            LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
            LEFT JOIN users u ON dcc.id_user = u.id_user WHERE num_attestation IS NOT NULL
            ORDER BY dcc.date_attestation DESC";

            $result = $conn->query($query);
        
            if ($result->num_rows > 0) {
                echo '
                <div id="loadingSpinner" class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div  class="table-responsive">
                    <table id="agentTable" class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" > </th>
                                 <th scope="col">N° Attestation</th>
                                <th scope="col">Date attestation</th>
                                 <th scope="col">Importateur</th>
                                  <th scope="col">Expediteur</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                        echo'<td>✅</td>';
                        $num_attestation = (int)$row['num_attestation'];
                    echo '<td>'.str_pad($num_attestation, 3, '0', STR_PAD_LEFT).'</td>';
                       echo '<td>'.date("d/m/Y", strtotime($row["date_attestation"])).'</td>';
                         echo'<td>'.$row["nom_societe_importateur"].'</td>';
                          echo'<td>'.$row["nom_societe_expediteur"].'</td>';
                         if(!empty($row['num_pv_controle'])){
                            echo'<td>Complet</td>';
                         }else{
                            echo'<td>Incomplet</td>';
                         }
                        echo '<td>
                        <a href="liste_contenu_attestation.php?id=' . $row['id_data_cc'] . '" class="link-dark">détails</a>
                           
                        </td>
                    </tr>';
                        
                }
                
                echo '</tbody>
                    </table>
                </div>';
            } else {
                echo '<p class="alert alert-info">Aucun résultat trouvé.</p>';
            }
        } else {
            $query = "SELECT dcc.*, sexp.*, simp.*
            FROM data_cc dcc
            LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
            LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
            LEFT JOIN users u ON dcc.id_user = u.id_user
            LEFT JOIN direction dir ON dir.id_direction = u.id_direction 
            WHERE dir.id_direction = $id_direction AND num_attestation IS NOT NULL
            ORDER BY dcc.date_attestation DESC";

            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                echo '
                <div id="loadingSpinner" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
                <div class="table-responsive">
                    <table id="agentTable" class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">N° Attestation</th>
                                <th scope="col">Date attestation</th>
                                 <th scope="col">Importateur</th>
                                  <th scope="col">Expediteur</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    $num_attestation = (int)$row['num_attestation'];
                    echo '<td>'.str_pad($num_attestation, 3, '0', STR_PAD_LEFT).'</td>';
                       echo '<td>'.date("d/m/Y", strtotime($row["date_attestation"])).'</td>';
                         echo'<td>'.$row["nom_societe_importateur"].'</td>';
                         echo'<td>'.$row["nom_societe_expediteur"].'</td>';
                         if(!empty($row['num_pv_controle'])){
                            echo'<td>Complet</td>';
                         }else{
                            echo'<td>Incomplet</td>';
                         }
                        if ($row['validation_attestation'] == 'Validé') {
                                 echo '<td>
                                    <a href="liste_contenu_attestation.php?id=' . $row['id_data_cc'] . '" class="link-dark">détails</a>
                                    <a href="#" class="link-dark" data-toggle="tooltip"
                                    title="Modification non autorisée : L\'attestation est déjà validée">
                                    <i class="fa-solid fa-pen-to-square me-3"></i></td>';
                            } else {
                                ?><td>
            <a href="liste_contenu_attestation.php?id=<?php echo $row['id_data_cc']; ?>" class="link-dark">détails</a>
            <a href="#" class="link-dark btn_edit_attestation" data-id="<?= htmlspecialchars($row["id_data_cc"])?>">
                <i class="fa-solid fa-pen-to-square me-3"></i>
            </a>
        </td>
        <?php }
                            echo '</tr>';
                        
                                }
                
                echo '</tbody>
                    </table>
                </div>';
            } else {
                echo '<p class="alert alert-info">Aucun résultat trouvé.</p>';
            }
        }
        $conn->close();
        ?>
        <div>
            <?php
                include('../../shared/pied_page.php');
            ?>
        </div>
        <div id="edit_attestation_form"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    // Filtrer la table en fonction de la sélection du menu déroulant "Nom Direction"
    $('#searchDirection').on('change', function() {
        var selectedDirection = $(this).val().toLowerCase();
        filterTable(1,
            selectedDirection); // Utilisez le numéro de l'index de colonne pour le filtrage (0-based)
    });

    // Fonction pour filtrer la table
    function filterTable(columnIndex, filterValue) {
        var table, rows, i, x;
        table = document.querySelector('.table');
        rows = table.rows;

        for (i = 1; i < rows.length; i++) {
            x = rows[i].getElementsByTagName("TD")[columnIndex];

            if (filterValue === '' || x.innerHTML.toLowerCase().indexOf(filterValue) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
    </script>
    <script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.toast').toast('show');

        function sortTable(columnIndex) {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.querySelector('.table');
            switching = true;

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;

                    x = rows[i].getElementsByTagName("TD")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }


    });
    </script>
    <script>
    $(document).ready(function() {
        // Afficher le formulaire modal lorsqu'on clique sur le bouton
        $("#btn-attestation").click(function() {
            $("#add_attestation").modal('show');
        });
    });
    </script>
    <!-- <script>
        $(document).ready(function(){
            // Afficher le formulaire modal lorsqu'on clique sur le bouton
            $("#btn-modifier-attestation").click(function(){
                $("#edit_attestation").modal('show');
            });
        });
    </script> -->
    <script>
    // Définir les variables (en supposant qu'elles sont définies ailleurs dans le code)
    var num_attestation;
    var date_attestation;
    var id_societe_importateur;
    var id_societe_expediteur;

    $(document).ready(function() {
        // Initialiser TomSelect pour id_societe_expediteur_edit
        $(".btn_edit_attestation").click(function() {
            var id = $(this).data('id');
            showEditForm('edit_attestation_form', './edit_attestation.php?id=' + id,
                'staticBackdrop2');

        });

        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });
    </script>


    <!-- Inclure les fichiers JavaScript de Bootstrap 5 (pour le bon fonctionnement des composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>