<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php
$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;


    if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];
        $adresse = $_POST['adresse'];
        $nif = $_POST['nif'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $id_societe_expediteur = $_POST['id'];
        $responsable = $_POST['responsable'];
        $instat = $_POST['instat'];
        $rcs = $_POST['rcs'];
        $type = $_POST['type'];
        
        if (strlen($contact) !== 10) {
            echo json_encode(array('error' => 'Le numéro de contact doit avoir exactement 10 chiffres.'));
         
            exit();
        }

        // Vérifier la longueur du numéro NIF
        if (strlen($nif) !== 10) {
            echo "Le numéro NIF doit avoir 10 caractères.";
            exit();
        }
        if (empty($id_societe_expediteur)) {
            $numNif_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $nif);
            $uploadDir = '../upload/';
            $fileName_nif = "SCAN_NIF_" .$numNif_cleaned.".". pathinfo($_FILES['pj_nif']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_NIF = $uploadDir . $fileName_nif;

            $numRcs_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $rcs);
            $fileName_rcs = "SCAN_RCS_" .$numRcs_cleaned.".". pathinfo($_FILES['pj_rcs']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_NIF = $uploadDir . $fileName_rcs;

            $numInstat_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $instat);
            $fileName_rcs = "SCAN_INSTAT_" .$numInstat_cleaned.".". pathinfo($_FILES['pj_instat']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_INSTAT = $uploadDir . $numInstat_cleaned;
            //deplacement des fichier
            if (move_uploaded_file($_FILES['pj_nif']['tmp_name'], $uploadPath_NIF)) {
            } else {
            echo "Erreur lors de l'upload du fichier.";
            }
            if (move_uploaded_file($_FILES['pj_rcs']['tmp_name'], $uploadPath_RCS)) {
            } else {
            echo "Erreur lors de l'upload du fichier.";
            }
             if (move_uploaded_file($_FILES['pj_instat']['tmp_name'], $uploadPath_INSTAT)) {
            } else {
            echo "Erreur lors de l'upload du fichier.";
            }
            // Insertion d'une nouvelle société
            $sql = "INSERT INTO `societe_expediteur`(`nom_societe_expediteur`, `adresse_societe_expediteur`, `nif_societe_expediteur`, `contact_societe_expediteur`, `email_societe_expediteur`, responsable, `instat`, `rcs`, `pj_nif`, `pj_rcs`, `pj_instat`, `validation`,`type`) VALUES ('$nom','$adresse','$nif','$contact','$email', '$responsable', '$instat', '$rcs', '$uploadPath_NIF','$uploadPath_RCS', '$uploadPath_INSTAT', 'En attente', '$type')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Erreur d'enregistrement" . mysqli_error($conn);
            }
        } else {
            $numNif_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $nif);
            $uploadDir = '../upload/';
            $fileName_nif = "SCAN_NIF_" .$numNif_cleaned.".". pathinfo($_FILES['pj_nif']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_NIF = $uploadDir . $fileName_nif;

            $numRcs_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $rcs);
            $fileName_rcs = "SCAN_RCS_" .$numRcs_cleaned.".". pathinfo($_FILES['pj_rcs']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_NIF = $uploadDir . $fileName_rcs;

            $numInstat_cleaned = preg_replace('/[^a-zA-Z0-9]/', '-', $instat);
            $fileName_rcs = "SCAN_INSTAT_" .$numInstat_cleaned.".". pathinfo($_FILES['pj_instat']['name'],
            PATHINFO_EXTENSION);
            $uploadPath_INSTAT = $uploadDir . $numInstat_cleaned;
            //deplacement des fichier
             
            if(!empty($uploadPath_NIF)){
                $sql = "UPDATE `societe_expediteur` SET `pj_nif`='$uploadPath_NIF' WHERE `id_societe_expediteur`='$id_societe_expediteur'";
                $result = mysqli_query($conn, $sql);

                 if (move_uploaded_file($_FILES['pj_nif']['tmp_name'], $uploadPath_NIF)) {
                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
            }
            if(!empty($uploadPath_INSTAT)){
                $sql = "UPDATE `societe_expediteur` SET `pj_instat`='$uploadPath_INSTAT' WHERE `id_societe_expediteur`='$id_societe_expediteur'";
                $result = mysqli_query($conn, $sql);
                if (move_uploaded_file($_FILES['pj_instat']['tmp_name'], $uploadPath_INSTAT)) {
                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
            }
            if(!empty($uploadPath_RCS)){
                $sql = "UPDATE societe_expediteur SET `pj_rcs`='$uploadPath_RCS' WHERE `id_societe_expediteur`='$id_societe_expediteur'";
                $result = mysqli_query($conn, $sql);
                if (move_uploaded_file($_FILES['pj_rcs']['tmp_name'], $uploadPath_RCS)) {
                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
            }
            // Mise à jour d'une société existante
            $sql = "UPDATE `societe_expediteur` SET `nom_societe_expediteur`='$nom', `adresse_societe_expediteur`='$adresse', `nif_societe_expediteur`='$nif', `contact_societe_expediteur`='$contact', `email_societe_expediteur`='$email', `responsable`='$responsable', `type`='$type', `instat`='$instat', `rcs`='$rcs',`validation`='En attente' WHERE `id_societe_expediteur`='$id_societe_expediteur'";
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
$edit_societe_details = array();

if (!empty($edit_societe_id)) {
    $sql_edit = "SELECT * FROM `societe_expediteur` WHERE `id_societe_expediteur`='$edit_societe_id'";
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
    <link rel="icon" href="../../logo/favicon.ico">
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

    main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        /* background-color: #f0f0f0; Couleur de fond */
        background-color: #ffffff;
    }

    .centered-container {
        text-align: center;
    }

    .rounded-border {
        background-color: #fff;
        /* Couleur de fond du cadre */
        border-radius: 10px;
        /* Bordure arrondie */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        /* Ombre légère */
        padding: 20px;
        width: 100%;
        max-width: 1000px;
        /* Largeur maximale du cadre */
    }
    </style>
    <style>
    .custom-text-justify {
        text-align: justify;
        text-justify: inter-word;
        /* Ajoutez cette propriété pour une justification plus précise */
    }
    </style>
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
                <h5>Liste des sociétés expediteur</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche par nom...">
            </div>
            <div class="col text-end">
                <a class="btn btn-success btn-sm rounded-pill px-3 " href="./exporter.php?"><i
                        class="fas fa-file-excel"></i> Exporter en excel</a>
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
                    <th scope="col">Adresse</th>
                    <th scope="col">NIF</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql="SELECT * FROM `societe_expediteur`";
                $result= mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                  ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $row['nom_societe_expediteur'] ?></td>
                    <td><?php echo $row['adresse_societe_expediteur'] ?></td>
                    <td><?php echo $row['nif_societe_expediteur'] ?></td>
                    <td><?php echo $row['contact_societe_expediteur'] ?></td>
                    <td><?php echo $row['validation'] ?></td>
                    <td>
                        <a class="link-dark"
                            href="./detail.php?id=<?php echo $row['id_societe_expediteur']; ?>">détails</a>
                        <a href="#" class="link-dark" onclick="openModal(<?php echo $row['id_societe_expediteur']?>)"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="#" class="link-dark"
                            onclick="confirmerSuppression(<?php echo $row['id_societe_expediteur']?>)"><i
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle société</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="societeForm" action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col">
                                <label for="nom" name="nom" class="col-form-label">Nom de la société:</label>
                                <input type="text" class="form-control" name="nom" id="nom" placeholder="Nom complète"
                                    required style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="type" name="type" class="col-form-label">Type de la société:</label>
                                <select class="form-control" name="type" id="type" required>
                                    <option value="">Séléctionner ...</option>
                                    <option value="SA">SA</option>
                                    <option value="SARL">SARL</option>
                                    <option value="SARLU">SARLU</option>
                                    <option value="Entreprise Individuelle">Entreprise Individuelle</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="responsable" name="nom" class="col-form-label">Responsable:</label>
                                <input type="text" class="form-control" name="responsable" id="responsable"
                                    placeholder="Nom complète" required style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="email" name="email" class="col-form-label">Email de la société:</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Adresse email" required style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="contact" name="contact" class="col-form-label">Contact de la
                                    société:</label>
                                <input type="number" class="form-control" id="contact" name="contact"
                                    placeholder="Numéro de téléphone" required style="font-size:90%"
                                    pattern="[0-9]{10}">
                            </div>
                            <div class="col">
                                <label for="adresse" name="adresse" class="col-form-label">Adresse de la
                                    société:</label>
                                <input type="text" class="form-control" id="adresse" name="adresse"
                                    placeholder="Adresse complète" required style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="nif" name="nif" class="col-form-label">NIF de la société:</label>
                                <input type="number" class="form-control" id="nif" name="nif"
                                    placeholder="Numéro d'identification fiscale" required style="font-size:90%"
                                    pattern="[0-9]{10}">
                            </div>
                            <div class="col">
                                <label for="pj_nif" name="pj_nif" class="col-form-label">Scan de NIF de la
                                    société:</label>
                                <input type="file" class="form-control" id="pj_nif" name="pj_nif"
                                    placeholder="Numéro d'identification fiscale" style="font-size:90%"
                                    pattern="[0-9]{10}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="rcs" name="rcs" class="col-form-label">N° RCS de la société:</label>
                                <input type="text" class="form-control" id="rcs" name="rcs"
                                    placeholder="Numéro d'identification fiscale" required style="font-size:90%"
                                    pattern="[0-9]{10}">
                            </div>
                            <div class="col">
                                <label for="pj_rcs" name="pj_rcs" class="col-form-label">Scan de RCS de la
                                    société:</label>
                                <input type="file" class="form-control" id="pj_rcs" name="pj_rcs"
                                    placeholder="Numéro d'identification fiscale" style="font-size:90%"
                                    pattern="[0-9]{10}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="instat" name="instat" class="col-form-label">N° STAT de la
                                    société:</label>
                                <input type="text" class="form-control" id="instat" name="instat"
                                    placeholder="Numéro d'INSTAT" required style="font-size:90%" pattern="[0-9]{10}">
                            </div>
                            <div class="col">
                                <label for="pj_instat" name="pj_instat" class="col-form-label">Scan d'STAT de la
                                    société:</label>
                                <input type="file" class="form-control" id="pj_instat" name="pj_instat"
                                    placeholder="Numéro d'identification fiscale" style="font-size:90%"
                                    pattern="[0-9]{10}">
                            </div>
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
                        $('#id').val(data.id_societe_expediteur);
                        $('#nom').val(data.nom_societe_expediteur);
                        $('#adresse').val(data.adresse_societe_expediteur);
                        $('#nif').val(data.nif_societe_expediteur);
                        $('#contact').val(data.contact_societe_expediteur);
                        $('#email').val(data.email_societe_expediteur);
                        $('#responsable').val(data.responsable);
                        $('#rcs').val(data.rcs);
                        $('#instat').val(data.instat);
                        $('#type').val(data.type);
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
            document.getElementById('adresse').value = '';
            document.getElementById('nif').value = '';
            document.getElementById('contact').value = '';
            document.getElementById('email').value = '';
            document.getElementById('responsable').value = '';
            document.getElementById('id').value = '';
        }

        myModal.show();
        document.getElementById('societeForm').addEventListener('submit', function(event) {
            if (document.getElementById('contact').value.length !== 10) {
                // Empêche la soumission du formulaire s'il y a une erreur
                event.preventDefault();
                alert("Le numéro de contact doit avoir exactement 10 chiffres.");
            }
            // Vous pouvez ajouter d'autres vérifications si nécessaire
            if (document.getElementById('nif').value.length !== 10) {
                // Empêche la soumission du formulaire s'il y a une erreur
                event.preventDefault();
                alert("Le numéro de NIF doit avoir exactement 10 chiffres.");
            }
        });
    }
    </script>
</body>

</html>