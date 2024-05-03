<?php

// Connexion à la base de données
require(__DIR__ . '/../../scripts/db_connect.php');
require(__DIR__ . '/../../scripts/session.php');
if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
if (isset($_GET['id'])) {
    $id_data_cc= $_GET['id'];

    $sql = "SELECT dcc.*, sexp.*, simp.*
    FROM data_cc dcc
    LEFT JOIN societe_expediteur sexp ON dcc.id_societe_expediteur = sexp.id_societe_expediteur
    LEFT JOIN societe_importateur simp ON dcc.id_societe_importateur = simp.id_societe_importateur
    WHERE dcc.id_data_cc = $id_data_cc;
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row_1 = $result->fetch_assoc();
        $num_facture = $row_1["num_facture"];
        $date_facture = $row_1["date_facture"];

        $nom_societe_expediteur = $row_1["nom_societe_expediteur"];
        $adresse_societe_expediteur = $row_1["adresse_societe_expediteur"];
        $nif_societe_expediteur = $row_1["nif_societe_expediteur"];
        $contact_societe_expediteur = $row_1["contact_societe_expediteur"];
        $email_societe_expediteur = $row_1["email_societe_expediteur"];

        $nom_societe_importateur = $row_1["nom_societe_importateur"];
        $adresse_societe_importateur = $row_1["adresse_societe_importateur"];
        $contact_societe_importateur = $row_1["contact_societe_importateur"];
        $email_societe_importateur = $row_1["email_societe_importateur"]; 
        $pays_destination = $row_1["pays_destination"]; 
    }

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
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
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
    <?php include_once('../../shared/header.php'); ?>

    <div class="container">
        <?php include('./add_contenu_facture.php'); ?>
        <div id="edit_contenu_facture_form"></div>
        <div id="ajout_pv_controle_form"></div>
        <div id="ajout_pv_scellage_form"></div>
        <div id="sow_contenu_form"></div>
        <hr>
        <div class="row mb-3">
            <div class="col">
                <h5>Factures <?php echo $num_facture;?> du <?php echo date('d/m/Y', strtotime($date_facture));?></h5>
            </div>
            <div class="col text-end dropdown">
                <?php 
                // if($groupeID === 3){
                //     echo '<a class="btn btn-dark rounded-pill px-3 btn-ajout_pv_scellage" data-id="' . $id_data_cc . '">Générer PV scellage</a>';
                // } else if($groupeID===1) {
                //     echo '<a class="btn btn-dark rounded-pill px-3 btn-ajout_pv_controle" data-id="' . $id_data_cc . '">Générer PV controle</a>';
                // }
                ?>
                <a class="btn btn-success rounded-pill px-3"
                    href="./exporter_contenu.php?id_data_cc=<?= $id_data_cc ?>">Exporter en
                    excel</a>
                <!-- <a class="btn btn-dark rounded-pill px-3" href="../insert_lp1_permis_e.php">Ajouter une demande</a> -->
                <a class="btn btn-dark rounded-pill px-3 btn-add-contenu-facture"
                    data-id-data-cc="<?= $id_data_cc ?>">Ajouter un contenu</a>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-light" role="alert">
                    <strong class="alert-heading">EXPEDITEUR </strong>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-light">
                            <strong>Nom du société : </strong> <?php echo $nom_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Adresse : </strong> <?php echo $adresse_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>NIF : </strong> <?php echo $nif_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Contact : </strong> <?php echo $contact_societe_expediteur; ?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Mail : </strong> <?php echo $email_societe_expediteur; ?>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-light" role="alert">
                    <strong class="alert-heading">IMPORTATEUR</strong>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-light">
                            <strong>Nom du société : </strong> <?php echo $nom_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Adresse : </strong> <?php echo $adresse_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Contact : </strong> <?php echo $contact_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Mail : </strong> <?php echo $email_societe_importateur;?>
                        </li>
                        <li class="list-group-item list-group-item-light">
                            <strong>Pays de destination : </strong> <?php echo $pays_destination;?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr>

        <?php
        $sql="SELECT cfac.prix_unitaire_facture, cfac.poids_facture
        FROM contenu_facture cfac INNER JOIN data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc WHERE dcc.id_data_cc = $id_data_cc";
        $result = $conn->query($sql);
        $row1 = mysqli_fetch_assoc($result);
        $montant=0;
        while($row1 = mysqli_fetch_assoc($result)){
            $montant += floatval($row1['prix_unitaire_facture']*$row1['poids_facture']);
        }
        $query = "
        SELECT dcc.*, cfac.*, sds.*, s.*, g.*, sds.prix_substance
        FROM contenu_facture cfac
        INNER JOIN data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
        INNER JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON sds.id_substance = s.id_substance
        LEFT JOIN granulo g ON sds.id_granulo = g.id_granulo
        WHERE dcc.id_data_cc = $id_data_cc
        ORDER BY cfac.id_contenu_facture DESC
        ";
        
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
         ?>

        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col" id=""> </th>
                    <th scope="col" id="numLP">Designation</th>
                    <th scope="col" id="nomDirection">Granulo</th>
                    <th scope="col" id="nom_prenom">Poids</th>
                    <th scope="col" id="nomSubstance">Prix unitaire</th>
                    <th scope="col" id="nomSubstance">Prix normale</th>
                    <th scope="col" id="status">Prix total</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo htmlspecialchars($row['nom_substance']) ?></td>
                    <td><?php echo htmlspecialchars($row['nom_granulo']) ?></td>
                    <td><?php echo htmlspecialchars($row['poids_facture']) . ' ' . htmlspecialchars($row['unite_poids_facture']) ?>
                    </td>
                    <?php
                    if($row['prix_unitaire_facture']==$row['prix_substance']){
                       ?><td><?php echo number_format('.$row["prix_unitaire_facture"].', 3, ',', ' ') . ' US $'; ?>
                    </td>;

                    <?php }else{
                        ?><td style="color: red;">
                        <?php echo number_format('.$row["prix_unitaire_facture"].', 3, ',', ' ') . ' US $'; ?></td>;
                    <?php }
                    ?>
                    >
                    <td><?php echo number_format($row['prix_substance'], 2, ',', ' ') . ' US $'; ?></td>
                    <td><?php echo number_format($row['poids_facture'] * $row["prix_unitaire_facture"], 3, ',', ' ') . ' US $' ?>
                    </td>
                    <td>
                        <a class="link-dark btn-sow-contenu" href="#"
                            data-id="<?php echo htmlspecialchars($row["id_contenu_facture"]) ?>">détails</a>
                        <a class="link-dark btn-edit-contenu-facture"
                            data-id-contenu-facture="<?php echo htmlspecialchars($row["id_contenu_facture"]) ?>">
                            <i class="fa-solid fa-pen-to-square me-2"></i></a>
                        <a href="#" class="link-dark"
                            onclick="confirmerSuppression(<?php echo htmlspecialchars($row['id_contenu_facture']) ?>)"><i
                                class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php   
                    }
                } else {
                    echo '<p class="alert alert-info">Aucun résultat trouvé.</p>';
                }
                ?>
            </tbody>
        </table>
        <?php echo "MONTANT TOTAL: ".number_format($montant, 3, ',', ' ') . ' US $' ?>
        <?php
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
        $(".btn-sow-contenu").click(function() {
            var id_data = $(this).data('id');
            showEditForm('sow_contenu_form', './sow_contenu.php?id=' +
                id_data, 'staticBackdrop');

        });
        // Afficher le formulaire modal lorsqu'on clique sur le bouton
        $(".btn-add-contenu-facture").click(function() {
            id_data_cc = $(this).data('id');
            $("#add_contenu_facture").modal('show');
            $("#id_data_cc").val(id_data_cc);
        });
        $(".btn-ajout_pv_controle").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('ajout_pv_controle_form', '../pv_controle/ajout_pv_controle.php?id=' +
                id_data_cc, 'staticBackdrop');

        });
        $(".btn-ajout_pv_scellage").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('ajout_pv_scellage_form', '../pv_scellage/ajout_pv.php?id=' +
                id_data_cc, 'staticBackdrop');

        });
    });

    function showEditForm(editFormId, scriptPath, modalId) {
        $("#" + editFormId).load(scriptPath, function() {
            // Après le chargement du contenu, initialisez le modal manuellement
            $("#" + modalId).modal('show');
        });
    }

    function confirmerSuppression(id) {
        // Utilisation de la fonction confirm pour afficher une boîte de dialogue
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");

        // Si l'utilisateur clique sur "OK", la suppression est effectuée
        if (confirmation) {
            $.ajax({
                url: 'delete.php',
                method: 'POST', // Utilisez la méthode POST pour la suppression
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // Traitez la réponse du serveur ici
                    if (response.success) {
                        // La suppression a réussi
                        alert('Suppression réussie.');
                        // Vous pouvez également effectuer d'autres actions nécessaires après la suppression
                        location.reload();
                    } else {
                        // La suppression a échoué
                        alert('Erreur lors de la suppression : ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression : ' + error);
                }
            });
        } else {
            // Sinon, rien ne se passe
        }
    }
    </script>

    <!-- <script>
        $(document).ready(function(){
            // Afficher le formulaire modal lorsqu'on clique sur le bouton
            $("#btn-modifier-facture").click(function(){
                $("#edit_contenu_facture").modal('show');
            });
        });
    </script> -->
    <!-- <script>
    // Définir les variables (en supposant qu'elles sont définies ailleurs dans le code)
// var id_data_cc;
// var num_facture;
// var date_facture;
// var id_societe_importateur;
// var id_societe_expediteur;

$(document).ready(function() {
    // Initialiser TomSelect pour id_societe_expediteur_edit
    // var id_societe_expediteur_edit_value = new TomSelect("#id_societe_expediteur_edit", {
    //     create: true,
    //     sortField: {
    //         field: "text",
    //         direction: "asc"
    //     }
    // });
    // var id_societe_importateur_edit_value = new TomSelect("#id_societe_importateur_edit", {
    //     create: true,
    //     sortField: {
    //         field: "text",
    //         direction: "asc"
    //     }
    // });

    // Afficher le formulaire modal lorsqu'on clique sur le bouton
    $(".btn-edit-contenu-facture").click(function() {
        id_data_cc = $(this).data('id-data-cc');
        id_contenu_facture = $(this).data('id-contenu-facture');
        id_substance = $(this).data('id-substance');
        id_couleur_substance = $(this).data('id-couleur-substance');
        poids_facture = $(this).data('poids-facture');
        unite_poids_facture = $(this).data('unite-poids-facture');
        prix_unitaire_facture = $(this).data('prix-unitaire-facture');
        granulo_facture = $(this).data('granulo-facture');
        id_degre_couleur = $(this).data('id-degre-couleur');
        id_transparence = $(this).data('id-transparence');
        id_durete_edit = $(this).data('id-durete-edit');
        id_categorie = $(this).data('id-categorie');
        id_forme_substance = $(this).data('id-forme-substance');
        id_dimension_diametre = $(this).data('id-dimension-diametre');
        id_lp1_info = $(this).data('id-lp1-info');
        // Définir les valeurs pour les champs du formulaire
        $("#edit_contenu_facture").modal('show');
        $("#id_data_cc_edit").val(id_data_cc);
        $("#id_contenu_facture_edit").val(id_contenu_facture);
        $("#id_substance_edit").val(id_substance);
        $("#id_couleur_substance_edit").val(id_couleur_substance);
        $("#poids_facture_edit").val(poids_facture);
        $("#unite_poids_facture_edit").val(unite_poids_facture);
        $("#prix_unitaire_facture_edit").val(prix_unitaire_facture);
        $("#granulo_facture_edit").val(granulo_facture);
        $("#id_degre_couleur_edit").val(id_degre_couleur);
        $("#id_transparence_edit").val(id_transparence);
        $("#id_durete_edit_edit").val(id_durete_edit);
        $("#id_categorie_edit").val(id_categorie);
        $("#id_forme_substance_edit").val(id_forme_substance);
        $("#id_dimension_diametre_edit").val(id_dimension_diametre);
        $("#id_lp1_info_edit").val(id_lp1_info);
        // id_societe_expediteur_edit_value.setValue(id_societe_expediteur);
        // id_societe_importateur_edit_value.setValue(id_societe_importateur);
    });
});

</script> -->
    <!-- <script>
    $(document).ready(function () {
        // Fonction pour afficher le formulaire d'ajout de membre
        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function () {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }

        // Associez les fonctions aux clics des boutons
        $(".btn-edit-contenu-facture").click(function () {
            var id_contenu_facture = $(this).data('id-contenu-facture');
            // Assure-toi que les éléments HTML existent avant d'appeler showEditForm
            showEditForm('edit_contenu_facture_form', 'edit_contenu_facture.php?id=' + id_contenu_facture,'edit_contenu_facture');
        });
    });
</script> -->
    <script>
    $(document).ready(function() {
        $(".btn-edit-contenu-facture").click(function() {
            var id_contenu_facture = $(this).data('id-contenu-facture');
            // Assure-toi que les éléments HTML existent avant d'appeler showEditForm
            $("#edit_contenu_facture_form").load('edit_contenu_facture.php?id=' +
                id_contenu_facture,
                function() {
                    // Détache l'événement click du bouton après l'ouverture du modal
                    $(this).off('click');
                    // Initialise le modal
                    $("#edit_contenu_facture").modal('show');
                });
        });
    });
    </script>


    <!-- Inclure les fichiers JavaScript de Bootstrap 5 (pour le bon fonctionnement des composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>