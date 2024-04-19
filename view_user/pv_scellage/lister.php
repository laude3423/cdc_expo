<?php 
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $expediteur = $_POST['expediteur'];
        $destination = $_POST["destination"];
        $facture = $_POST["id"];
        $nombre = $_POST["nombre"];
        $lieu_sce = $_POST["lieu_sce"];
        $lieu_emb = $_POST["lieu_emb"];
        $numDom = $_POST["numDom"];
        $declaration = $_POST["declaration"];
        $date_declaration = $_POST["date_declaration"];
        $num_lp3 = $_POST["num_lp3"];
        $date_lp3 = $_POST["date_lp3"];
        $chef = $_POST["chef"];
        $police = $_POST["police"];
        $douane = $_POST["douane"];
        $qualite = $_POST["qualite"];
        $agent_scellage= $_POST["agent_scellage"];
        $type_colis= $_POST["type_colis"];

        $id_data = $_POST['id'];
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');

        $date_creation="";
        $num_pv_scellage="";
        $num_pv="";
        $codeSql = "SELECT dcc.date_creation_pv_scellage, dcc.num_pv_scellage 
            FROM data_cc AS dcc
            INNER JOIN users AS us ON us.id_user = dcc.id_user
            LEFT JOIN direction AS di ON us.id_direction = di.id_direction
            WHERE dcc.id_data_cc = (SELECT MAX(id_data_cc) FROM data_cc)
            AND num_pv_scellage IS NOT NULL AND di.id_direction = $id_direction";
        $stmt1 = $conn->prepare($codeSql);
        $stmt1->execute();
        $resu1 = $stmt1->get_result();
            if ($resu1->num_rows > 0) {
                $rowss = mysqli_fetch_assoc($resultCode);
                $date_creation = $rowss['date_creation_pv_scellage'];
                $num_pv_scellage = $rowss['num_pv_scellage'];
                $parts = explode("-", $num_pv_scellage);
                // Si la chaîne a bien été divisée
                if(count($parts) === 2) {
                $incrementation = substr($parts[0], 2); // "0001"
                } else {
                echo "La chaîne n'a pas pu être divisée comme prévu.";
                }
                $nouvelle_incrementation = intval($incrementation) + 1;
                $nouvelle_incrementation_formattee = sprintf("%03d", $nouvelle_incrementation);
                $anneeFacture = date('Y', strtotime($date_creation));
                $moisFacture = date('m', strtotime($date_creation));
                if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
                $num_pv = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/DEV/PSC/GU";
                }else{
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/PSC/GU";
                }
            }else{
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/PSC/GU";
            }
            echo $num_pv;
        // recherche
        $query = "SELECT num_pv_scellage FROM data_cc WHERE id_data_cc = $facture AND num_pv_scellage IS NOT NULL";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $resu = $stmt->get_result();
            if ($resu->num_rows === 0) {
                include "../generate_fichier/generate_insert_scellage.php";
                include "./traitement.php";
            } else {
                $_SESSION['toast_message2'] = "Le numéro de facture que vous avez choisi est déjà enregistré.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        
    }
    //modification
    
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
    <?php 
    include "../../shared/header.php";
    ?>
    <style>
    #spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #7983ff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    </style>


</head>

<body>
    <div class="container">
        <div class="row mb-3" style="margin-top: 30px;">
            <div class="col md-8 mb-3">
                <h5>Liste des Procès-Verbal de Scellage</h5>
            </div>
            <div class="col md-10 text-end">
                <a class="btn btn-success btn-sm rounded-pill px-3 mb-3" href="../cdc/exporter.php?">Exporter en
                    excel</a>
                <a class="btn btn-dark btn-sm rounded-pill px-3 mb-3" href="#" onclick="openModal()"><i
                        class="fa-solid fa-add me-1"></i>Ajouter nouveau</a>
            </div>
        </div>
        <?php 
        $sql='';
        if($groupeID===2){
            $sql="SELECT datacc.*, societe_imp.*, societe_exp.* FROM data_cc datacc
            INNER JOIN  societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
            INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
            LEFT JOIN users us ON datacc.id_user = us.id_user
            LEFT JOIN direction di ON us.id_direction=di.id_direction
            WHERE datacc.num_pv_scellage IS NOT NULL
            ORDER BY datacc.date_modification_pv_scellage DESC";
        }else{
            $sql="SELECT datacc.*, societe_imp.*, societe_exp.* FROM data_cc datacc
            INNER JOIN  societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
            INNER JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
            LEFT JOIN users us ON datacc.id_user = us.id_user
            LEFT JOIN direction di ON us.id_direction=di.id_direction
            WHERE datacc.num_pv_scellage IS NOT NULL AND di.id_direction=$id_direction
            ORDER BY datacc.date_modification_pv_scellage DESC";
        }
        
            $result= mysqli_query($conn, $sql);
            if ($result->num_rows > 0) {
            ?>
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Numéro de PV de scellage</th>
                    <th scope="col">Société expéditeur</th>
                    <th scope="col">Numéro Facture</th>
                    <th scope="col">Numéro DOM</th>
                    <th scope="col">Destination finale</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $row['num_pv_scellage'] ?></td>
                    <td><?php echo $row['nom_societe_expediteur'] ?></td>
                    <td><?php echo $row['num_facture'] ?></td>
                    <td><?php echo $row['num_domiciliation'] ?></td>
                    <td><?php echo $row['pays_destination'] ?></td>
                    <td>
                        <a class="link-dark detail_pv_scellage"
                            href="detail.php?id=<?php echo $row['id_data_cc']?>">détails</a>
                        <a href="#" class="link-dark btn_edit_pv_scellage"
                            data-id="<?= htmlspecialchars($row["id_data_cc"])?>"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                    </td>
                </tr>
                <?php   
                    }
                } else {
                    echo '<p class="alert alert-info">Aucun résultat trouvé.</p>';
                }
                ?>


                <tr>
            </tbody>
        </table>
    </div>
    <div id="edit_pv_scellage_form"></div>
    <?php 
    include('./ajout_pv.php');
    ?>


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
            vider();

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

        $(".btn_edit_pv_scellage").click(function() {
            var id_data_cc = $(this).data('id');
            showEditForm('edit_pv_scellage_form', './edit_pv.php?id=' + id_data_cc, 'staticBackdrop2');

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

    function openModal() {
        myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });

        // Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs
        document.getElementById('staticBackdropLabel').innerText = 'Modifier un PV de scellage';

        // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
        document.getElementById('staticBackdropLabel').innerText = 'Nouvelle PV de scellage';
        document.getElementById('expediteur').value = '';
        document.getElementById('destination').value = '';
        document.getElementById('facture').value = '';
        document.getElementById('lieu_sce').value = '';
        document.getElementById('lieu_emb').value = '';
        document.getElementById('nombre').value = '';
        document.getElementById('numDom').value = '';

        myModal.show();
        selectTom();
    }



    function selectTom() {
        // Initialisez TomSelect pour chaque élément select
        var selectOptions = {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            }
        };

        new TomSelect("#expediteur", selectOptions);
        new TomSelect("#destination", selectOptions);
        new TomSelect("#facture", selectOptions);
        new TomSelect("#agent_scellage", selectOptions);
        new TomSelect("#qualite", selectOptions);
        new TomSelect("#douane", selectOptions);
        new TomSelect("#police", selectOptions);
        new TomSelect("#chef", selectOptions);

    };

    function vider() {
        $('#id').val('');
        $('#num').val('');
        societe_value.setValue('Choisir ...');
        destination_value.setValue('');
        facture_value.setValue('');
        $('#numDom').val('');
        $('#nombre').val('');
        $('#lieu_sce').val('');
        $('#lieu_emb').val('');
    }
    </script>
</body>

</html>