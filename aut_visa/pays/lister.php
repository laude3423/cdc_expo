<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php 

$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;

    if (isset($_POST['submit'])) {
        $code_alpha2 = htmlspecialchars($_POST['code_alpha2']);
        $code_alpha3 = htmlspecialchars($_POST['code_alpha3']);
        $code_numerique = htmlspecialchars($_POST['code_numerique']);
        $nom_pays = htmlspecialchars($_POST['nom_pays']);
        $capitale = $_POST['capitale'];
        $indepandent = $_POST['independant'];
        $nationalite = $_POST['nationalite'];
        $id_continent = $_POST['continent'];
        $id_pays = $_POST['id'];

        if (empty($id_pays)) {
            // Insertion d'une nouvelle société
            $stmt = $conn->prepare("INSERT INTO `pays`( `code_alpha_2`, `code_alpha_3`, `code_numerique`, `nom_pays`, `capitale`,
            `indepandant`, `nationalite`, `id_continent`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssi", $code_alpha2, $code_alpha_3, $code_numerique, $nom_pays, $capitale, $indepandent,
        $nationalite, $id_continent);
            if ($stmt->execute()) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
            }
        } else {
            // Requête de mise à jour
            $stmt = $conn->prepare("UPDATE `pays` SET `code_alpha_2` = ?, `code_alpha_3` = ?, `code_numerique` = ?, `nom_pays` = ?, `capitale` = ?, `indepandant` = ?, `nationalite` = ?, `id_continent` = ? WHERE `id_pays` = ?");

            // Liaison des paramètres
            $stmt->bind_param("sssssssii", $code_alpha2, $code_alpha3, $code_numerique, $nom_pays, $capitale, $indepandent, $nationalite, $id_continent, $id_pays);

            // Exécution de la requête et vérification
            if ($stmt->execute()) {
                $_SESSION['toast_message'] = "Mise à jour réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur lors de la mise à jour : " . mysqli_error($conn);
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
    include "../header.php";
    ?>
</head>

<body>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des Pays</h5>
            </div>
            <div class="col">
                <input type="text" id="searchR" class="form-control" placeholder="Recherche...">
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
                    <th scope="col">CL2</th>
                    <th scope="col">CL3</th>
                    <th scope="col">CN</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Cap</th>
                    <th scope="col">Nationalité</th>
                    <th scope="col">Continent</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql="SELECT pay.*, cont.* FROM `continent` AS cont LEFT JOIN pays AS pay ON 
                pay.id_continent=cont.id_continent";
                $result= mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                  ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $row['code_alpha_2'] ?></td>
                    <td><?php echo $row['code_alpha_3'] ?></td>
                    <td><?php echo $row['code_numerique'] ?></td>
                    <td><?php echo $row['nom_pays'] ?></td>
                    <td><?php echo $row['capitale'] ?></td>
                    <td><?php echo $row['nationalite'] ?></td>
                    <td><?php echo $row['nom_continent'] ?></td>
                    <td>
                        <a href="#" class="link-dark" onclick="openModal(<?php echo $row['id_pays']?>)"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="#" class="link-dark" onclick="confirmerSuppression(<?php echo $row['id_pays']?>)"><i
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouveau agent</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" id="id" name="id">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="code_alpha2" name="code_alpha2" class="col-form-label">Code alpha
                                        2:</label>
                                    <input type="text" class="form-control" name="code_alpha2" id="code_alpha2"
                                        placeholder="Code alpha 2" required style="font-size:90%">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="code_alpha3" name="code_alpha3" class="col-form-label">Code alpha
                                        3:</label>
                                    <input type="text" class="form-control" name="code_alpha3" id="code_alpha3"
                                        placeholder="Code alpha 3" required style="font-size:90%">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="code_numerique" name="code_numerique" class="col-form-label">Code
                                        numérique:</label>
                                    <input type="number" class="form-control" name="code_numerique" id="code_numerique"
                                        placeholder="Code numérique" required style="font-size:90%">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="capitale" name="capitale" class="col-form-label">Capitale:</label>
                                    <input type="text" class="form-control" name="capitale" id="capitale"
                                        placeholder="Nom du caputale" required style="font-size:90%">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nom_pays" name="nom_pays" class="col-form-label">Nom du pays:</label>
                                    <input type="text" class="form-control" name="nom_pays" id="nom_pays"
                                        placeholder="Nom du pays" required style="font-size:90%">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <label for="independant" name="independant"
                                            class="col-form-label">Indépandant:</label>
                                        <select class="form-select" name="independant" id="independant"
                                            placeholder="Choisir ..." aria-label="Default select example" required>
                                            <option value="" selected disabled>Choisir ...</option>
                                            <option value="Oui">OUI</option>
                                            <option value="Non">NON</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="nationalite" name="nationalite"
                                        class="col-form-label">Nationalité:</label>
                                    <input type="text" class="form-control" name="nationalite" id="nationalite"
                                        placeholder="Nationalite" required style="font-size:90%">
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="continent" name="continent" class="col-form-label">Continent:</label>
                                    <select class="form-select" name="continent" id="continent"
                                        placeholder="Choisir ..." aria-label="Default select example" required>
                                        <option value="" selected disabled>Choisir ...</option>
                                        <?php    
                                        $query = "SELECT * FROM continent";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo "<option value='" . $rowSub['id_continent'] ."'>" . $rowSub['nom_continent'] .' '.$rowSub['prenom_agent']. "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

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
            document.getElementById('staticBackdropLabel').innerText = 'Modifier Pays';
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
                        $('#id').val(data.id_pays);
                        $('#code_alpha2').val(data.code_alpha_2);
                        $('#code_alpha3').val(data.code_alpha_3);
                        $('#code_numerique').val(data.code_numerique);
                        $('#nom_pays').val(data.nom_pays);
                        $('#capitale').val(data.capitale);
                        $('#independant').val(data.indepandant);
                        $('#nationalite').val(data.nationalite);
                        $('#continent').val(data.id_continent);
                        console.log(data.continent);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de la récupération des données : ' + error);
                    }
                });
            }
            getDataById(id);
        } else {
            // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Nouveau Pays';
            document.getElementById('code_alpha2').value = '';
            document.getElementById('code_alpha3').value = '';
            document.getElementById('code_numerique').value = '';
            document.getElementById('nom_pays').value = '';
            document.getElementById('capitale').value = '';
            document.getElementById('independant').value = '';
            document.getElementById('nationalite').value = '';
            document.getElementById('continent').value = '';
            document.getElementById('id').value = '';
        }

        myModal.show();
    }

    document.getElementById('searchR').addEventListener('input', function() {
        var searchValue = this.value.toLowerCase(); // Récupère la valeur saisie et convertit en minuscules

        // Parcourt toutes les lignes du tableau
        var rows = document.querySelectorAll('#agentTable tbody tr');
        rows.forEach(function(row) {
            var nomSociete = row.querySelector('td:nth-child(5)').textContent
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