<?php

// Connexion à la base de données
require(__DIR__ . '/../../scripts/db_connect.php');
require(__DIR__ . '/../../scripts/session.php');
require(__DIR__ . '/../../histogramme/insert_logs.php');
if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
$validation_contenu ="";
$validation_v = $fonctionUsers. ' ' . $nom_user. ' '.$prenom_user;
if (isset($_GET['id'])) {
    $id_data_cc= $_GET['id'];
    $sql2 = "SELECT * FROM contenu_attestation WHERE validation_contenu='Validé' AND id_data_cc = $id_data_cc";
    $result = $conn->query($sql2);
    if ($result->num_rows > 0) {
        $valide = 'avec';
    }
    // $sql3 = "SELECT * FROM contenu_attestation WHERE validation_contenu ='refaire' AND id_data_cc = $id_data_cc";
    // $result = $conn->query($sql3);
    // if ($result->num_rows > 0) {
    //     $refaire = 'avec';
    // }
    $sql4 = "SELECT * FROM contenu_attestation WHERE id_data_cc = $id_data_cc";
    $result = $conn->query($sql4);
    if ($result->num_rows > 0) {
        $row_1 = $result->fetch_assoc();
        $validation_contenu = $row_1['validation_contenu'];
        $user_validation = $row_1['users_validation'];
    }
    $sql4 = "SELECT * FROM data_cc WHERE id_data_cc = $id_data_cc";
    $result = $conn->query($sql4);
    if ($result->num_rows > 0) {
        $row_details = $result->fetch_assoc();
        $num_pv_controle = $row_details['num_pv_controle'];
    }

}else{
    echo 'vide';
}
if (isset($_POST['submit'])) {
        $id_data_cc = $_POST['id_data'];
        $action = $_POST['action'];
        $activite="Validation de l'attestation";
        $sql="UPDATE `contenu_attestation` SET `validation_contenu`='$action', `users_validation`='$validation_v' WHERE id_data_cc=$id_data_cc";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            insertLogs($conn, $userID, $activite);
            $_SESSION['toast_message'] = "Modification réussie.";
             header("Location: ./liste_contenu_attestation.php?id=" . $id_data_cc);
             //header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
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
<?php 

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
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <title>Ministere des mines</title>
    <style>
    .partie {
        display: inline;
    }

    .partie1 .partie2 {
        display: inline;
    }

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

    .th .td {
        font-size: small;
    }

    .h4 {
        font-size: 20px;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    #infon1 #info2 {
        display: inline-block;
    }

    .info1 {
        width: 49%;
        float: left;

    }

    .link-dark {
        margin: 0;
        padding: 0;
    }

    .btn-sow-contenu,
    .btn-edit-contenu-attestation,
    .link-dark {
        display: inline-block;
        vertical-align: middle;
    }

    .info2 {
        width: 49%;
        float: right;

    }

    @media screen and (max-width: 800px) {

        .infon1,
        .info2 {
            display: block;
        }

        .info1 {
            width: 100%;
        }

        .info2 {
            width: 100%;
        }
    }

    .bfooter {
        margin-left: 40%;
        position: fixed;
    }
    </style>
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
</head>

<body>
    <?php include_once('../../view/shared/navBar.php'); 
   
    $compte="";?>

    <div class=" info container">
        <div id="edit_contenu_attestation_form"></div>
        <div id="ajout_pv_controle_form"></div>
        <div id="ajout_pv_contenu_dire"></div>
        <div id="edit_contenu_societe_form"></div>
        <div id="sow_contenu_form"></div>
        <h6 style="text-align: center;">Liste des contenus de l'attestations N°
            <?php $num_attestation = $row_details['num_attestation'];
            echo str_pad($num_attestation, 3, '0', STR_PAD_LEFT);?> du
            <?php echo date('d/m/Y', strtotime($row_details['date_attestation']));?></h6>
        <hr>
        <div class="partie d-flex justify-content-between align-items-center">
            <div class="partie1">
                <?php 
                if ($groupeID !== 2) {
                        if (($validation_contenu=='Validé')&&(empty($num_pv_controle))) {
                            echo '<a class="btn btn-dark rounded-pill px-3 btn-generer-controle" 
                                    data-id="' . $id_data_cc . '">
                                    <i class="fa-solid fa-add me-1"></i>Générer PV contrôle</a>';
                        }
                        if(!empty($num_pv_controle)){
                            echo '
                                <div class="dropdown">
                                    <button type="button" class="btn btn-dark rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Voir les détails associer
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="../pv_controle_gu/detail.php?id=' . $id_data_cc.'">Voir PV de controle</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="../pv_controle/detail.php?id=' . $id_data_cc.'">Voir la certificat de conformité</a></li>
                                    </ul>
                                </div>
                            ';
                        }
                    }      
                    ?>
            </div>
            <div class=" partie2 text-end">
                <a class="btn btn-success rounded-pill px-3"
                    href="./exporter_contenu.php?id_data_cc=<?= $id_data_cc ?>"><i class="fas fa-file-excel"></i>
                    Exporter en excel</a>
                <a class="btn btn-dark rounded-pill px-3" href="../gerer_substance/lister.php?" style="font-size: 90%;">
                    Substance</a>

                <?php
                        if ($groupeID !== 2) {
                        if (empty($valide)){
                                echo '<a class="btn btn-dark rounded-pill px-3 btn-add-contenu-societe" 
                                        data-id="' . $id_data_cc . '">
                                        <i class="fa-solid fa-add me-1"></i>Ajouter nouveau
                                    </a>';
                            }
                        }
                        ?>
            </div>
        </div>
        <hr>
        <?php if($groupeID!==2){
            if((empty($num_pv_controle)&&(($code_fonction=='A')||$code_fonction=='B')&&(($validation_contenu =='En attente')||(empty($validation_contenu))))){
        ?>
        <form action="" method="post">
            <?php
            // Supposons que $selectedValue contient la valeur récupérée de la base de données.
            $selectedValue = $validation_contenu; // Exemple de valeur
            function isSelected($value, $selectedValue) {
                return $value === $selectedValue ? 'selected' : '';
            }
            ?>
            <div class="row">
                <div class="col">
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data" id="id_data">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire</option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php
            }else if($validation_contenu=='En attente'){
                echo '<p class="alert alert-info">Status: En attente.</p>';
            }else {
                echo '<p class="alert alert-info">Status: '.$validation_contenu.', Validateur: '.$user_validation.'.</p>';
            }
        }else if($groupeID===2){
               ?>
        <form action="" method="post">
            <?php
             function isSelected($value, $selectedValue) {
                return $value === $selectedValue ? 'selected' : '';
            }
                    // Supposons que $selectedValue contient la valeur récupérée de la base de données.
                    $selectedValue = $validation_contenu; // Exemple de valeur
                    ?>
            <div class="row">
                <div class="col">
                    <input type="hidden" value="<?php echo $id_data_cc; ?>" name="id_data" id="id_data">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire</option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php 
        }
    ?>
        <hr>
        <?php
        $query = "SELECT  catt.*, s.*
        FROM contenu_attestation catt
        INNER JOIN data_cc dcc ON dcc.id_data_cc = catt.id_data_cc
        LEFT JOIN substance2 s ON catt.id_substance = s.id_substance
        LEFT JOIN lp_scan lp ON catt.id_lp_scan = lp.id_lp_scan
        WHERE dcc.id_data_cc = $id_data_cc
        ORDER BY catt.id_contenu_attestation DESC";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
         ?>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="info1">
            <table id="agentTable" class="table  table-hover text-center" style="font-size: small;">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"> </th>
                        <th scope="col">Designation</th>
                        <th class="masque2" scope="col">Poids</th>
                        <th class="masque" scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <tr>
                        <?php if($row["validation_contenu"]=='Validé'){
                            echo'<td>✅</td>';
                        }else if($row["validation_contenu"]=='À Refaire'){
                            echo'<td>❌</td>';
                        }else if($row["validation_contenu"]=='Exporté'){
                            echo'<td>🔒</td>';
                        }else{
                            echo'<td>⚠️</td>';
                        } ?>
                        <td><?php echo $row['nom_substance']; ?></td>
                        <td><?php echo $row['poids_attestation'].' '.$row['unite']; ?></td>
                        <td>
                            <?php if($row["validation_contenu"]=='Exporté') { ?>
                            <a href="#" class="link-dark" data-toggle="tooltip" title="L'attestation est déjà validée">
                                <i class="fa-solid fa-pen-to-square me-3"></i>
                            </a>
                            <a href="#" data-toggle="tooltip" title="L'attestation est déjà validée" class="link-dark">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            <?php } else {?>
                            <a href="#" class="link-dark btn_edit_attestation"
                                data-id="<?= htmlspecialchars($row["id_contenu_attestation"])?>">
                                <i class="fa-solid fa-pen-to-square me-3"></i>
                            </a><a href="#" onclick="confirmerSuppression(<?php echo $row['id_contenu_attestation']?>)"
                                class="link-dark">
                                <i class="fa-solid fa-trash"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php 
            $query = "SELECT  catt.*, lp.*
        FROM contenu_attestation catt
        LEFT JOIN lp_scan lp ON catt.id_lp_scan = lp.id_lp_scan
        WHERE catt.id_data_cc = $id_data_cc";
        $result = $conn->query($query);
        $row = mysqli_fetch_assoc($result);
        if(!empty($row['scan_lp'])){ ?>
            <p><strong>Information sur LP:</strong> <a
                    href="../view_user/<?php echo $row['scan_lp']; ?>"><?php echo $row['numero_lp']; ?>.pdf</a>
                <?php } ?>
        </div>
        <?php
            } else {
                echo '<div class="info1"><p class="alert alert-info">Aucune contenu de la attestation.</p></div>';
            }
            ?>

        <div class="info2">
            <div class="alert alert-light" role="alert">
                <?php
                    if(!empty($row_details['pj_attestation'])){
                            $pdfFilePath = $row_details['pj_attestation'];
                    include "../cdc/convert.php";
                    }else{
                        echo ' <p class="alert alert-info">Aucun scan de la attestation trouvé.</p>';
                    }
                    
                ?>
            </div>
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

    function toggleStatus(userId) {
        var confirmation = confirm("Voulez-vous vraiment terminer la mise à jour de la attestation ?");
        if (confirmation) {
            $.ajax({
                url: 'update_validation.php',
                type: 'POST',
                data: {
                    userId: userId
                },
                success: function(response) {
                    location.reload(); // Par exemple, recharger la page pour refléter les changements
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la mise à jour du statut:', error);
                }
            });
        }
    }
    </script>

    <script>
    $(document).ready(function() {
        $('.toast').toast('show');
        $(".btn_edit_attestation").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('edit_contenu_attestation_form',
                './scripts_attestation/edit_contenu_attestation.php?id=' + id_data_cc,
                'staticBackdrop2');

        });
        $(".btn-add-contenu-societe").click(function() {
            var id = $(this).data('id');
            showEditForm('edit_contenu_societe_form', './add_societe.php?id=' + id,
                'staticBackdrop_societe');

        });
        $(".btn-sow-contenu").click(function() {
            var id_data = $(this).data('id');
            showEditForm('sow_contenu_form', './sow_contenu.php?id=' +
                id_data, 'staticBackdrop');

        });
        // Afficher le formulaire modal lorsqu'on clique sur le bouton
        $(".btn-add-contenu-attestation").click(function() {
            var id_data_cc = $(this).data('id-data-cc'); // Remplace 'id' par 'id-data-cc'
            $("#add_contenu_attestation").modal('show');
            $("#id_data_cc").val(id_data_cc);
            console.log('consulter ' + id_data_cc);
        });
        $(".btn-add-contenu-dire").click(function() {
            id_data_cc = $(this).data('id-data-cc'); // Correction ici
            showEditForm('ajout_pv_contenu_dire', './add_contenu_direction.php?id=' + id_data_cc,
                'staticBackdrop3');
        });
        $(".btn-generer-controle").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('ajout_pv_controle_form', './generate_pv/ajout_pv.php?id=' +
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
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");
        console.log(id);
        if (confirmation) {
            $.ajax({
                url: 'delete.php',
                method: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Suppression réussie.');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression : ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression : ' + error);
                    alert('Erreur lors de la suppression : ' + error);
                }
            });
        }
    }
    </script>

    <!-- Inclure les fichiers JavaScript de Bootstrap 5 (pour le bon fonctionnement des composants) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>