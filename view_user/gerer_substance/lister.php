<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php
$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;

    if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];
        $type_substance = $_POST['type_substance'];
        $id_substance = $_POST['id'];

        if (empty($id_substance)) {
            // Insertion d'une nouvelle société
            $sql = "INSERT INTO `substance2`(`nom_substance`, `id_type_substance`) VALUES ('$nom', '$type_substance')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
            }
        } else {
            // Mise à jour d'une société existante
            $sql = "UPDATE `substance2` SET `nom_substance`='$nom', `id_type_substance`='$type_substance' WHERE id_substance='$id_substance'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $_SESSION['toast_message'] = "Modification réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
            }
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-...VotreClé...==" crossorigin="anonymous" />
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    include "../../view/shared/navBar.php";
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
                <input type="text" id="search" class="form-control" placeholder="Recherche...">
            </div>
            <div class="col text-end">
                <a class="btn btn-dark btn-sm rounded-pill px-3 " href="../attestation_valeur/liste_attestation.php"><i
                        class="bi bi-arrow-left"></i>Retour</a>
                <a class="btn btn-dark btn-sm rounded-pill px-3 " href="#" onclick="openModal()"><i
                        class="fa-solid fa-add me-1"></i>Ajouter nouveau</a>
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
                    <th scope="col">Type de la substance</th>
                    <th scope="col">Code de type de la substance</th>
                    <th scope="col">Nom de la substance</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql="SELECT sub.* , typesub.* FROM substance2 sub INNER JOIN type_substance typesub ON sub.id_type_substance= typesub.id_type_substance";
                $result= mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                  ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $row['nom_type_substance'] ?></td>
                    <td><?php echo $row['code_type_substance'] ?></td>
                    <td><?php echo $row['nom_substance'] ?></td>
                    <td>
                        <a href="#" class="link-dark" onclick="openModal(<?php echo $row['id_substance']?>)"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="#" class="link-dark"
                            onclick="confirmerSuppression(<?php echo $row['id_substance']?>)"><i
                                class="fa-solid fa-trash "></i></a>
                    </td>
                </tr>
                <?php   
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle substance</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="type_substance" name="type_substance" class="col-form-label">Type de la
                                substance:</label>
                            <select class="form-select" id="type_substance" name="type_substance" required>
                                <option value="">Sélectionner...</option>
                                <?php 
                                    $query = "SELECT * FROM type_substance";
                                    $result = $conn->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['id_type_substance'] . '">' . $row['nom_type_substance'] . '</option>';
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nom" name="nom" class="col-form-label">Nom de la substance:</label>
                            <input type="text" class="form-control" name="nom" id="nom"
                                placeholder="Nom de la substance" required style="font-size:90%">
                        </div>
                        <input type="hidden" id="id" name="id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                            <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    var myModal;
    var closeModalAfterSubmit = false; // Variable pour vérifier si la modal doit être fermée

    // Fonction pour fermer la modal et actualiser la page si nécessaire
    function closeModal() {
        console.log("Fermeture de la modal");
        if (myModal) {
            myModal.hide();
            if (closeModalAfterSubmit) {
                location.reload(); // Actualiser la page après la fermeture de la modal
            }
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

        if (edit_id) {
            console.log('ID' + edit_id)
            // Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Modifier substance';
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
                        $('#id').val(data.id_substance);
                        $('#nom').val(data.nom_substance);
                        $('#type_substance').val(data.id_type_substance);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de la récupération des données : ' + error);
                    }
                });
            }
            getDataById(id);
        } else {
            // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Nouvelle substance';
            document.getElementById('nom').value = '';
            document.getElementById('type_substance').value = '';
            document.getElementById('id').value = '';
        }

        myModal.show();
    }
    </script>
</body>

</html>