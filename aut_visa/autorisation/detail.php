<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    $validation_sce = $fonctionUsers. ' ' . $nom_user. ' '.$prenom_user;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT aut.*, vo.*, pay.*, cont.*, agent.* FROM `autorisation` AS aut 
            LEFT JOIN vol AS vo ON vo.id_vol = aut.id_vol 
            LEFT JOIN pays AS pay ON pay.id_pays = aut.id_pays 
            LEFT JOIN continent AS cont ON cont.id_continent = pay.id_continent 
            LEFT JOIN agent_controle AS agent ON agent.id_agent_controle = aut.id_agent_controle
            WHERE id_autorisation = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        $validation_aut=$row['validation_autorisation'];
        $user_validation = $row['user_validation_autorisation'];

        $stmt->close();
   } else {
        echo "<p>Aucune information trouvée pour cet ID LP.</p>";
    }

    if (isset($_POST['submit'])) {
        $id_aut = $_POST['id_data'];
        $action = $_POST['action'];
        $sql="UPDATE `autorisation` SET `validation_autorisation`='$action', `user_validation_autorisation`='$validation_sce' WHERE id_autorisation=$id_aut";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['toast_message'] = "Validation réussie.";
             header("Location: https://cdc.minesmada.org/aut_visa/autorisation/detail.php?id=" . $id_aut);
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <style>
    body {
        margin: 0;
    }
    </style>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
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

    .info {
        padding-left: 8.5%;
        padding-right: 8.5%;
        font-size: small;
    }

    #infon1 #info2 {
        display: inline-block;
    }

    .info1 {
        width: 40%;
        float: left;

    }

    .info2 {
        width: 57%;
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
    </style>
    <title>Information sur un PV</title>
    <?php include_once('../header.php'); ?>

</head>

<body>
    <div class="info  container">
        <p class="text-center mb-0">Détails d'une autorisation</p>
        <hr>
        <?php 
        if(($code_fonction=="A")||($code_fonction=="B")){ ?>

        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <?php
                    $selectedValue = $validation_aut; // Exemple de valeur
                        function isSelected($value, $selectedValue) {
                        return $value === $selectedValue ? 'selected' : '';
                    }
                    ?>
                    <input type="hidden" value="<?php echo $id; ?>" name="id_data" id="id_data">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire
                        </option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En
                            attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php }else { 
                if(empty($validation_aut)){
                    echo '<p class="alert alert-info">Status:En attente.</p>';
                }else{
                    echo '<p class="alert alert-info">Status:'.$validation_aut.' Validateur: '.$user_validation.'.</p>';
                }
             } ?>
        <hr>
        <div class="info1">
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur l'autorisation</h5>
                <hr>
                <p><strong>Numéro de l'autorisation:</strong> <?php echo $row['numero_autorisation']; ?></p>
                <p><strong>Date de création:</strong><?php echo date('d/m/Y', strtotime($row['date_creation'])); ?></p>
                <p><strong>Date de
                        modification:</strong><?php echo date('d/m/Y', strtotime($row['date_modification'])); ?></p>
                <p><strong>Date de départ:</strong><?php echo date('d/m/Y', strtotime($row['date_depart'])); ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($row['pj_autorisation'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['numero_autorisation'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                <p><strong>Numéro de la facture:</strong> <?php echo $row['numero_facture']; ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($row['scan_facture'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['numero_facture'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur l'éxpediteur</h5>
                <hr>
                <p><strong>Nom:</strong> <?php echo $row['nom_porteur']; ?></p>
                <p><strong>Prénom(s):</strong><?php echo $row['prenom_porteur']; ?></p>
                <p><strong>Numéro du passeport:</strong><?php echo $row['numero_passeport']; ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($row['scan_passeport'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['numero_passeport'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur le vol</h5>
                <hr>
                <p><strong>Numréro du vol:</strong><?php echo $row['numero_vol']; ?></p>
                <p><strong>Nom de la compagnie:</strong><?php echo $row['nom_compagnie']; ?></p>
                <p><strong>Destination du vol:</strong><?php echo $row['destination_vol']; ?></p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur le Pays</h5>
                <hr>
                <p><strong>Nom du continent:</strong><?php echo $row['nom_continent']; ?></p>
                <p><strong>Nom du pays:</strong><?php echo $row['nom_pays']; ?></p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur le colis</h5>
                <hr>
                <p><strong>Designation:</strong><?php echo $row['designation']; ?></p>
                <p><strong>Poids:</strong><?php echo $row['poids']." ".$row['unite']; ?></p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur l'agent responsable</h5>
                <hr>
                <p><strong>Matricule:</strong><?php echo $row['matricule']; ?></p>
                <p><strong>Nom:</strong><?php echo $row['nom_agent']; ?></p>
                <p><strong>Prénom(s):</strong><?php echo $row['prenom_agent']; ?></p>
            </div>
        </div>
        <div class="info2">
            <div class="alert alert-light" role="alert">
                <?php
                        // Emplacement du fichier PDF
                        if($row['validation_autorisation']!='Validé'){
                            $pdfFilePath = $row['pj_autorisation'];
                        }else{
                            $pdfFilePath = $row['lien_autorisation'];
                        }
                        include "../../view_user/cdc/convert.php";
                    ?>
            </div>
        </div>
        <div id="modifier_scan_form"></div>
        <div id="nouveau_scan_form"></div>
    </div>
    <?php
    
    ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    function refreshIframe(pj) {
        var pj_pv_scellage = pj.replace('../', '');
        var pdfFilePathSc = 'cdc.minesmada.org/view_user/' + pj_pv_scellage;
        // Mettre à jour l'attribut src de l'iframe avec le nouveau lien PDF
        $('#pdfIframe').attr('src', 'https://docs.google.com/gview?url=' + encodeURIComponent(
                pdfFilePathSc) +
            '&embedded=true');
    }
    $(document).ready(function() {
        $('.toast').toast('show');
        $(".btn-nouveau-scan").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('nouveau_scan_form', '../pv_controle_gu/nouveau_scan.php?id=' + id_data_cc,
                'staticBackdrop3');

        });
        $(".btn-modifier-scan").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('modifier_scan_form', '../pv_controle_gu/edit_scan.php?id=' + id_data_cc,
                'staticBackdrop3');

        });


        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });
    </script>
</body>

</html>