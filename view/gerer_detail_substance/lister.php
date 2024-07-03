<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
?>
<?php 
$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : NULL;

    if (isset($_POST['submit'])) {
        $substance = $_POST['substance'];
        $granulo = $_POST["granulo"];;
        $transparence = $_POST["transparence"];;
        $degre = $_POST["degre"];
        $forme = $_POST["forme"];
        $durete = $_POST["durete"];
        $categorie = $_POST["categorie"];
        $dimension = $_POST["dimension"];
        $prix = $_POST['prix'];
        $unite = $_POST['unite'];
        $id_detail = $_POST['id'];
        $id_type_substance = $_POST['type_substance'];
        $id_couleur_substance = $_POST['couleur'];
        if(empty($granulo)){
            $granulo=NULL;
        }if(empty($trasparence)){
            $trasparence=NULL;
        }if(empty($degre)){
            $degre=NULL;
        }if(empty($durete)){
            $durete=NULL;
        }if(empty($categorie)){
            $categorie=NULL;
        }if(empty($forme)){
            $forme=NULL;
        }if(empty($dimension)){
            $dimension=NULL;
        }
        echo $substance;

        if (empty($id_detail)) {
            $subInsertQuery = "INSERT INTO `substance`( `nom_substance`, `id_type_substance`) VALUES ('$substance','$id_type_substance')";
            $conn->query($subInsertQuery);
            $id_substance = $conn->insert_id; 

             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `prix_substance`, `unite_prix_substance`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iiiiiiiiid", $id_substance, $granulo, $transparence, $degre, $forme, $durete, $categorie, $dimension, $id_couleur_substance, $prix, $unite);
            if ($stmt->execute()) {
                    $_SESSION['toast_message'] = "Insertion réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Erreur d'enregistrement" . mysqli_error($conn);
                } 
        } else {
            $subInsertQuery = "UPDATE `substance` SET `nom_substance`='$substance',`id_couleur_substance`='$id_couleur_substance',`id_type_substance`='$id_type_substance' WHERE id_substance='$id_detail'";
            $conn->query($subInsertQuery);

            // Mise à jour d'une société existante
            $sql = "UPDATE `substance_detaille_substance` SET `id_substance`='$substance',`id_granulo`='$granulo',`id_transparence`='$trasparence',`id_degre_couleur`='$degre',`id_forme_substance`='$forme',`id_durete`='$durete',`id_categorie`='$categorie',`id_dimension_diametre`='$dimension',`prix_substance`='$prix',`unite_prix_substance`='$unite' WHERE id_substance='$id_detail'";
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
    $sql_edit = "SELECT * FROM `substance` WHERE `id_substance`='$edit_societe_id'";
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
    <title>Ministère des mines</title>
    <link rel="icon" href="../../logo/favicon.ico">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS personnalisé -->
    <link rel="stylesheet" type="text/css" href="style.css">

    <!-- Règles CSS pour la responsivité -->
    <style>
    @media (max-width: 500px) {
        .modal-lg {
            max-width: 100% !important;
            width: 100% !important;
        }
    }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
    <script>
    $(document).ready(function() {
        // Écoutez les changements dans le menu déroulant
        $('#substance').change(function() {
            // Récupérez la valeur sélectionnée
            var selectedValue = $(this).val();
            // Si l'option "Saphir" est sélectionnée
            if (selectedValue == "Saphir") {
                // Rendez les champs input visibles
                $('#select-granulo, #transparence, #categorie, #degre').show();
                // Ajoutez l'attribut 'required' aux champs input
                $('#granulo_input_label input, #transparence_input_label input, #categorie_input_label input, #degre_input_label input')
                    .attr('required', 'required');
            } else {
                // Cachez les champs input
                $('#select-granulo, #transparence, #categorie, #degre').hide();
                // Retirez l'attribut 'required' des champs input
                $('#granulo_input_label input, #transparence_input_label input, #categorie_input_label input, #degre_input_label input')
                    .removeAttr('required');
            }
        });
    });
    </script>
    <style>
    #agentTable {
        display: none;
    }
    </style>

    <?php include "../shared/navBar.php"; ?>
</head>


<body>

    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des substances</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control mb-3" placeholder="Recherche...">
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
                    <th scope="col">Substance</th>
                    <th scope="col">Granulo</th>
                    <th scope="col">Transparence</th>
                    <th scope="col">Degré de couleur</th>
                    <th scope="col">Forme</th>
                    <th scope="col">Dureté</th>
                    <th scope="col">Catégorie</th>
                    <th scope="col">Dimmension ou Diamètre</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Unité</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="substanceTable">
                <!-- Contenu généré par PHP -->
            </tbody>
        </table>
        <div class="container">
            <ul class="pagination" id="pagination">
                <!-- Pagination générée par PHP -->
            </ul>
            <ul class="pagination" id="pagination2">
                <!-- Pagination générée par PHP -->
            </ul>
        </div>
        <div>
            <?php
                include('../../shared/pied_page.php');
            ?>
        </div>

        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
            <div class=" modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle substance</h1>
                        <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="./add_substance.php" method="post">
                            <div class="row">
                                <div class="col">
                                    <label for="type_substance" name="type_substance" class="col-form-label">Type de la
                                        substance: <span style="color:rgb(247, 62, 6)">*</span></label>
                                    <select id="type_substance" name="type_substance" placeholder="Choisir ..."
                                        autocomplete="off" required>
                                        <option value="">Choisir ...</option>
                                        <?php    
                                $query = "SELECT * FROM type_substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_type_substance'] . "'>" . $rowSub['nom_type_substance'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="substance" name="substance" class="col-form-label">Nom de la
                                        substance:</label>
                                    <input type="text" class="form-control" name="substance" id="substance"
                                        placeholder="Nom de la substance" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="couleur" name="couleur" class="col-form-label">Couleur de la
                                        substance:</label>
                                    <select id="couleur" name="couleur[]" placeholder="Choisir ..." autocomplete="off"
                                        multiple>
                                        <option value="">Choisir ...</option>
                                        <?php    
                                $query = "SELECT * FROM couleur_substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_couleur_substance'] . "'>" . $rowSub['nom_couleur_substance'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="categorie" name="categorie" class="col-form-label">Catégorie:</label>
                                    <select id="categorie" name="categorie[]" placeholder="Choisir ..."
                                        autocomplete="off" multiple>
                                        <option value="">Choisir</option>
                                        <?php    
                                $query = "SELECT id_categorie, nom_categorie FROM categorie";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_categorie'] . "'>" . $rowSub['nom_categorie'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="granulo" name="granulo" class="col-form-label">Granulomètrie:</label>
                                    <select id="granulo" name="granulo[]" placeholder="Choisir ..." multiple
                                        autocomplete="off">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                $query = "SELECT id_granulo, nom_granulo FROM granulo";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_granulo'] . "'>" . $rowSub['nom_granulo'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="transparence" name="transparence"
                                        class="col-form-label">Transparence:</label>
                                    <select id="transparence" name="transparence[]" placeholder="Choisir ..." multiple
                                        autocomplete="off">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                $query = "SELECT id_transparence, nom_transparence FROM transparence";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_transparence'] . "'>" . $rowSub['nom_transparence'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="degre" name="degre" class="col-form-label">Degré de la
                                        couleur:</label>
                                    <select id="degre" name="degre[]" multiple placeholder="Choisir ..."
                                        autocomplete="off">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                $query = "SELECT id_degre_couleur, nom_degre_couleur FROM degre_couleur";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_degre_couleur'] . "'>" . $rowSub['nom_degre_couleur'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="durete" name="durete" class="col-form-label">Dureté de la
                                        substance:</label>
                                    <select id="durete" name="durete[]" multiple placeholder="Choisir ..."
                                        autocomplete="off">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                $query = "SELECT id_durete, nom_durete FROM durete";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_durete'] . "'>" . $rowSub['nom_durete'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="dimension" name="dimension" class="col-form-label">Dimension ou
                                        Diamètre:</label>
                                    <select id="dimension" name="dimension[]" multiple placeholder="Choisir ..."
                                        autocomplete="off">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                $query = "SELECT id_dimension_diametre, nom_dimension_diametre FROM dimension_diametre";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_dimension_diametre'] . "'>" . $rowSub['nom_dimension_diametre'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="forme" name="forme" class="col-form-label">Forme de la
                                        substance:</label>
                                    <select id="forme" name="forme[]" multiple placeholder="Choisir ..."
                                        autocomplete="off">
                                        <option value="">Choisir...</option>
                                        <?php    
                                $query = "SELECT id_forme_substance, nom_forme_substance FROM forme_substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_forme_substance'] . "'>" . $rowSub['nom_forme_substance'] . "</option>";
                                }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="prix" name="prix" class="col-form-label">Prix unitaire (en US
                                        $):</label>
                                    <input type="text" class="form-control" name="prix" id="prix"
                                        placeholder="Prix unitaire en US $" required>
                                    <input type="hidden" id="id" name="id">
                                </div>
                                <div class="col">
                                    <label for="unite" name="unite" class="col-form-label">Unité de la
                                        substance:</label>
                                    <select class="form-select" id="unite" name="unite"
                                        aria-label="Default select example" required>
                                        <option selected>Choisir ...</option>
                                        <option value="g">US $ / Gramme</option>
                                        <option value="kg">US $ / Kilogramme</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    onclick="closeModal()">Close</button>
                                <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Substance Modal -->
        <div class="modal fade" id="editSubstanceModal" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier prix de la substance</h1>
                        <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="editSubstanceForm">
                            <input type="hidden" id="editSubstanceId">
                            <div class="row">
                                <div class="col">
                                    <label for="type_substance " class="fw-bold">Type de la substance :</label>
                                    <input class="form-control" type="text" id="type_substance" name="type_substance">
                                </div>
                                <div class="col">
                                    <label for="id_substance " class="fw-bold">Nom de la substance :</label>
                                    <input type="text" class="form-control" id="id_substance" name="id_substance">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="id_couleur_substance " class="fw-bold">Couleur de la substance :</label>
                                    <input type="text" class="form-control" id="id_couleur_substance"
                                        name="id_couleur_substance">
                                </div>
                                <div class="col">
                                    <label for="id_granulo " class="fw-bold">Granulomètrie :</label>
                                    <input type="text" class="form-control" id="id_granulo" name="id_granulo">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="id_transparence " class="fw-bold">Transparence de la substance
                                        :</label>
                                    <input type="text" class="form-control" id="id_transparence" name="id_transparence">
                                </div>
                                <div class="col">
                                    <label for="id_degre_couleur " class="fw-bold">Degré de couleur:</label>
                                    <input type="text" class="form-control" id="id_degre_couleur"
                                        name="id_degre_couleur">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="id_forme_substance " class="fw-bold">Forme de la substance :</label>
                                    <input type="text" class="form-control" id="id_forme_substance"
                                        name="id_forme_substance">
                                </div>
                                <div class="col">
                                    <label for="id_durete " class="fw-bold">Dureté de la substance :</label>
                                    <input type="text" class="form-control" id="id_durete" name="id_durete">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="id_categorie " class="fw-bold">Catégorie de la substance :</label>
                                    <input type="text" class="form-control" id="id_categorie" name="id_categorie">
                                </div>
                                <div class="col">
                                    <label for="id_dimension_diametre " class="fw-bold">Dimension ou diamètre de la
                                        substance :</label>
                                    <input type="text" class="form-control" id="id_dimension_diametre"
                                        name="id_dimension_diametre">
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="prix_substance " class="fw-bold">Prix de la substance :</label>
                                    <input type="number" class="form-control" name="prix_substance" id="prix_substance"
                                        step="0.01" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    onclick="closeModal()">Close</button>
                                <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>div
    <!-- TomSelect JS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <!-- Votre script personnalisé -->
    <script>
    var myModal;
    var closeModalAfterSubmit = false; // Variable pour vérifier si la modal doit être fermée
    var selectOptions = {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };


    function openModal(edit_id = null) {
        myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });

        if (edit_id) {
            //Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs
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

                        var id_type_substance_value = new TomSelect("#type_substance", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                        var id_couleur_value = new TomSelect("#couleur", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                        var id_granulo_value = new TomSelect("#granulo", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });

                        var id_transparence_value = new TomSelect("#transparence", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                        var id_durete_value = new TomSelect("#durete", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                        var id_degre_value = new TomSelect("#degre", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                        var id_forme_value = new TomSelect("#forme", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                        var id_dimension_value = new TomSelect("#dimension", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                        var id_categorie_value = new TomSelect("#categorie", {
                            create: true,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });

                        var selectedUnite = data.unite_prix_substance;

                        $('#id').val(data.id_substance);
                        id_substance_value.setValue(data.id_substance);
                        id_type_substance_value.setValue(data.id_type_substance);
                        id_couleur_value.setValue(data.id_type_substance);
                        //$('#substance').val(data.nom_substance);
                        id_granulo_value.setValue(data.id_granulo);
                        // $('#transparence').val(data.nom_transparence);
                        id_transparence_value.setValue(data.id_transparence);
                        // $('#degre').val(data.nom_degre_couleur);
                        id_degre_value.setValue(data.id_degre_couleur);
                        // $('#forme').val(data.nom_forme_couleur);
                        id_forme_value.setValue(data.id_forme_substance);
                        // $('#durete').val(data.nom_durete);
                        id_durete_value.setValue(data.id_durete);
                        // $('#categorie').val(data.nom_categorie);
                        id_categorie_value.setValue(data.id_categorie);
                        // $('#dimension').val(data.nom_dimension_diametre);
                        id_dimention_diametre_value.setValue(data.id_dimension_diametre);
                        $('#prix').val(data.prix_substance);
                        $("input[name='unite'][value='" + selectedUnite + "']").prop("checked", true);
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
            document.getElementById('substance').value = '';
            document.getElementById('granulo').value = '';
            document.getElementById('transparence').value = '';
            document.getElementById('degre').value = '';
            document.getElementById('forme').value = '';
            document.getElementById('durete').value = '';
            document.getElementById('categorie').value = '';
            document.getElementById('dimension').value = '';
            document.getElementById('prix').value = '';
            document.getElementById('type_substance').value = '';
            document.getElementById('couleur').value = '';
            //document.getElementById('unite').value = '';


        }

        myModal.show();
        //use tom select
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

        new TomSelect("#granulo", selectOptions);
        new TomSelect("#transparence", selectOptions);
        new TomSelect("#degre", selectOptions);
        new TomSelect("#forme", selectOptions);
        new TomSelect("#durete", selectOptions);
        new TomSelect("#categorie", selectOptions);
        new TomSelect("#dimension", selectOptions);
        new TomSelect("#couleur", selectOptions);
        new TomSelect("#type_substance", selectOptions);
    };

    // Fonction pour fermer la modal et actualiser la page si nécessaire
    function closeModal() {
        if (myModal) {
            myModal.hide();
            // location.reload();

        }
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
    </script>
    <!-- <script>
        new TomSelect('select[multiple]', {plugins:{remove_button:{title: 'Supprimer'}}})
    </script> -->
    <script src="search-pagination.js"></script>
</body>

</html>