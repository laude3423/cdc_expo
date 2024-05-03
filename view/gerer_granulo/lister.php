<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php 
    if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];
        $min = $_POST['min'];
        $max = $_POST['max'];
        $id_granulo = $_POST['id'];
            // Insertion d'une nouvelle société
        $sql = "INSERT INTO `granulo`(`nom_granulo`, `granulo_min`, `granulo_max`) VALUES ('$nom','$min','$max')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
        } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
        
    }
    if(isset($_SESSION['toast_message'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
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
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
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


</head>

<body>
    <div class="container">
        <div class="row mb-3" style="margin-top: 30px;">
            <div class="col md-8 mb-3">
                <h5>Liste des granulométries</h5>
            </div>
            <div class="col md-10 text-end">
                <a class="btn btn-dark btn-sm rounded-pill px-3 mb-3" href="#" onclick="openModal()"><i
                        class="fa-solid fa-add me-1"></i>Ajouter nouveau</a>
            </div>
        </div>
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Nom de granulo</th>
                    <th scope="col">Granulo min</th>
                    <th scope="col">Granulo max</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql="SELECT * FROM `granulo`";
                $result= mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                  ?>
                <tr>
                    <td><?php echo $row['nom_granulo'] ?></td>
                    <td><?php echo $row['granulo_min'] ?></td>
                    <td><?php echo $row['granulo_max'] ?></td>
                    <td>
                        <a href="#" class="link-dark" onclick="openModal(<?php echo $row['id_granulo']?>)"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="#" class="link-dark" onclick="confirmerSuppression(<?php echo $row['id_granulo']?>)"><i
                                class="fa-solid fa-trash "></i></a>
                    </td>
                </tr>
                <?php   
                }

                ?>


                <tr>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouveau agent</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="nom" name="nom" class="col-form-label">Nom du granulo:</label>
                            <input type="text" class="form-control" name="nom" id="nom" placeholder="Nom du granulo"
                                required style="font-size:90%">
                            <input type="hidden" id="id" name="id">
                        </div>
                        <div class="mb-3">
                            <label for="min" name="min" class="col-form-label">Granulo min:</label>
                            <input type="number" class="form-control" name="min" id="min"
                                placeholder="Valeur min du granulo" required style="font-size:90%">
                        </div>
                        <div class="mb-3">
                            <label for="max" name="max" class="col-form-label">Granulo max:</label>
                            <input type="number" class="form-control" name="max" id="max"
                                placeholder="Valeur max du granulo" required style="font-size:90%">
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
            document.getElementById('staticBackdropLabel').innerText = 'Modifier granulo';
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
                        $('#id').val(data.id_granulo);
                        $('#nom').val(data.nom_granulo);
                        $('#min').val(data.granulo_min);
                        $('#max').val(data.granulo_max);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de la récupération des données : ' + error);
                    }
                });
            }
            getDataById(id);
        } else {
            // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Nouveau granulo';
            document.getElementById('nom').value = '';
            document.getElementById('min').value = '';
            document.getElementById('max').value = '';
        }

        myModal.show();
    }
    </script>
</body>

</html>