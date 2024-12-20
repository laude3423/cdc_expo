<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php 

$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;

    if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $direction= $_POST['direction'];
        $id_user = $_POST['id'];

        if (empty($id_user)) {
            // Insertion d'une nouvelle société
            $sql = "INSERT INTO `users`(`nom_user`, `prenom_user`, `mail_user`, `password_user`, `status_user`, `date_acceptation`, `status_condition`, `photo_profil`, `id_direction`) VALUES ('$nom','$prenom','$email','$password','$status','','','','$direction')";
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
            $sql = "UPDATE `users` SET `nom_user`='$nom',`prenom_user`='$prenom',`mail_user`='$email',`password_user`='$password',`status_user`='$status',`id_direction`='$direction' WHERE `id_user`='$id_user'";
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
                <img src="../images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
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
    $sql_edit = "SELECT * FROM `users` WHERE `id_users`='$edit_societe_id'";
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
    #agentTable {
        display: none;
    }
    </style>
    <title>Ministere des mines</title>
    <?php 
    include "../shared/navBar.php";
    ?>


</head>

<body>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des utilisateurs</h5>
            </div>
            <div class="col">
                <input type="text" id="search2" class="form-control" placeholder="Recherche par direction">
            </div>
            <div class="col text-end">
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
                    <th scope="col">Nom</th>
                    <th class="masque1" scope="col">prenom</th>
                    <th class="masque1" scope="col">Téléphone</th>
                    <th class="masque1" scope="col">Email</th>
                    <th scope="col">Groupe</th>
                    <th scope="col">Direction</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql="SELECT user.*, gp.*, dir.* FROM users AS user INNER JOIN groupe gp ON user.id_groupe=gp.id_groupe
                    LEFT JOIN direction dir ON dir.id_direction=user.id_direction";
                    $result= mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)){
                    ?>
                <tr>
                    <td><?php echo $row['status_user'] === '1'  ? '✅' : '🔒'; ?></td>
                    <td><?php echo $row['nom_user'] ?></td>
                    <td class="masque1"><?php echo $row['prenom_user'] ?></td>
                    <td class="masque1"><?php echo $row['phone_user'] ?></td>
                    <td class="masque1"><?php echo $row['mail_user'] ?></td>
                    <td><?php echo $row['nom_groupe'] ?></td>
                    <td><?php echo $row['nom_direction'] ?></td>
                    <td>
                        <a class="link-dark" href="./detail.php?id=<?php echo $row['id_user']; ?>">détails</a>
                        <?php if ($row['status_user'] === '1'): ?>
                        <a href="#" class="btn-sm btn btn-success"
                            onclick="toggleStatus(<?php echo $row['id_user'] ?>, 'desactive')">Désactiver</a>
                        <?php else: ?>
                        <a href="#" class="btn-sm btn btn-success"
                            onclick="toggleStatus(<?php echo $row['id_user'] ?>, 'active')">Activer</a>
                        <?php endif; ?>

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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle société</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="societeForm" action="" method="post">
                        <div class="mb-3">
                            <label for="nom" name="nom" class="col-form-label">Nom de la société:</label>
                            <input type="text" class="form-control" name="nom" id="nom" placeholder="Nom complète"
                                required style="font-size:90%">
                        </div>
                        <div class="mb-3">
                            <label for="prenom" name="prenom" class="col-form-label">prenom de la société:</label>
                            <input type="text" class="form-control" id="prenom" name="prenom"
                                placeholder="prenom complète" required style="font-size:90%">
                        </div>
                        <div class="mb-3">
                            <label for="password" name="password" class="col-form-label">password de la société:</label>
                            <input type="number" class="form-control" id="password" name="password"
                                placeholder="Numéro de téléphone" required style="font-size:90%" pattern="[0-9]{10}">
                        </div>
                        <div class="mb-3">
                            <label for="email" name="email" class="col-form-label">Email de la société:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="prenom email"
                                required style="font-size:90%">
                            <input type="hidden" id="id" name="id">
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
            // Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Modifier la société';
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
                        $('#id').val(data.id_user);
                        $('#nom').val(data.nom_user);
                        $('#prenom').val(data.prenom_user);
                        $('#password').val(data.password_user);
                        $('#email').val(data.email_user);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de la récupération des données : ' + error);
                    }
                });
            }
            getDataById(id);
        } else {
            // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Nouvelle société';
            document.getElementById('nom').value = '';
            document.getElementById('prenom').value = '';
            document.getElementById('password').value = '';
            document.getElementById('email').value = '';
        }

        myModal.show();
    }

    function toggleStatus(userId, action) {
        console.log(userId + action);
        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: {
                userId: userId,
                action: action
            },
            success: function(response) {
                location.reload(); // Par exemple, recharger la page pour refléter les changements
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la mise à jour du statut:', error);
            }
        });
    }
    document.getElementById('search2').addEventListener('input', function() {
        var searchValue = this.value.toLowerCase(); // Récupère la valeur saisie et convertit en minuscules

        // Parcourt toutes les lignes du tableau
        var rows = document.querySelectorAll('#agentTable tbody tr');
        rows.forEach(function(row) {
            var nomSociete = row.querySelector('td:nth-child(7)').textContent
                .toLowerCase(); // Récupère le nom de la société dans chaque ligne
            // Vérifie si le nom de la société correspond à la valeur saisie dans le champ de recherche
            if (nomSociete.includes(searchValue)) {
                row.style.display = ''; // Affiche la ligne si elle correspond à la recherche
            } else {
                row.style.display = 'none'; // Masque la ligne si elle ne correspond pas à la recherche
            }
        });
    });
    </script>
</body>

</html>