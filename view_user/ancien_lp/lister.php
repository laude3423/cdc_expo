<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php
if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $id = $_POST['id'] ?? null;
    $numero_lp = $_POST['num_lp'] ?? null;
    $type_lp = $_POST['type_lp'] ?? null;
    $folio = $_POST['folio'] ?? null;
    $quantite = $_POST['quantite'];
    $unite = $_POST['unite'];
    $num_lp = $_POST['num_lp'] ?? null;
    $titulaire = $_POST['nom_titulaire'] ?? null;
    $date_creation = $_POST['date_creation'] ?? null;
    $type_permis = $_POST['type_permis'] ?? null;
    $numero_permis = $_POST['num_permis'] ?? null;
    $nom_substance = $_POST['nom_substance'] ?? null;
    $nom_commercant = $_POST['nom_commercant'] ?? null;
    $numero_autorisation = $_POST['numero_autorisation'] ?? null;
    $nom_transformateur = $_POST['nom_transformateur'] ?? null;

    // Effectuer une validation des données si nécessaire
    $dateFormat = "Y-m-d";
    $date = date($dateFormat);
    $dateInsert = date($dateFormat);
    $anneeActuelle = date('Y');
    $moisActuel = date('m');
    $num_as="";
    $validation_lp="En attente";
    $sql ="SELECT * FROM ancien_lp WHERE numero_lp='$numero_lp'";
    $resultLp = mysqli_query($conn, $sql);
    $rowLp = mysqli_fetch_assoc($resultLp);
    if(empty($rowLp['numero_lp'])){
        $uploadDir = '../upload/';
        $num_passeport = preg_replace('/[^a-zA-Z0-9]/', '-', $num_lp);
        $fileName_LP = "SCAN_LP_" .$num_passeport.".".
        pathinfo($_FILES['scan_lp']['name'], PATHINFO_EXTENSION);
        $uploadPath_LP = $uploadDir . $fileName_LP;
        //deplacement des fichier
        if (move_uploaded_file($_FILES['scan_lp']['tmp_name'], $uploadPath_LP)) {
        } else {
        echo "Erreur lors de l'upload du fichier.";
        }
        if($type_lp=="LPII"){
            $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, nom_transformateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssss',$validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $nom_transformateur);
            $result = $stmt->execute();

            if ($result) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
            }
    }else if($type_lp == "FDC"){
        $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, numero_autorisation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sdssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $numero_autorisation);
        $result = $stmt->execute();
        if ($result) {
            $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
        }
        
    }else if($type_lp == "LPIIIC"){
        $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, nom_commercant) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sdssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $nom_commercant);
        $result = $stmt->execute();
        if ($result) {
            $_SESSION['toast_message'] = "Insertion réussie.";
                header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
        }
        // }else if($type_lp == "FDC"){
        //     $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, nom_exportateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        //     $stmt = $conn->prepare($query);
        //     $stmt->bind_param('sdssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $nom_exporteur);
        //     $result = $stmt->execute();
        //     if ($result) {
        //         $_SESSION['toast_message'] = "Insertion réussie.";
        //             header("Location: ".$_SERVER['PHP_SELF']);
        //         exit();
        //     } else {
        //         echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
        //     }
        }else if($type_lp=="LPI"){
            $query = "INSERT INTO ancien_lp (validation_lp, quantite, unite, type_lp, date_creation, numero_folio, numero_lp, titulaire_lp, scan_lp, type_permis, numero_permis, nom_substance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sdssssssssss', $validation_lp, $quantite, $unite, $type_lp, $date_creation, $folio, $numero_lp, $titulaire, $uploadPath_LP, $type_permis, $numero_permis, $nom_substance);
            $result = $stmt->execute();
            if ($result) {
                $_SESSION['toast_message'] = "Insertion réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors l\'insertion.</div>';
            }
        }
    }else{
        $_SESSION['toast_message2'] = "LP déjà existe.";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
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
                ' . $_SESSION['toast_message2'] . '
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
    .hidden {
        display: none;
    }

    #agentTable {
        display: none;
    }

    td {
        font-size: small;
    }
    </style>


    <title>Ministere des mines</title>
    <?php include_once('../../view/shared/navBar.php'); ?>
</head>

<body>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des anciens LP</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche...">
            </div>
            <div class="col text-end">
                <a class="btn btn-dark btn-sm rounded-pill px-3 " href="#" onclick="openModal()"><i
                        class="fa-solid fa-add me-1"></i>Ajouter nouveau</a>
            </div>
        </div>
        <hr>
        <?php 
                $sql="SELECT * FROM `ancien_lp` WHERE validation_lp IS NOT NULL";
                $result= mysqli_query($conn, $sql);
                 if ($result->num_rows > 0) { ?>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table id="agentTable" class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Numéro</th>
                    <th scope="col">Date</th>
                    <th class="masque2" scope="col">N° folio</th>
                    <th class="masque2" scope="col">Titulaire</th>
                    <th class="masque1" scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <?php if($row["validation_lp"]=='Validé'){
                            echo'<td>✅</td>';
                        }else{
                            echo'<td>⚠️</td>';
                        } ?>
                    <td><?php echo $row['numero_lp'] ?></td>
                    <td><?php echo date("d/m/Y", strtotime($row["date_creation"]))?></td>
                    <td class="masque2"><?php echo $row['numero_folio'] ?></td>
                    <td class="masque2"><?php echo $row['titulaire_lp'] ?></td>
                    <td class="masque1"><?php echo $row['validation_lp'] ?></td>
                    <td>
                        <a class="link-dark" href="./detail.php?id=<?php echo $row['id_ancien_lp']; ?>">détails</a>
                        <?php if($row['validation_lp']=='Validé') { ?>
                        <a href="#" class="link-dark" data-toggle="tooltip" title="LP déjà validée">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>
                        <a href="#" data-toggle="tooltip" title="LP déjà validée" class="link-dark">
                            <i class="fa-solid fa-trash"></i>
                        </a>

                        <?php } else {?>
                        <a href="#" class="link-dark btn_edit_ancien"
                            data-id="<?= htmlspecialchars($row["id_ancien_lp"])?>">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>
                        <a href="#" class="link-dark"
                            onclick="confirmerSuppression(<?php echo $row['id_ancien_lp']?>)"><i
                                class="fa-solid fa-trash "></i></a>
                        <?php }?>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle autorisation</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="./insert.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id">
                        <div class="row">
                            <div class="col">
                                <label for="type_lp" class="col-form-label">Type de LP:</label>
                                <select id="type_lp" class="form-select" name="type_lp" placeholder="Choisir ..."
                                    autocomplete="off" required style="font-size:90%" onchange="showFields()">
                                    <option value="">Choisir ...</option>
                                    <option value="LPI">LPI</option>
                                    <option value="LPII">LPII</option>
                                    <option value="LPIIIC">LPIIIC</option>
                                    <option value="FDC">Fiche de contôle</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="folio" class="col-form-label">Numéro du folio:</label>
                                <input type="text" class="form-control" name="folio" id="folio"
                                    placeholder="Numéro du folio" required style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="num_lp" class="col-form-label">Numéro du LP:</label>
                                <input type="text" class="form-control" name="num_lp" id="num_lp"
                                    placeholder="Numéro du Laisser-passer" required style="font-size:90%">
                            </div>
                            <div class="col">
                                <label for="nom_titulaire" class="col-form-label">Titulaire:</label>
                                <input type="text" class="form-control" name="nom_titulaire" id="nom_titulaire"
                                    placeholder="Nom du titulaire" required style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="date_creation" class="col-form-label">Date de création:</label>
                                <input type="date" class="form-control" name="date_creation" id="date_creation"
                                    placeholder="date de creation" style="font-size:90%" required>
                            </div>
                            <div class="col">
                                <label for="scan_lp" id="label-scan" class="col-form-label hidden">Scan du LP:</label>
                                <label for="scan_lp" id="label-fiche" class="col-form-label hidden">Scan de la fiche
                                    de
                                    contrôle:</label>
                                <input type="file" class="form-control" name="scan_lp" id="scan_lp" accept=".pdf"
                                    placeholder="Scan du Laisser-passer" required style="font-size:90%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="quantite" class="col-form-label">Quantité:</label>
                                <input type="number" class="form-control" name="quantite" id="quantite"
                                    placeholder="Quantite" style="font-size:90%" step="0.01" required>
                            </div>
                            <div class="col">
                                <label for="unite" class="col-form-label">Unite:</label>
                                <select id="unite" class="form-select" name="unite" placeholder="Choisir ..."
                                    autocomplete="off" required style="font-size:90%">
                                    <option value="">Choisir ...</option>
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 hidden" id="row1">
                            <div class="row">
                                <div class="col">
                                    <label for="type_permis" class="col-form-label">Type du permis:</label>
                                    <select id="type_permis" class="form-select" name="type_permis"
                                        placeholder="Choisir ..." autocomplete="off" style="font-size:90%">
                                        <option value="">Choisir ...</option>
                                        <option value="PRE">PRE</option>
                                        <option value="PE">PE</option>
                                        <option value="ZE">ZE</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="num_permis" class="col-form-label">Numéro du permis:</label>
                                    <input type="text" class="form-control" name="num_permis" id="num_permis"
                                        placeholder="Numéro du permis" style="font-size:90%">
                                </div>
                                <div class="col">
                                    <label for="nom_substance" class="col-form-label">Nom de la substance:</label>
                                    <input type="text" class="form-control" name="nom_substance" id="nom_substance"
                                        placeholder="Nom de la substance" style="font-size:90%">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 hidden" id="row3">
                            <label for="nom_commercant" class="col-form-label">Nom du commerçant:</label>
                            <input type="text" class="form-control" name="nom_commercant" id="nom_commercant"
                                placeholder="Nom du commerçant" style="font-size:90%">

                        </div>
                        <div class="mb-3 hidden" id="row4">
                            <label for="numero_autorisation" class="col-form-label">Numéro de l'autorisation:</label>
                            <input type="text" class="form-control" name="numero_autorisation" id="numero_autorisation"
                                placeholder="Numéro de l'autorisation" style="font-size:90%">
                        </div>
                        <div class="mb-3 hidden" id="row5">
                            <label for="nom_transformateur" class="col-form-label">Nom du transformateur:</label>
                            <input type="text" class="form-control" name="nom_transformateur" id="nom_transformateur"
                                placeholder="Nom de l'exporteur" style="font-size:90%">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                onclick="closeModal()">Fermer</button>
                            <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="edit_ancien_form"></div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function showFields() {
        var typeLP = document.getElementById('type_lp').value;

        document.getElementById('row1').classList.add('hidden');
        document.getElementById('row3').classList.add('hidden');
        document.getElementById('row4').classList.add('hidden');
        document.getElementById('row5').classList.add('hidden');
        document.getElementById('label-fiche').classList.add('hidden');

        if (typeLP === 'LPIIIC') {
            document.getElementById('row3').classList.remove('hidden');
            $('#nom_commercant input').attr('required', true);
            document.getElementById('label-scan').classList.remove('hidden');
        } else if (typeLP === 'FDC') {
            document.getElementById('row4').classList.remove('hidden');
            $('#numero_autorisation input').attr('required', true);
            document.getElementById('label-fiche').classList.remove('hidden');
            document.getElementById('label-scan').classList.add('hidden');
        } else if (typeLP === 'LPII') {
            document.getElementById('row5').classList.remove('hidden');
            $('#num_transformateur input').attr('required', true);
            document.getElementById('label-scan').classList.remove('hidden');
        } else if (typeLP === 'LPI') {
            document.getElementById('row1').classList.remove('hidden');
            $('#type_permis select, #numero_permis input, #nom_substance input').attr('required', true);
            document.getElementById('label-scan').classList.remove('hidden');
        }
    }
    </script>

    <script>
    $(document).ready(function() {
        document.getElementById('label-scan').classList.remove('hidden');
        $('[data-toggle="tooltip"]').tooltip();
        $('.toast').toast('show');
        $(".btn_edit_ancien").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('edit_ancien_form', './edit.php?id=' + id_data_cc,
                'staticBackdrop2');

        });

        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
        // Lorsqu'une option est sélectionnée dans le premier menu
        $("#continent").change(function() {
            var id_continent = $(this).val();
            if (id_continent !== "") {
                $("#id_pays").prop("disabled", false);
                // Charger les pays en fonction du continent sélectionné
                ajax(id_continent);
            } else {
                // Désactiver et réinitialiser le deuxième menu déroulant
                $("#id_pays").prop("disabled", true).html(
                    "<option value=''>Sélectionner d'abord un continent...</option>");
            }
        });
    });

    function ajax(id_continent) {
        $.ajax({
            url: "get.php",
            method: "POST",
            data: {
                continent: id_continent
            },
            dataType: "json",
            success: function(data) {
                const dropdowns = [{
                    id: "#id_pays",
                    options: data.options_pays,
                    emptyMessage: "Aucune..."
                }];

                dropdowns.forEach(dropdown => {
                    if (dropdown.options ===
                        "<option value=''>Sélectionner...</option>") {
                        $(dropdown.id).prop("disabled", true).html(
                            `<option value=''>${dropdown.emptyMessage}</option>`
                        );
                    } else {
                        $(dropdown.id).prop("disabled", false).html(dropdown
                            .options);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log("An error occurred:", error);
                console.log("Response text:", xhr.responseText);
            }
        });
    }

    // Appeler cette fonction lors du chargement de la page pour peupler le menu déroulant

    function openModal(edit_id = null) {
        myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });
        if (edit_id) {
            // Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs

        } else {
            // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Nouveau LP';
            document.getElementById('type_lp').value = '';
            document.getElementById('date_creation').value = '';
            document.getElementById('folio').value = '';
            document.getElementById('num_lp').value = '';
            document.getElementById('nom_titulaire').value = '';
            document.getElementById('scan_lp').value = '';
            document.getElementById('nom_commercant').value = '';
            document.getElementById('num_permis').value = '';
            document.getElementById('type_permis').value = '';
            document.getElementById('nom_substance').value = '';
            document.getElementById('numero_autorisation').value = '';
            document.getElementById('nom_transformateur').value = '';
        }

        myModal.show();
    }

    function closeModal() {
        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });
        myModal.hide();
    }

    function validatePDFInput(event) {
        var fileInput = event.target;
        var filePath = fileInput.value;
        var allowedExtension = /\.pdf$/i;

        if (!allowedExtension.test(filePath)) {
            alert('Veuillez choisir un fichier PDF.');
            fileInput.value = ''; // Clear the file input
            return false;
        }
    }

    document.getElementById('scan_lp').addEventListener('change', validatePDFInput);
    </script>
</body>

</html>