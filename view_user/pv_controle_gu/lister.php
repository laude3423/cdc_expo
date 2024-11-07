<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
if($groupeID!==2){
    require_once('../../scripts/session_actif.php');
}
?>

<?php

$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;

    if (isset($_POST['submit'])) {
        $id_data = $_POST["id_data"];
        $mode_emballage = $_POST["nombre"];
        $lieu_controle = $_POST["lieu_controle"];
        $num_pv='';
        $num_cc='';
            //construction de num pv de controle
        $dateFormat = "Y-m-d";
        $dateInsert = date($dateFormat);
        $codeSql="SELECT date_creation_pv_controle, num_cc, num_pv_controle FROM data_cc WHERE id_data_cc = $id_data";
            $stmt1 = $conn->prepare($codeSql);
            $stmt1->execute();
            $resu1 = $stmt1->get_result();
            if($resu1->num_rows !== 0){
                $row = $resu1->fetch_assoc();
                $num_pv = $row['num_pv_controle'];
                $num_cc = $row['num_cc'];
            }
            //appelle de création de fichier
            include '../generate_fichier/generate_insert_controle.php';
             // Mise à jour
            $sql = "UPDATE `data_cc` SET `lieu_controle_pv`='$lieu_controle',`lien_pv_controle`='$pathToSave',`pj_pv_controle`='$pathToSavePDF',`mode_emballage`='$mode_emballage',
            `date_modification_pv_controle`='$dateInsert',`num_pv_controle`='$num_pv',
            `num_cc`='$num_cc',`date_cc`='$dateInsert',`lien_cc`='$lien_cc',`pj_cc`='$pj_cc' WHERE id_data_cc=$id_data";
            $result = mysqli_query($conn, $sql);
             if ($result) {
                $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
                exit();
            } else {
            echo "Erreur d'enregistrement" . mysqli_error($conn);
            }
    }
    //modification
    
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
$edit_societe_details = array();

if (!empty($edit_societe_id)) {
    $sql_edit = "SELECT * FROM `categorie` WHERE `id_categorie`='$edit_societe_id'";
    $result_edit = mysqli_query($conn, $sql_edit);

    if ($result_edit) {
        $edit_societe_details = mysqli_fetch_assoc($result_edit);
    } else {
        echo "Erreur lors de la récupération des détails de la société" . mysqli_error($conn);
    }
}
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

    <title>Ministere des mines</title>
    <?php include_once('../../view/shared/navBar.php'); ?>
    <style>
    #agentTable {
        display: none;
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
    <div class="container">
        <hr>
        <div class="row mb-3" style="margin-top: 30px;">
            <div class="col">
                <h5>Liste des P.V de constantation et de contrôle</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche par numéro...">
            </div>
            <div class="col md-10 text-end">
                <a class="btn btn-success btn-sm rounded-pill px-3 mb-3" href="../cdc/exporter.php?"
                    style="font-size: 90%;"><i class="fas fa-file-excel"></i> Exporter en excel</a>
            </div>
        </div>
        <hr>
        <?php 
        $sql='';
        if($groupeID===2){
            $sql="SELECT datacc.*, societe_imp.*, societe_exp.*
                  FROM data_cc datacc
                  INNER JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
                  INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
                  LEFT JOIN users us ON datacc.id_user = us.id_user
                  LEFT JOIN direction di ON us.id_direction=di.id_direction
                  WHERE datacc.num_pv_controle IS NOT NULL
                  ORDER BY datacc.date_modification_pv_controle DESC";
        }else{
            $sql="SELECT datacc.*, societe_imp.*, societe_exp.*
                  FROM data_cc datacc
                  INNER JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
                  INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
                  LEFT JOIN users us ON datacc.id_user = us.id_user
                  LEFT JOIN direction di ON us.id_direction=di.id_direction
                  WHERE datacc.num_pv_controle IS NOT NULL AND di.id_direction=$id_direction
                  ORDER BY datacc.date_modification_pv_controle DESC";
        }
        $result= mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
            ?>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table id="agentTable" class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Numéro de PV</th>
                    <th class="masque2" scope="col">Date</th>
                    <th class="masque2" scope="col">Numéro Facture</th>
                    <th class="masque1" scope="col">Société expéditeur</th>
                    <th class="masque1" scope="col">Destination</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php  while($row = mysqli_fetch_assoc($result)){
                    ?>
                <tr>
                    <?php  if( $row['validation_controle']=='Validé'){
                    ?>
                    <td>✅</td>
                    <?php  }
                    else if($row["validation_controle"]=='À Refaire'){
                            echo'<td>❌</td>';
                        } else{?>
                    <td>⚠️</td>
                    <?php }?>
                    <td><?php echo $row['num_pv_controle'] ?></td>
                    <td class="masque2"><?php echo date('d/m/Y', strtotime($row['date_creation_pv_controle'])); ?></td>
                    <?php if(empty($row['num_facture'])){?>
                    <td class="masque2">Non commerçant</td>
                    <?php }else{ ?>
                    <td class="masque2"><?php echo $row['num_facture'] ?></td>
                    <?php }?>
                    <td class="masque1"><?php echo $row['nom_societe_expediteur'] ?></td>
                    <td class="masque1"><?php echo $row['pays_destination'] ?></td>
                    <td><?php echo $row['validation_controle'] ?></td>
                    <td>
                        <a class="link-dark detail_pv_scellage"
                            href="detail.php?id=<?php echo $row['id_data_cc']?>">détails</a>
                        <?php if($groupeID !=2){
                            if ($row['validation_controle'] != 'Validé') {
                                ?>
                        <a href="#" class="link-dark btn_edit_pv_controle"
                            data-id="<?= htmlspecialchars($row["id_data_cc"])?>"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <?php
                            } else {
                                    ?>
                        <a href="#" class="link-dark" data-toggle="tooltip"
                            title="Modification non autorisée : PV déjà validé">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>
                        <?php
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php   
                    }
                }else{
                    echo '<p class="alert alert-info">Aucun résultat trouvé.</p>';
                }
                ?>


                <tr>
            </tbody>
        </table>
        <div>
            <?php
                include('../../shared/pied_page.php');
            ?>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier PV de controle</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nombre" name="nombre" class="col-form-label">Nombre et mode d'emballage:</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                placeholder="Nombre de colis" required style="font-size:90%">
                        </div>
                        <div class="mb-3">
                            <label for="lieu_controle" name="lieu_controle" class="col-form-label">Lieu de
                                controle:</label>
                            <input type="text" class="form-control" name="lieu_controle" id="lieu_controle"
                                placeholder="Lieu de controle" required style="font-size:90%">
                            <input type="hidden" id="id" name="id" value="<?php echo $id_data_cc; ?>">
                            <input type="hidden" id="id_data" name="id_data">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                            <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="edit_pv_controle_form"></div>
    <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    var myModal;
    var closeModalAfterSubmit = false; // Variable pour vérifier si la modal doit être fermée

    // Fonction pour fermer la modal et actualiser la page si nécessaire
    function closeModal() {
        if (myModal) {
            myModal.hide();

        }
    }

    // Fonction pour confirmer la suppression
    function confirmDeletion() {
        // Ici, vous pouvez ajouter le code PHP pour effectuer la suppression
        // Par exemple, vous pouvez utiliser une requête AJAX pour appeler un script PHP de suppression
        console.log("Suppression confirmée");
        closeModal(); // Fermer la modale après la confirmation
    }
    $(document).ready(function() {
        $('.toast').toast('show');
        $('[data-toggle="tooltip"]').tooltip();

        $(".btn_edit_pv_controle").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('edit_pv_controle_form', '../pv_controle/edit_pv.php?id=' + id_data_cc,
                'staticBackdrop2');

        });

        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });

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


    function openModal(edit_id = null) {
        myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });
        console.log(edit_id + 'Mety');
        if (edit_id) {
            // Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Modifier un PV de controle';
            var id = edit_id;

            function getDataById(id) {
                $.ajax({
                    url: 'get_data.php',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#id_data').val(data.id_data_cc);
                        $('#nombre').val(data.mode_emballage);
                        $('#lieu_controle').val(data.lieu_controle_pv);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de la récupération des données : ' + error);
                    }
                });
            }
            getDataById(id);
            myModal.show();
        }
    }
    </script>
</body>

</html>