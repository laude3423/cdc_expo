<?php
// Connexion à la base de données
  require_once('../../scripts/db_connect.php');
  require_once('../../scripts/connect_db_lp1.php');
$currentYear = date('Y');
$years = range($currentYear - 6, $currentYear);
$annee = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;
  $sql = "SELECT * FROM contenu_facture WHERE quantite_lp1_actuel_lp1_suivis=0 AND id_lp1_info IS NOT NULL";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $id_lp_info = $row['id_lp1_info'];

            $sql = "SELECT * FROM lp_info WHERE id_lp='$id_lp_info' AND expire_lp IS NULL";
            $result2 = $conn_lp1->query($sql);
            if($result2 && $result2->num_rows > 0){
                $row2 = $result2->fetch_assoc();
                $id_lp= $row2['id_lp'];

                $query = "UPDATE lp_info SET expire_lp = 'oui' WHERE id_lp = ?";
                $stmt = $conn_lp1->prepare($query);
                $stmt->bind_param("i", $id_lp);
                $stmt->execute();
            }
    }
}
    
  $sql = "SELECT cfac.id_ancien_lp FROM contenu_facture AS cfac LEFT JOIN ancien_lp AS anc
  ON cfac.id_ancien_lp=anc.id_ancien_lp  WHERE quantite_lp1_actuel_lp1_suivis = 0 AND anc.expiration IS NULL";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        $query = "UPDATE ancien_lp SET expiration = 'oui' WHERE id_ancien_lp = ?";
        $stmt = $conn->prepare($query);

        while ($row = $result->fetch_assoc()) {
            $id_contenu_facture = $row['id_ancien_lp'];
            $stmt->bind_param("i", $id_contenu_facture);
            $stmt->execute();
        }
    }
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
                <h5>Liste des factures enregistrée</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche par numéro...">
            </div>
            <div class="col">
                <form method="GET" action="">
                    <select id="yearSelect" class="form-select" name="id" onchange="this.form.submit()">
                        <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year; ?>" <?php echo ($year == $annee) ? 'selected' : ''; ?>>
                            <?php echo $year; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="col text-end">
                <a class="btn btn-success rounded-pill px-3" href="./exporter_facture.php?" style="font-size: 90%;"><i
                        class="fas fa-file-excel"></i> Exporter en excel</a>
                <!-- <a class="btn btn-dark rounded-pill px-3" href="../insert_lp1_permis_e.php">Ajouter une demande</a> -->
                <?php
                    if ($groupeID !== 2) {
                        echo '<button class="btn btn-dark rounded-pill px-3"  id="btn-facture" style="font-size: 90%;">
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
            LEFT JOIN users u ON dcc.id_user = u.id_user WHERE YEAR(dcc.date_facture) = $annee AND num_facture IS NOT NULL
            ORDER BY dcc.date_modification_facture DESC";

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
                                <th scope="col" >N° facture</th>
                                <th class="masque2" scope="col" >Expediteur</th>
                                <th class="masque2" scope="col" >Importateur</th>
                                <th scope="col" >Status</th>
                                <th scope="col" >Validation</th>
                                <th scope="col" >Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                        if($row["validation_facture"]=='Validé'){
                            echo'<td>✅</td>';
                        }else{
                            echo'<td>⚠️</td>';
                        }
                        echo '<td>'.$row["num_facture"].'</td> 
                        <td class="masque2">'.$row["nom_societe_importateur"].'</td>
                        <td class="masque2">'.$row["nom_societe_expediteur"].'</td>';
                        if(empty($row["num_pv_controle"])){
                            echo'<td>incomplet</td>';
                        }else{
                            echo'<td>complet</td>';
                        }
                        echo'<td>'.$row["validation_facture"].'</td>';
                        echo '<td>
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
            WHERE dir.id_direction = $id_direction YEAR(dcc.date_facture) = $annee AND num_facture IS NOT NULL
            ORDER BY dcc.date_modification_facture DESC";

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
                                <th scope="col" id=""></th>
                                <th scope="col" id="">N° Facture</th>
                                <th scope="col" id="nomDirection">Date facture</th>
                                <th class="masque2" scope="col" id="nom_prenom">Expediteur</th>
                                <th class="masque2" scope="col" id="nomSubstance">Importateur</th>
                                <th scope="col" id="status">Status</th>
                                <th scope="col" id="numLP">Validation</th>
                                <th scope="col" >Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    if($row["validation_facture"]=='Validé'){
                            echo'<td>✅</td>';
                        }else if($row["validation_facture"]=='À Refaire'){
                            echo'<td>❌</td>';
                        }else{
                            echo'<td>⚠️</td>';
                        }
                        echo '<td>'.$row["num_facture"].'</td>
                        <td>'.date("d/m/Y", strtotime($row["date_facture"])).'</td> 
                        <td class="masque2">'.$row["nom_societe_importateur"].'</td>
                        <td class="masque2">'.$row["nom_societe_expediteur"].'</td>';
                        if(empty($row["num_pv_controle"])){
                            echo'<td>incomplet</td>';
                        }else{
                            echo'<td>complet</td>';
                        }
                         echo'<td>'.$row["validation_facture"].'</td>';
                            if ($row['validation_facture']!='Validé') {
                                echo '<td>
                                <a href="liste_contenu_facture.php?id=' . $row['id_data_cc'] . '" class="link-dark">détails</a>
                                    <a class="link-dark btn-edit-facture" 
                                    data-id-data-cc="' . htmlspecialchars($row["id_data_cc"], ENT_QUOTES, 'UTF-8') . '"
                                    data-num-facture="' . htmlspecialchars($row["num_facture"], ENT_QUOTES, 'UTF-8') . '"
                                    data-date-facture="' . $row["date_facture"] . '"
                                    data-id-societe-importateur="' . htmlspecialchars($row["id_societe_importateur"], ENT_QUOTES, 'UTF-8') . '"
                                    data-id-societe-expediteur="' . htmlspecialchars($row["id_societe_expediteur"], ENT_QUOTES, 'UTF-8') . '">
                                    <i class="fa-solid fa-pen-to-square me-3"></i></a>
                                </td>';
                            } else {
                                    echo '<td>
                                    <a href="liste_contenu_facture.php?id=' . $row['id_data_cc'] . '" class="link-dark">détails</a>
                                    <a href="#" class="link-dark" data-toggle="tooltip"
                                    title="Modification non autorisée : La facture est déjà validée">
                                    <i class="fa-solid fa-pen-to-square me-3"></i></td>';
                            }
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