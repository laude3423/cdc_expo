<?php

// Connexion à la base de données
  require_once('../../../scripts/db_connect.php');
  require_once('../../../scripts/session.php');
  if($groupeID!==2){
    require_once('../../../scripts/session_actif.php');
}

if(isset($_SESSION['toast_message'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
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
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
}
if(isset($_SESSION['toast_message2'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="../../view/images/warning.jpeg" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                    <small class="text-muted">Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . $_SESSION['toast_message2'] . '
                </div>
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
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <title>Ministere des mines</title>
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
    <?php include_once('../../view/shared/navBar.php'); ?>

    <div class="container">
        <?php include('./add_facture.php'); ?>
        <?php include('./edit_facture.php'); ?>
        <hr>
        <div class="row mb-3">
            <div class="col">
                <h5>Liste des factures enregistrée d</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche...">
            </div>
            <div class="col text-end">
                <a class="btn btn-success rounded-pill px-3" href="./exporter_facture.php?">Exporter en excel</a>
                <!-- <a class="btn btn-dark rounded-pill px-3" href="../insert_lp1_permis_e.php">Ajouter une demande</a> -->
                <?php
                    if ($groupeID !== 2) {
                        echo '<button class="btn btn-dark rounded-pill px-3" type="button" id="btn-facture" aria-expanded="false">
                    <i class="fa-solid fa-add me-1"></i>Ajouter une facture
                </button>';
                    }?>

            </div>
        </div>
        <hr>
        <?php
        if ($groupeID === 2) {
            $query = "
            SELECT dcc.*, sexp.*, simp.*
            FROM data_cc dcc
            LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
            LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
            LEFT JOIN users u ON dcc.id_user = u.id_user
            ORDER BY dcc.date_modification_facture DESC";

            $result = $conn->query($query);
        
            if ($result->num_rows > 0) {
                echo '<div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" id=""> </th>
                                <th scope="col" id="numLP">N° facture</th>
                                <th scope="col" id="nomDirection">Date facture</th>
                                <th scope="col" id="nom_prenom">Expediteur</th>
                                <th scope="col" id="nomSubstance">Importateur</th>
                                <th scope="col" id="status">Status</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while($row = $result->fetch_assoc()) {
                    echo '<tr>
                    <td>✅</td>
                        <td>'.$row["num_facture"].'</td>
                        <td>'.date("d/m/Y", strtotime($row["date_facture"])).'</td> 
                        <td>'.$row["nom_societe_importateur"].'</td>
                        <td>'.$row["nom_societe_expediteur"].'</td>
                        <td></td>
                        <td class="text-center">
                        <a href="liste_contenu_facture.php?id=' . $row['id_data_cc'] . '" class="link-dark">détails</a>
                           
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
            $query = "
            SELECT dcc.*, sexp.*, simp.*
            FROM data_cc dcc
            LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
            LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
            LEFT JOIN users u ON dcc.id_user = u.id_user
            LEFT JOIN direction dir ON dir.id_direction = u.id_direction 
            WHERE dir.id_direction = $id_direction
            ORDER BY dcc.date_modification_facture DESC";

            $result = $conn->query($query);
        
            if ($result->num_rows > 0) {
                echo '<div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" id=""> </th>
                                <th scope="col" id="numLP">N° facture</th>
                                <th scope="col" id="nomDirection">Date facture</th>
                                <th scope="col" id="nom_prenom">Expediteur</th>
                                <th scope="col" id="nomSubstance">Importateur</th>
                                <th scope="col" id="status">Status</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while($row = $result->fetch_assoc()) {
                    echo '<tr>
                    <td>✅</td>
                        <td>'.$row["num_facture"].'</td>
                        <td>'.date("d/m/Y", strtotime($row["date_facture"])).'</td> 
                        <td>'.$row["nom_societe_importateur"].'</td>
                        <td>'.$row["nom_societe_expediteur"].'</td>
                        <td></td>
                        <td class="text-center">
                        <a href="liste_contenu_facture.php?id=' . $row['id_data_cc'] . '" class="link-dark">détails</a>
                            <a class="link-dark btn-edit-facture" 
                            data-id-data-cc="' . htmlspecialchars($row["id_data_cc"], ENT_QUOTES, 'UTF-8') . '"
                            data-num-facture="' . htmlspecialchars($row["num_facture"], ENT_QUOTES, 'UTF-8') . '"
                            data-date-facture="' . $row["date_facture"] . '"
                            data-id-societe-importateur="' . htmlspecialchars($row["id_societe_importateur"], ENT_QUOTES, 'UTF-8') . '"
                            data-id-societe-expediteur="' . htmlspecialchars($row["id_societe_expediteur"], ENT_QUOTES, 'UTF-8') . '">
                            <i class="fa-solid fa-pen-to-square me-3"></i></a>
                        </td>
                    </tr>';
                        
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
        // Fonction pour trier la table
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

        // Activer la fonctionnalité de tri sur la colonne "Num_LP"
        $('#numLP').on('click', function() {
            sortTable(0); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Nom Direction"
        $('#nomDirection').on('click', function() {
            sortTable(1); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Nom Convoyeur"
        $('#nomConvoyeur').on('click', function() {
            sortTable(2); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Nom Substance"
        $('#nomSubstance').on('click', function() {
            sortTable(3); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });

        // Activer la fonctionnalité de tri sur la colonne "Status"
        $('#status').on('click', function() {
            sortTable(4); // Utilisez le numéro de l'index de colonne pour le tri (0-based)
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        // Afficher le formulaire modal lorsqu'on clique sur le bouton
        $("#btn-facture").click(function() {
            $("#add_facture").modal('show');
        });
    });
    </script>
    <!-- <script>
        $(document).ready(function(){
            // Afficher le formulaire modal lorsqu'on clique sur le bouton
            $("#btn-modifier-facture").click(function(){
                $("#edit_facture").modal('show');
            });
        });
    </script> -->
    <script>
    // Définir les variables (en supposant qu'elles sont définies ailleurs dans le code)
    var id_data_cc;
    var num_facture;
    var date_facture;
    var id_societe_importateur;
    var id_societe_expediteur;

    $(document).ready(function() {
        // Initialiser TomSelect pour id_societe_expediteur_edit
        var id_societe_expediteur_edit_value = new TomSelect("#id_societe_expediteur_edit", {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
        var id_societe_importateur_edit_value = new TomSelect("#id_societe_importateur_edit", {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Afficher le formulaire modal lorsqu'on clique sur le bouton
        $(".btn-edit-facture").click(function() {
            id_data_cc = $(this).data('id-data-cc');
            num_facture = $(this).data('num-facture');
            date_facture = $(this).data('date-facture');
            id_societe_importateur = $(this).data('id-societe-importateur');
            id_societe_expediteur = $(this).data('id-societe-expediteur');

            // Définir les valeurs pour les champs du formulaire
            $("#edit_facture").modal('show');
            $("#id_data_cc").val(id_data_cc);
            $("#num_facture_edit").val(num_facture);
            $("#date_facture_edit").val(date_facture);
            $("#id_societe_importateur_edit").val(id_societe_importateur);
            id_societe_expediteur_edit_value.setValue(id_societe_expediteur);
            id_societe_importateur_edit_value.setValue(id_societe_importateur);
        });
    });
    </script>


    <!-- Inclure les fichiers JavaScript de Bootstrap 5 (pour le bon fonctionnement des composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>